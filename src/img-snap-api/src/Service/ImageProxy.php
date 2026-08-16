<?php

namespace App\Service;

use App\Entity\Image;
use App\Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class ImageProxy {
    private Filesystem $filesystem;

    public function __construct(
        private string $imageCacheDir,
        private int $imageCacheTTL,
        private HttpClientInterface $httpClient,
        private ImageRepository $imageRepository,
        private EntityManagerInterface $entityManager,
    ) {
        $this->filesystem = new Filesystem();
        /** @disregard P1001 Doesn't make sense. mkdir() takes two arguments */
        $this->filesystem->mkdir($this->imageCacheDir, 0o755);
    }

    public function getCacheDir(){
        return $this->imageCacheDir;
    }

    public function getCacheTTL(){
        return $this->imageCacheTTL;
    }

    public function getImagePath(string $name, string $ext = "webp") {
        return Path::join($this->getCacheDir(), "{$name}.{$ext}");
    }

    public function deleteFile(string $name) {
        return $this->filesystem->remove($this->getImagePath($name));
    }

    /**
     * Store image data to slug.webp after converting
     *
     * @throws IOException
     * @throws Exception if the storage path has not been set yet
     */
    public function convertStoreImage(string $data, string $slug): void {
        $gdImage = imagecreatefromstring($data);

        // Output needs to be buffered and read because this is how
        // imagewebp() works.
        ob_start();
        \imagewebp($gdImage, null, 100);
        $webp = ob_get_clean();

        $this->filesystem->dumpFile($this->getImagePath($slug), $webp);
    }

    public function fetchImage(Image $image): void {
        try {
            $response = $this->httpClient->request('GET', $image->getUpstreamUrl());
            // getStatusCode() throws on network error, so
            $code = $response->getStatusCode();
        } catch(TransportException $e) {
            throw Exception\ProxyFetchException::forSlug(
                $image->getSlug(), $e->getCode()
            );
        }

        switch ($code) {
            case 200:
                break;
            default:
                throw Exception\ProxyFetchException::forSlug(
                    $image->getSlug(), $response->getStatusCode()
                );
        }

        $imageData = $response->getContent();

        try {
            $this->convertStoreImage($imageData, $image->getSlug());
        } catch(IOException) {
            throw Exception\ImageConversionException("Unable to convert and/or store image!");
        } catch(Exception) {
            throw Exception\ImageConversionException(
                "Guardrails hit. Most likely localImagePath is missing!"
            );
        }
    }

    /**
     * Get the image file path for a given slug.
     *
     * @throws ProxyExceptionInterface
     */
    public function getProxyFilePath(string $slug): string {
        $image = $this->imageRepository->findOneBy(['slug' => $slug]);

        if (!$image) {
            throw Exception\ImageNotFoundException::forSlug($slug);
        }

        $cachedAt = $image->getCachedAt();
        $interval = new \DateInterval("PT{$this->imageCacheTTL}S");
        $path = $this->getImagePath($image->getSlug());

        $isExpired = true;
        if ($cachedAt !== null) {
            $cacheToL = $cachedAt->add($interval);
            $isExpired = $cacheToL <= new \DateTimeImmutable();
        }

        if ( ($isExpired) or (!$this->filesystem->exists($path))) {
            $this->fetchImage($image);
            $image->setCachedAt(new \DateTimeImmutable());
            $this->entityManager->flush();
        }

        // Path should be safe to send now. It was either:
        // Present, with valid cache
        // Not present, and refetched
        // Present and MISS, then refetched
        // An exception has been raised during the process.
        return $this->getImagePath($image->getSlug());
    }
}
