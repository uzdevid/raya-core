<?php

declare(strict_types=1);

return [
    'config-plugin' => [
        'params' => 'common/params.php',
        'params-web' => [
            '$params',
            'http/params.php',
        ],
        'params-websocket' => [
            '$params',
            'websocket/params.php',
        ],
        'params-console' => [
            '$params',
            'console/params.php',
        ],
        'di' => 'common/di/*.php',
        'di-web' => [
            '$di',
            'http/di/*.php',
        ],
        'di-websocket' => [
            '$di',
            'websocket/di/*.php',
        ],
        'di-console' => '$di',
        'di-delegates' => [],
        'di-delegates-console' => '$di-delegates',
        'di-delegates-web' => '$di-delegates',
        'di-providers' => [],
        'di-providers-console' => '$di-providers',
        'di-providers-web' => '$di-providers',
        'events' => [],
        'events-console' => '$events',
        'events-web' => [
            '$events',
            'http/events.php',
        ],
        'bootstrap' => [
            'common/bootstrap.php'
        ],
        'bootstrap-console' => '$bootstrap',
        'bootstrap-web' => '$bootstrap',
        'routes' => [
            'http/routes/*.php',
        ],
    ],
    'config-plugin-environments' => [
        'dev' => [
            'params' => [
                'environments/dev/params.php',
            ],
        ],
        'prod' => [
            'params' => [
                'environments/prod/params.php',
            ],
        ],
        'test' => [
            'params' => [
                'environments/test/params.php',
            ],
        ],
    ],
    'config-plugin-options' => [
        'source-directory' => 'config',
    ],
];
