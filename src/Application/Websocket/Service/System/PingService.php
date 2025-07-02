<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\System;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use JsonException;
use Workerman\Connection\TcpConnection;
use Yiisoft\Json\Json;

class PingService implements HandlerServiceInterface {
    /**
     * @throws JsonException
     */
    public function handle(TcpConnection $tcpConnection, Message $payload): void {
        $tcpConnection->send(Json::encode(['method' => 'system:ping', 'data' => ['pong' => true]]));
    }
}
