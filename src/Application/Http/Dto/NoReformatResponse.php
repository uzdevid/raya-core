<?php
declare(strict_types=1);

namespace App\Application\Http\Dto;

class NoReformatResponse implements CustomResponseInterface {
    /**
     * @param mixed $payload
     */
    public function __construct(
        public array $payload
    ) {
    }

    /**
     * @return array
     */
    public function fields(): array {
        return [];
    }

    /**
     * @return array
     */
    public function extraFields(): array {
        return [];
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], bool $recursive = true): array {
        return $this->payload;
    }
}
