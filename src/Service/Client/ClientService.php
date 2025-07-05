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
     * @param string $identity
     * @return Send
     * @throws NotFoundException
     */
    public function send(string $identity): Send {
        return Send::to($this->clientCollection->getByIdentity($identity)->connection);
    }

    /**
     * @param string $identity
     * @param string $code
     * @throws NotFoundException
     */
    public function sendCode(string $identity, string $code): void {
        $this->send($identity)->message('execute:client', ['code' => $code]);
    }
}
