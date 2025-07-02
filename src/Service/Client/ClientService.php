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
     * @param string $device
     * @return Send
     * @throws NotFoundException
     */
    public function send(string $device): Send {
        return Send::to($this->clientCollection->getByDevice($device)->connection);
    }

    /**
     * @param string $device
     * @param string $code
     * @throws NotFoundException
     */
    public function sendCode(string $device, string $code): void {
        $this->send($device)->message('execute:client', ['code' => $code]);
    }
}
