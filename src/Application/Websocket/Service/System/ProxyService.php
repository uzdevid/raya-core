<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\System;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Exception\NotFoundException;
use App\Service\Client\Send;
use Workerman\Connection\TcpConnection;

readonly class ProxyService implements HandlerServiceInterface {
    public function __construct(
        private ClientCollectionInterface $clientCollection
    ) {
    }

    public function handle(TcpConnection $tcpConnection, Message $payload): void {
        try {
            $targetClient = $this->clientCollection->get($payload->payload['client']);
        } catch (NotFoundException $e) {
            Send::to($tcpConnection)->message('error', ['message' => 'Client not found']);
            return;
        }

        Send::to($targetClient->connection)->message($payload->payload['proxy.method'], $payload->payload['proxy.payload']);
    }
}
