<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Workerman\Connection\TcpConnection;
use Yiisoft\Json\Json;

readonly class OnMessage implements OnMessageInterface {
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * @param TcpConnection $connection
     * @param Message $payload
     * @throws ContainerExceptionInterface
     * @throws JsonException
     */
    public function handle(TcpConnection $connection, Message $payload): void {
        try {
            $handlerService = $this->container->get($payload->method);
        } catch (NotFoundExceptionInterface $e) {
            $connection->close(Json::encode(['error' => 'Not found']));
            return;
        }

        if (!$handlerService instanceof HandlerServiceInterface) {
            $connection->close(Json::encode(['error' => 'Invalid implementation']));
            return;
        }

        $handlerService->handle($connection, $payload);
    }
}
