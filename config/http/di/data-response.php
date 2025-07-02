<?php

declare(strict_types=1);

use App\Application\Http\Factory\ResponseFactory;
use App\Application\Http\ResponseFormatter;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\DataResponseFormatterInterface;
use Yiisoft\DataResponse\Formatter\HtmlDataResponseFormatter;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;
use Yiisoft\DataResponse\Formatter\XmlDataResponseFormatter;
use Yiisoft\DataResponse\Middleware\ContentNegotiator;

/* @var $params array */

return [
    DataResponseFormatterInterface::class => ResponseFormatter::class,
    DataResponseFactoryInterface::class => ResponseFactory::class,
    //
    ContentNegotiator::class => [
        '__construct()' => [
            'contentFormatters' => [
                'text/html' => new HtmlDataResponseFormatter(),
                'application/xml' => new XmlDataResponseFormatter(),
                'application/json' => new JsonDataResponseFormatter(),
            ],
        ],
    ],
];
