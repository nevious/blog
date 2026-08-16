<?php

namespace App\Exception;

/**
 * Common Interface for exception handling in the proxy
 */
interface ProxyExceptionInterface extends \Throwable {
    /**
     * The HTTP status code to return for this error
     */
    public function getStatusCode(): int;
}
