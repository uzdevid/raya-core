<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\System;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Application\Websocket\Storage\Client;
use JsonException;
use Yiisoft\Json\Json;

class PingService implements HandlerServiceInterface {
    /**
     * @throws JsonException
     */
    public function handle(Client $client, Message $payload): void {
        $client->connection->send(Json::encode(['method' => 'system:ping', 'data' => ['pong' => true]]));
    }
}
