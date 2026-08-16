<?php

namespace App\DTO;

use App\Validator\UniqueSlug;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

/**
 * @disregard P1001
 */
readonly class ImageCreateRequestDTO {
    public function __construct(
        #[OA\Property(description: 'A unique identifier for the image', example: 'hero-banner')]
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[UniqueSlug]
        public ?string $slug,

        #[OA\Property(description: 'The source URL from Nextcloud', example: 'https://nextcloud.example.com/s/abcdef')]
        #[Assert\NotBlank]
        #[Assert\Url(requireTld: true)]
        public ?string $upstreamUrl
    ) {}
}
