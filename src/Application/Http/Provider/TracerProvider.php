<?php
declare(strict_types=1);

namespace App\Application\Http\Provider;

class TracerProvider implements TracerProviderInterface {
    private string $id;

    /**
     * @param string $id
     * @return TracerProviderInterface
     */
    public function withId(string $id): TracerProviderInterface {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }
}
