<?php

namespace App\Application\Websocket\Storage;

use App\Exception\NotFoundException;

interface ClientCollectionInterface {
    /**
     * @param Client $client
     * @return void
     */
    public function add(Client $client): void;

    /**
     * @param string|int $id
     * @return Client
     * @throws NotFoundException
     */
    public function get(string|int $id): Client;

    /**
     * @param string $device
     * @return Client
     * @throws NotFoundException
     */
    public function getByDevice(string $device): Client;

    /**
     * @return Client[]
     */
    public function list(): array;

    /**
     * @param string|int $id
     * @return void
     */
    public function remove(string|int $id): void;
}
