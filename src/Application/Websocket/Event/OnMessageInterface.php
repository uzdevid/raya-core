<?php

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\Storage\Client;

interface OnMessageInterface {
    /**
     * @param Client $client
     * @param Message $payload
     * @return void
     */
    public function handle(Client $client, Message $payload): void;
}
