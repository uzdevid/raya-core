<?php declare(strict_types=1);

namespace App\Application\Websocket\Storage;

use App\Exception\NotFoundException;

class ClientCollection implements ClientCollectionInterface {
    /**
     * @var array<string, Client>
     */
    private array $indexedById = [];
    /**
     * @var array<int, Client>
     */
    private array $indexedByConnectionId = [];

    private array $indexedByDevice = [];

    public function add(Client $client): void {
        $this->indexedById[$client->id] = $client;
        $this->indexedByConnectionId[$client->connection->id] = $client;
        $this->indexedByDevice[$client->device] = $client;
    }

    /**
     * @throws NotFoundException
     */
    public function get(string|int $id): Client {
        if (is_int($id)) {
            $list = $this->indexedByConnectionId;
        } else {
            $list = $this->indexedById;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        throw new NotFoundException('Client not found: ' . $id);
    }

    /**
     * @throws NotFoundException
     */
    public function getByDevice(string $device): Client {
        if (isset($this->indexedByDevice[$device])) {
            return $this->indexedByDevice[$device];
        }

        throw new NotFoundException('Client not found for device: ' . $device);
    }

    public function list(): array {
        return $this->indexedById;
    }

    public function remove(string|int $id): void {
        if (is_int($id)) {
            $client = $this->indexedByConnectionId[$id];
            unset($this->indexedById[$client->id], $this->indexedByConnectionId[$id]);
        } else {
            $client = $this->indexedById[$id];
            unset($this->indexedById[$id], $this->indexedByConnectionId[$client->connection->id]);
        }
    }
}
