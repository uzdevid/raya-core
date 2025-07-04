<?php declare(strict_types=1);

use App\Application\Websocket\Application;
use App\Application\Websocket\ApplicationInterface;
use App\Application\Websocket\Event\OnClose;
use App\Application\Websocket\Event\OnCloseInterface;
use App\Application\Websocket\Event\OnConnect;
use App\Application\Websocket\Event\OnConnectInterface;
use App\Application\Websocket\Event\OnError;
use App\Application\Websocket\Event\OnErrorInterface;
use App\Application\Websocket\Event\OnMessage;
use App\Application\Websocket\Event\OnMessageInterface;
use App\Application\Websocket\Event\OnWorkerExit;
use App\Application\Websocket\Event\OnWorkerExitInterface;
use App\Application\Websocket\Storage\ClientCollection;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Service\Brain\AssistantBrain;
use App\Service\Brain\BrainInterface;

return [
    ApplicationInterface::class => Application::class,
    ClientCollectionInterface::class => ClientCollection::class,
    //
    BrainInterface::class => AssistantBrain::class,
    //
    OnConnectInterface::class => OnConnect::class,
    OnMessageInterface::class => OnMessage::class,
    OnErrorInterface::class => OnError::class,
    OnCloseInterface::class => OnClose::class,
    OnWorkerExitInterface::class => OnWorkerExit::class,
];
