<?php

namespace App\Application\Websocket;

use Workerman\Connection\TcpConnection;

interface ApplicationInterface {
    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void;

    /**
     * @param TcpConnection $tcpConnection
     * @param string $payload
     * @return void
     */
    public function onMessage(TcpConnection $tcpConnection, string $payload): void;

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onClose(TcpConnection $tcpConnection): void;

    /**
     * @param TcpConnection $tcpConnection
     * @param int $code
     * @param string $message
     * @return void
     */
    public function onError(TcpConnection $tcpConnection, int $code, string $message): void;

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onWorkerExit(TcpConnection $tcpConnection): void;
}
