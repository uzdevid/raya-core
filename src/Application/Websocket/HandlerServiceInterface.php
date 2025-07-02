<?php

namespace App\Application\Websocket;

use App\Application\Websocket\Dto\Message;
use Workerman\Connection\TcpConnection;

interface HandlerServiceInterface {
    /**
     * @param TcpConnection $tcpConnection
     * @param Message $payload
     * @return void
     */
    public function handle(TcpConnection $tcpConnection, Message $payload): void;
}
