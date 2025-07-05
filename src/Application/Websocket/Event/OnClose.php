<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Storage\Client;
use App\Repository\ClientRepositoryInterface;

readonly class OnClose implements OnCloseInterface {

    public function __construct(
        private ClientRepositoryInterface $clientRepository
    ) {
    }

    public function handle(Client $client): void {
        $this->clientRepository->updateOnline($client->id, false);
    }
}
