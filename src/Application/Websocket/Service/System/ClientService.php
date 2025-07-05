<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\System;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Application\Websocket\Storage\Client;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Service\Client\Send;

readonly class ClientService implements HandlerServiceInterface {
    public function __construct(
        private ClientCollectionInterface $clientCollection
    ) {
    }

    public function handle(Client $client, Message $payload): void {
        $clients = array_map(static fn(Client $client) => [
            'id' => $client->id,
            'identityId' => $client->identityId,
            'connectionId' => $client->connection->id,
        ], $this->clientCollection->list());

        Send::to($client->connection)->message('respond:system:client:list', $clients);
    }
}
