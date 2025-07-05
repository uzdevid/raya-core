<?php

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Storage\Client;

interface OnConnectInterface {
    /**
     * @param Client $client
     * @return void
     */
    public function handle(Client $client): void;
}
