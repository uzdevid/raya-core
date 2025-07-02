<?php
declare(strict_types=1);

namespace App\Application\Http\Provider;

interface TracerProviderInterface {
    /**
     * @param string $id
     * @return TracerProviderInterface
     */
    public function withId(string $id): TracerProviderInterface;

    /**
     * @return string
     */
    public function getId(): string;
}
