<?php

namespace App\Application\Http\Dto;

use Yiisoft\Input\Http\InputValidationException;
use function array_keys;
use function array_map;

class InputValidationFailResponseData extends FailResponseData {
    /**
     * @param InputValidationException $exception
     */
    public function __construct(
        InputValidationException $exception
    ) {
        $errors = $exception->getResult()->getFirstErrorMessagesIndexedByPath();

        $errors = array_map(
            static fn($attribute, $message) => ['attribute' => $attribute, 'message' => $message],
            array_keys($errors), $errors
        );

        parent::__construct($exception->getMessage(), $exception->getCode(), ['errors' => $errors]);
    }
}
