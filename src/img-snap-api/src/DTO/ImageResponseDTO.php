<?php

namespace App\DTO;

use App\Entity\Image;
use OpenApi\Attributes as OA;

readonly class ImageResponseDTO {

    public function __construct(
            #[OA\Property(description: 'The internal database ID')]
            public int $id,
            #[OA\Property(description: 'The unique slug')]
            public string $slug,
            #[OA\Property(description: 'The original upstream URL')]
            public string $upstreamUrl,
    ) {}

    public static function fromEntity(Image $image): self {
        return new self($image->getId(), $image->getSlug(), $image->getUpstreamUrl());
    }
}
