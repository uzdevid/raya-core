<?php

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Model\Assistant;

interface AssistantRepositoryInterface {
    /**
     * @param string $ownerId
     * @return Assistant
     * @throws NotFoundException
     */
    public function getByOwnerId(string $ownerId): Assistant;

    /**
     * @param string $ownerId
     * @return bool
     */
    public function existsByOwnerId(string $ownerId): bool;
}
