<?php

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Exception\ServerErrorException;
use App\Model\Client;

interface ClientRepositoryInterface {
    /**
     * @param string $assistantId
     * @param string $platform
     * @return Client
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function getByAssistantIdAndPlatform(string $assistantId, string $platform): Client;

    /**
     * @param string $assistantId
     * @return Client[]
     * @throws ServerErrorException
     */
    public function list(string $assistantId): array;

    /**
     * @param string $id
     * @param bool $isOnline
     * @return void
     */
    public function updateOnline(string $id, bool $isOnline): void;
}
