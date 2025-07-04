#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Application\Websocket\WebSocketRunner;

require_once __DIR__ . '/autoload.php';

$runner = new WebSocketRunner(
    address: 'websocket://0.0.0.0:8080',
    name: 'Raya.Core',
    workersCount: 10,
    rootPath: __DIR__,
    debug: $_ENV['YII_DEBUG'],
    checkEvents: $_ENV['YII_DEBUG'],
    environment: $_ENV['YII_ENV']
);

$runner->run();
