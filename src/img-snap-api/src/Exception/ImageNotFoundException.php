<?php

namespace App\Exception;

class ImageNotFoundException extends \RuntimeException implements ProxyExceptionInterface {
    public function getStatusCode(): int { return 404; }

    public static function forSlug(string $slug): self {
        return new self("Image for slug '$slug' not found");
    }
}
