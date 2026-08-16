<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Image;
use App\DTO\ImageResponseDTO;
use App\DTO\ImageCreateRequestDTO;
use App\Repository\ImageRepository;
use App\Service\ImageProxy;

final class ImageApiController extends AbstractController
{
    public function __construct(
        private ImageRepository $imageRepository,
        private ImageProxy $imageProxy,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager
    ) {}

    #[OA\Tag(name: 'Management')]
    public function index(): JsonResponse {
        $collection = $this->imageRepository->findAll();
        $data = array_map(
            fn(Image $image) => ImageResponseDTO::fromEntity($image),
            $collection
        );
        return $this->json($data);
    }

    /*
     * This is the automatic "magic" Symfony way using MapRequestPayload:
     *
     * public function create(#[MapRequestPayload] ImageCreateRequestDTO $dto): JsonResponse {
     *     return $this->json(["message" => "pending"]);
     * }
     *
     * It does the same thing as the function blelow, but autowires it. I'm not sure if the validator is
     * executed as well.
     *
     */

    #[OA\Post(
        summary: 'Register a new image asset',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: ImageCreateRequestDTO::class))),
        responses: [
            new OA\Response(response: 201, description: 'Success', content: new OA\JsonContent(ref: new Model(type: ImageResponseDTO::class))),
            new OA\Response(response: 400, description: 'Validation Error')
        ]
    )]
    #[OA\Tag(name: 'Management')]
    public function create(
        Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse {
        // serialize JSON into DTO
        $dto = $serializer->deserialize(
            $request->getContent(), ImageCreateRequestDTO::class, 'json'
        );

        // Validate
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        // Create Object
        $image = new Image();
        $image->setSlug($dto->slug);
        $image->setUpstreamUrl($dto->upstreamUrl);
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $this->json(ImageResponseDTO::fromEntity($image), 201);
    }

    #[OA\Tag(name: 'Management')]
    public function delete(string $slug) {
        $image = $this->imageRepository->findOneBy(['slug' => $slug]);

        if (!$image) {
            return $this->json(['message' => 'image not found'], 404);
        }

        $this->entityManager->remove($image);
        //This is not gonna be transaction safe!
        $this->imageProxy->deleteFile($image->getSlug());
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
