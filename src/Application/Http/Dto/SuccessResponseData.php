<?php

namespace App\Application\Http\Dto;

use Yiisoft\Arrays\ArrayableTrait;

class SuccessResponseData implements CustomResponseInterface {
    use ArrayableTrait;

    public bool $success = true;

    /**
     * @param array|null $payload
     */
    public function __construct(
        public readonly array|null $payload = null
    ) {
    }
}
