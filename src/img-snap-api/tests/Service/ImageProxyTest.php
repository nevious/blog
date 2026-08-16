<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Filesystem\Filesystem;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

use App\Entity\Image;
use App\Service\ImageProxy;
use App\Repository\ImageRepository;
use App\Exception;

use PHPUnit\Framework\Attributes\DataProvider;

class ImageProxyTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ImageRepository $imageRepository;
    private ImageProxy $proxy;
    private int $configuredCacheTTL;
    private string $configuredCacheDir;
    private string $rawTestImage;

    public function setUp(): void {
        // Boot up and config read
        self::bootKernel();
        $this->proxy = static::getContainer()->get(ImageProxy::class);
        $this->configuredCacheTTL = static::getContainer()->getParameter('app.cache_life_time');
        $this->configuredCacheDir = static::getContainer()->getParameter('app.image_cache_dir');

        // Internals, DB creation
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->imageRepository = $this->entityManager->getRepository(Image::class);
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        // Testing data
        // @TODO: Use DataFixture\Base64ImageFixture
        $s = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
        $this->rawTestImage = base64_decode($s);
    }

    public static function httpErrorCodes() {
        return [
            "Internal Server Error" => [500],
            "Bad Gateway" => [502],
            "Service unavailable" => [503],
            "Not found" => [404]
        ];
    }

    private function getMockResponse(mixed $data, int $return_code, array $response_headers): MockResponse {
        $mockResponse = new MockResponse($data, [
            'http_code' => $return_code,
            'response_headers' => $response_headers
        ]);

        return $mockResponse;
    }

    private function assembleNewProxy(HttpClientInterface $client): ImageProxy {
        $proxy = new ImageProxy(
            $this->configuredCacheDir,
            $this->configuredCacheTTL,
            $client,
            $this->entityManager->getRepository(Image::class),
            $this->entityManager
        );

        return $proxy;
    }

    private function createTestImageModel(string $slug, string $upstream, \DateTimeImmutable $cachedAt) {
        $image = new Image();
        $image->setSlug($slug);
        $image->setUpstreamUrl($upstream);
        $image->setCachedAt($cachedAt);

        $this->entityManager->persist($image);
        $this->entityManager->flush();
    }

    public function testServiceConfig() {
        $cacheDir = $this->proxy->getCacheDir();
        $cacheTTL = $this->proxy->getCacheTTL();

        $this->assertNotNull($cacheDir);
        $this->assertSame($this->configuredCacheDir, $cacheDir);

        $this->assertNotNull($cacheTTL);
        $this->assertSame($this->configuredCacheTTL, $cacheTTL);
    }

    /**
     * Image was found in image cache dir and not fetched from upstream
     *
     */
    public function testCacheHit() {
        // 1. Setup the mocks
        $mockClient = $this->createMock(HttpClientInterface::class);
        $mockClient->expects($this->never())->method('request');

        // 2. Create a new proxy service that contains above mocks
        $proxy = $this->assembleNewProxy($mockClient);

        // 3. Setup the DB and FS
        $slug = 'test-image';
        $upstream = 'https://nextcloud.local/testTinyPicture.png';
        $cachedAt = new \DateTimeImmutable();
        $this->createTestImageModel($slug, $upstream, $cachedAt);

        $fs = new Filesystem;
        $fs->touch($proxy->getImagePath($slug));

        // 4. Act on the test
        $path = $proxy->getProxyFilePath($slug);
        $imageModel = $this->imageRepository->findOneBy(['slug' => $slug]);

        $this->assertNotNull($imageModel);
        $this->assertSame($cachedAt, $imageModel->getCachedAt());
        $this->assertNotNull($path);
    }

    /*
     * Image is not found locally and re-fetched from upstream
     */
    public function testCacheMissSuccess() {
        // 1. Setup the mocks
        $image = $this->rawTestImage;
        $mockResponse = $this->getMockResponse($image, 200, ['Content-Type' => 'image/png']);
        $mockClient = new MockHttpClient($mockResponse);

        // 2. Create a new proxy service that contains above mocks
        $proxy = $this->assembleNewProxy($mockClient);

        // 3. Setup the DB state
        $slug = 'testTinyPicture';
        $upstream = 'https://nextcloud.local/testTinyPicture.png';
        $cachedAt = new \DateTimeImmutable("2000-01-01");
        $this->createTestImageModel($slug, $upstream, $cachedAt);

        // 4. Act on the test
        $filepath = $proxy->getProxyFilePath($slug);
        $updatedModel = $this->imageRepository->findOneBy(['slug' => $slug]);

        // 5. Assertions
        $this->assertNotNull($filepath);
        $this->assertIsString($filepath) ;
        $this->assertFileExists($filepath);

        $this->assertNotNull($updatedModel);
        $this->assertGreaterThan($cachedAt, $updatedModel->getCachedAt());
    }

    /**
     * Image is not found in ImageProxy Service
     */
     public function testImageNotFound() {
        $slug = 'non-existent-slug';
        $this->expectException(Exception\ImageNotFoundException::class);
        $this->proxy->getProxyFilePath($slug);
    }

    /**
    * Image is not found locally, fetch from upstream
    */
    #[DataProvider('httpErrorCodes')]
    public function testUpstreamFailure(int $code) {
        $mockRsponse = $this->getMockResponse('', $code, []);  // Don't need the pixels ..
        $mockClient = new MockHttpClient($mockRsponse);
        $proxy = $this->assembleNewProxy($mockClient);
        // ... but we need the model!
        $this->createTestImageModel("test-slug", "https://test.local/someImage.png", new \DateTimeImmutable());

        $this->expectException(Exception\ProxyFetchException::class);
        $proxy->getProxyFilePath("test-slug");
    }

    /**
     * Test failure states on network errors
     */
    public function testNetworkError() {
        $this->createTestImageModel("test-slug", "https://test.local/someImage.png", new \DateTimeImmutable());
        $mockClient = new MockHttpClient([
            // This essentially is hooked in MockResponse.
            // If the info object has an error key, it'll cause a TransportError
            // DNS lookup failure _should_ also throw a TransportError
            new Mockresponse(info: ['error' => 'host unreachable', 'http_code', 23]),
        ]);

        $proxy = $this->assembleNewProxy($mockClient);
        $this->expectException(Exception\ProxyFetchException::class);
        $proxy->getProxyFilePath('test-slug');
    }

    public function testResolveError() {
        $this->createTestImageModel("test-slug", "https://test.local/someImage.png", new \DateTimeImmutable());
        $this->expectException(Exception\ProxyFetchException::class);
        $this->proxy->getProxyFilePath('test-slug');
    }

    /**
     * image is found locally but it is expired and refetched
     */
    public function testCacheExpired() {
        $this->markTestSkipped("Already implemented in testCacheMissSuccess");
    }

    public function testUpdate_CachedAt_() {
        $this->markTestSkipped("This is tested in testCacheMissSuccess");
    }

    public function tearDown(): void {
        parent::tearDown();
        $fs = new Filesystem();
        $fs->remove($this->configuredCacheDir);
        $this->entityManager->close();
    }
}
