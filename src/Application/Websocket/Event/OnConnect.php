<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use Workerman\Connection\TcpConnection;

class OnConnect implements OnConnectInterface {

    public function handle(TcpConnection $connection): void {
       
    }
}
