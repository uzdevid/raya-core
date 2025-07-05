<?php

namespace App\Application\Websocket;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\Storage\Client;

interface HandlerServiceInterface {
    /**
     * @param Client $client
     * @param Message $payload
     * @return void
     */
    public function handle(Client $client, Message $payload): void;
}
