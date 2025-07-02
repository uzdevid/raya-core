<?php declare(strict_types=1);

use App\Application\Websocket\Service\Brain\ReflectionService;
use App\Application\Websocket\Service\Challenge\Configuration;
use App\Application\Websocket\Service\System\ClientService;
use App\Application\Websocket\Service\System\PingService;
use App\Application\Websocket\Service\System\ProxyService;

return [
    // system
    'system:ping' => PingService::class,
    'system:clients:list' => ClientService::class,
    'system:proxy:concrete' => ProxyService::class,

    // challenge
    'challenge:config' => Configuration::class,

    // brain
    'brain:reflection' => ReflectionService::class
];
