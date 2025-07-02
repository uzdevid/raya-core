<?php

namespace App\Application\Http\Exception;

use RuntimeException;

class ServerErrorHttpException extends RuntimeException implements HttpExceptionInterface {
    /**
     * @return int
     */
    public function getStatusCode(): int {
        return 500;
    }
}
