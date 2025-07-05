<?php declare(strict_types=1);

namespace App\Service\Auth;

readonly class Authorized {
    public function __construct(
        public string $id
    ) {
    }
}
