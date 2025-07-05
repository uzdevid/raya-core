<?php declare(strict_types=1);

namespace App\Event;

use App\Application\Websocket\Storage\Client;

readonly final class NewClient {
    /**
     * @param Client $client
     */
    public function __construct(
        public Client $client
    ) {
    }
}
