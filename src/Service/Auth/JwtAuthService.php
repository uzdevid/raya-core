<?php declare(strict_types=1);

namespace App\Service\Auth;

use App\Exception\UnauthorizedException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

readonly final class JwtAuthService implements AuthServiceInterface {
    /**
     * @param string $key
     * @param string $algorithm
     */
    public function __construct(
        private string $key,
        private string $algorithm = 'HS256'
    ) {
    }

    public function verify(string $token): Authorized {
        if (empty($token)) {
            throw new UnauthorizedException('Token cannot be empty');
        }

        try {
            $data = JWT::decode($token, new Key($this->key, $this->algorithm));
        } catch (Throwable $exception) {
            throw new UnauthorizedException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new Authorized(id: $data->sub);
    }
}
