<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Application\Websocket\Storage\Client;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Yiisoft\Json\Json;

readonly class OnMessage implements OnMessageInterface {
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * @param Client $client
     * @param Message $payload
     * @throws ContainerExceptionInterface
     * @throws JsonException
     */
    public function handle(Client $client, Message $payload): void {
        try {
            $handlerService = $this->container->get($payload->method);
        } catch (NotFoundExceptionInterface $e) {
            $client->connection->close(Json::encode(['error' => 'Not found']));
            return;
        }

        if (!$handlerService instanceof HandlerServiceInterface) {
            $client->connection->close(Json::encode(['error' => 'Invalid implementation']));
            return;
        }

        $handlerService->handle($client, $payload);
    }
}
