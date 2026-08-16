<?php

namespace App\Exception;

class ProxyFetchException extends \RuntimeException implements ProxyExceptionInterface{
    public function getStatusCode(): int { return 502; }

    public static function forSlug(string $slug, int $upstreamCode): self {
        return new self("Upstream responded with '$upstreamCode' for resource '$slug'");
    }
}
