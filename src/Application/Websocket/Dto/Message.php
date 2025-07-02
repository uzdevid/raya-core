<?php declare(strict_types=1);

namespace App\Application\Websocket\Dto;

readonly class Message {
    /**
     * @param string $method
     * @param string $requestId
     * @param array|string|null $payload
     */
    public function __construct(
        public string            $method,
        public string            $requestId,
        public array|string|null $payload
    ) {
    }
}
