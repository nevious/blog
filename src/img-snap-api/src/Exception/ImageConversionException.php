<?php

namespace App\Exception;

class ImageConversionException extends \RuntimeException implements ProxyExceptionInterface {
    public function getStatusCode(): int { return 500; }
}
