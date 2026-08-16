<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Repository\ImageRepository;
use App\Service\ImageProxy;
use App\Exception;
use OpenApi\Attributes as OA;

final class ImageStoreController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private ImageProxy $imageProxy,
    ) {}

    #[OA\Tag(name: 'Public Proxy')]
    public function fetch(string $slug): Response {
        try {
            $path = $this->imageProxy->getProxyFilePath($slug);
            $response = new BinaryFileResponse($path);
            $response->headers->set('Content-Type', 'image/webp');

            return $response;
        } catch (Exception\ImageNotFoundException) {
            return $this->json([
                'message' => "image not found"
            ], 404);
        } catch (Exception\ProxyFetchException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }

        throw new BadRequestHttpException("Unhandled request");
    }
}
