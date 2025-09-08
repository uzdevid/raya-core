<?php declare(strict_types=1);

namespace App\Application\Websocket;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\Event\OnCloseInterface;
use App\Application\Websocket\Event\OnConnectInterface;
use App\Application\Websocket\Event\OnErrorInterface;
use App\Application\Websocket\Event\OnMessageInterface;
use App\Application\Websocket\Event\OnWorkerExitInterface;
use App\Application\Websocket\Storage\Client;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Service\Auth\AuthServiceInterface;
use App\Service\Client\Send;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Json\Json;

readonly class Application implements ApplicationInterface {
    /**
     * @param ContainerInterface $container
     * @param HydratorInterface $hydrator
     * @param LoggerInterface $logger
     * @param ClientCollectionInterface $collection
     * @param AuthServiceInterface $authService
     */
    public function __construct(
        private ContainerInterface        $container,
        private HydratorInterface         $hydrator,
        private LoggerInterface           $logger,
        private ClientCollectionInterface $collection,
        private AuthServiceInterface      $authService
    ) {
    }

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void {
        $container = $this->container;
        $collection = $this->collection;
        $authService = $this->authService;

        $tcpConnection->onWebSocketConnected = static function (TcpConnection $tcpConnection, Request $request) use ($authService, $container, $collection) {
            try {
                $authorized = $authService->verify($request->header('Authorization', ''));
            } catch (UnauthorizedException) {
                $tcpConnection->close(['status' => 401, 'message' => 'Unauthorized']);
                return;
            }

            $client = new Client(
                $request->header('X-Client-Id'),
                $request->header('X-Client-Version'),
                $request->header('X-Client-Platform'),
                $request->header('X-Client-Language'),
                $authorized->id,
                $tcpConnection
            );

            $collection->add($client);
            $container->get(OnConnectInterface::class)->handle($client);
        };
    }

    /**
     * @param TcpConnection $tcpConnection
     * @param string $payload
     */
    public function onMessage(TcpConnection $tcpConnection, string $payload): void {
        try {
            $payloadArray = Json::decode($payload);
        } catch (JsonException $e) {
            Send::to($tcpConnection)->message('error', ['message' => 'Invalid JSON payload.']);
            return;
        }

        if (!isset($payloadArray['method'])) {
            Send::to($tcpConnection)->message('error', ['message' => 'Invalid payload format. "method" key is required.']);
            return;
        }

        try {
            $client = $this->collection->get($tcpConnection->id);
        } catch (NotFoundException $e) {
            $tcpConnection->close(['status' => 404, 'message' => 'Client not found']);
            return;
        }

        try {
            $this->container->get(OnMessageInterface::class)->handle($client, $this->hydrator->create(Message::class, $payloadArray));
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), [
                'payload' => $payloadArray,
                'exception' => $exception,
            ]);
        }
    }

    /**
     * @param TcpConnection $tcpConnection
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onClose(TcpConnection $tcpConnection): void {
        try {
            $client = $this->collection->get($tcpConnection->id);
        } catch (NotFoundException $e) {
            print_r("Client not found on close: " . $tcpConnection->id . "\n");
            return;
        }
        $this->collection->remove($client->id);
        $this->container->get(OnCloseInterface::class)->handle($client);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onError(TcpConnection $tcpConnection, int $code, string $message): void {
        $this->container->get(OnErrorInterface::class)->handle($tcpConnection, $code, $message);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onWorkerExit(TcpConnection $tcpConnection): void {
        $this->container->get(OnWorkerExitInterface::class)->handle($tcpConnection);
    }
}
