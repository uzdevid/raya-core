<?php declare(strict_types=1);

namespace App\Application\Websocket\Storage;

use Workerman\Connection\TcpConnection;

class Client {
    /**
     * @param string $id
     * @param string $device
     * @param TcpConnection $connection
     */
    public function __construct(
        public string        $id,
        public string        $device,
        public TcpConnection $connection
    ) {
    }
}
