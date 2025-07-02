<?php

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Dto\Message;
use Workerman\Connection\TcpConnection;

interface OnMessageInterface {
    /**
     * @param TcpConnection $connection
     * @param Message $payload
     * @return void
     */
    public function handle(TcpConnection $connection, Message $payload): void;
}
