<?php

namespace App\Application\Http\Dto;

use Yiisoft\Arrays\ArrayableTrait;

class FailResponseData implements CustomResponseInterface {
    use ArrayableTrait;

    public bool $success = false;
    public array|null $payload;

    /**
     * @param string|null $message
     * @param int $code
     * @param array $params
     */
    public function __construct(
        string|null $message,
        int         $code,
        array       $params = [],
    ) {
        $this->payload = array_merge([
            'message' => $message,
            'code' => $code
        ], $params);
    }
}
