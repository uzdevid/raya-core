<?php

declare(strict_types=1);

use App\Application\Http\Middleware\ExceptionMiddleware;
use Yiisoft\ErrorHandler\Middleware\ErrorCatcher;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Yii\Middleware\Subfolder;

return [
    'yiisoft/input-http' => [
        'requestInputParametersResolver' => [
            'throwInputValidationException' => true,
        ],
    ],

    'middlewares' => [
        ErrorCatcher::class,
        Subfolder::class,
        Router::class,
        ExceptionMiddleware::class
    ],
];
