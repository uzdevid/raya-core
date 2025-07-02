<?php

namespace App\Application\Http\Exception;

use RuntimeException;

final class BadRequestHttpException extends RuntimeException implements HttpExceptionInterface {
    /**
     * @return int
     */
    public function getStatusCode(): int {
        return 400;
    }
}
