<?php

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

interface OnWorkerExitInterface {
    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function handle(TcpConnection $connection): void;
}
