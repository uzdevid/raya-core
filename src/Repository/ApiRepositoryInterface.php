<?php

namespace App\Repository;

use App\Exception\ServerErrorException;

interface ApiRepositoryInterface {
    /**
     * @param string $clientId
     * @return void
     * @throws ServerErrorException
     */
    public function deleteByClientId(string $clientId): void;
}
