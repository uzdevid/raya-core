<?php

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Storage\Client;

interface OnCloseInterface {
    /**
     * @param Client $client
     * @return void
     */
    public function handle(Client $client): void;
}
