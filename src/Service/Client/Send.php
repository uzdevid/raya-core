<?php declare(strict_types=1);

namespace App\Service\Client;

use JsonException;
use Workerman\Connection\TcpConnection;
use Yiisoft\Json\Json;

readonly class Send {
    private function __construct(
        private TcpConnection $connection
    ) {
    }

    /**
     * @param TcpConnection $connection
     * @return static
     */
    public static function to(TcpConnection $connection): static {
        return new static($connection);
    }

    /**
     * @param string $method
     * @param array $data
     * @return bool|null
     */
    public function message(string $method, array $data = []): bool|null {
        try {
            $payload = Json::encode([
                'method' => $method,
                'payload' => $data
            ]);
        } catch (JsonException $e) {
            return null;
        }

        return $this->connection->send($payload);
    }
}
