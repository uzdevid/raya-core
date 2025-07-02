<?php

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

interface OnErrorInterface {
    /**
     * @param TcpConnection $connection
     * @param int $code
     * @param string $message
     * @return void
     */
    public function handle(TcpConnection $connection, int $code, string $message): void;
}
