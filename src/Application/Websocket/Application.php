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
     */
    public function __construct(
        private ContainerInterface        $container,
        private HydratorInterface         $hydrator,
        private LoggerInterface           $logger,
        private ClientCollectionInterface $collection
    ) {
    }

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void {
        $container = $this->container;
        $collection = $this->collection;

        $tcpConnection->onWebSocketConnected = static function (TcpConnection $tcpConnection, Request $request) use ($container, $collection) {
            $collection->add(new Client(uniqid('', true), $request->header('X-Device'), $tcpConnection));
            $container->get(OnConnectInterface::class)->handle($tcpConnection);
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
            $this->container->get(OnMessageInterface::class)->handle($tcpConnection, $this->hydrator->create(Message::class, $payloadArray));
        } catch (Throwable $exception) {
            print $exception->getMessage() . "\n";
            $this->logger->error($exception->getMessage(), [
                'payload' => $payloadArray,
                'exception' => $exception,
            ]);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function onClose(TcpConnection $tcpConnection): void {
        $this->collection->remove($tcpConnection->id);
        $this->container->get(OnCloseInterface::class)->handle($tcpConnection);
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
