<?php

namespace App\Service\Auth;

use App\Exception\UnauthorizedException;

interface AuthServiceInterface {
    /**
     * @param string $token
     * @return Authorized
     * @throws UnauthorizedException
     */
    public function verify(string $token): Authorized;
}
