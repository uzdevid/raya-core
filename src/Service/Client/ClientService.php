<?php declare(strict_types=1);

namespace App\Service\Client;

use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Exception\NotFoundException;

readonly class ClientService {
    public function __construct(
        private ClientCollectionInterface $clientCollection
    ) {
    }

    /**
     * @param string $clientId
     * @return Send
     * @throws NotFoundException
     */
    public function send(string $clientId): Send {
        return Send::to($this->clientCollection->get($clientId)->connection);
    }

    /**
     * @param string $clientId
     * @param string $code
     * @throws NotFoundException
     */
    public function sendCode(string $clientId, string $code): void {
        $this->send($clientId)->message('execute:client', ['code' => $code]);
    }
}
