<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

class OnError implements OnErrorInterface {

    public function handle(TcpConnection $connection, int $code, string $message): void {
        // TODO: Implement handle() method.
    }
}
