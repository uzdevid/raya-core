<?php

namespace App\Application\Http\Exception;

use RuntimeException;

class TooManyRequestsHttpException extends RuntimeException implements HttpExceptionInterface {
    /**
     * @return int
     */
    public function getStatusCode(): int {
        return 429;
    }
}
