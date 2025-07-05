<?php

namespace App\Service\Brain;

use App\Application\Websocket\Storage\Client;

interface BrainInterface {
    public function init(Client $client): void;

    /**
     * @param Client $client
     * @param string $query
     * @return string
     */
    public function reflection(Client $client, string $query): string;
}
