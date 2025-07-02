<?php

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

interface OnCloseInterface {
    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function handle(TcpConnection $connection): void;
}
