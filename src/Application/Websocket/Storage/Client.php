<?php declare(strict_types=1);

namespace App\Application\Websocket\Storage;

use Workerman\Connection\TcpConnection;

class Client {
    private array $storage;

    /**
     * @param string $id
     * @param string $version
     * @param string $platform
     * @param string $language
     * @param string $identityId
     * @param TcpConnection $connection
     */
    public function __construct(
        public string        $id,
        public string $version,
        public string $platform,
        public string $language,
        public string $identityId,
        public TcpConnection $connection
    ) {
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function add(string $key, mixed $value): void {
        $this->storage[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     *
     * @psalm-template T
     * @psalm-param string|class-string<T> $key
     * @psalm-return ($key is class-string ? T : mixed)
     */
    public function get(string $key, mixed $default = null): mixed {
        return $this->storage[$key] ?? $default;
    }
}
