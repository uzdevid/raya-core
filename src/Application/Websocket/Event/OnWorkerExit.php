<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

class OnWorkerExit implements OnWorkerExitInterface {

    public function handle(TcpConnection $connection): void {
        // TODO: Implement handle() method.
    }
}
