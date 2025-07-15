<?php
declare(strict_types=1);

use App\Repository\ApiDbRepository;
use App\Repository\ApiRepositoryInterface;
use App\Repository\AssistantDbRepository;
use App\Repository\AssistantRepositoryInterface;
use App\Repository\ClientDbRepository;
use App\Repository\ClientRepositoryInterface;
use App\Service\Auth\AuthServiceInterface;
use App\Service\Auth\JwtAuthService;
use GuzzleHttp\Client as GuzzleClient;
use OpenAI\Client;
use Yiisoft\Hydrator\AttributeHandling\ResolverFactory\AttributeResolverFactoryInterface;
use Yiisoft\Hydrator\AttributeHandling\ResolverFactory\ContainerAttributeResolverFactory;
use Yiisoft\Hydrator\ObjectFactory\ContainerObjectFactory;
use Yiisoft\Hydrator\ObjectFactory\ObjectFactoryInterface;

/** @var array $params */

return [
    AttributeResolverFactoryInterface::class => ContainerAttributeResolverFactory::class,
    ObjectFactoryInterface::class => ContainerObjectFactory::class,
    //
    Client::class => static function () {
        return OpenAI::factory()->withHttpClient(new GuzzleClient([
            'proxy' => "http://gMeB2Z:6oKXGG@45.4.199.73:8000",
            'timeout' => 30,
            'connect_timeout' => 10,
            'headers' => ['OpenAI-Beta' => 'assistants=v2'],
        ]))->withApiKey($_ENV['OPENAI_API_KEY'])->make();
    },
    //
    AuthServiceInterface::class => [
        'class' => JwtAuthService::class,
        '__construct()' => [
            'key' => 'RAYA:wN4Vw$kc0B1'
        ]
    ],
    //
    AssistantRepositoryInterface::class => AssistantDbRepository::class,
    ClientRepositoryInterface::class => ClientDbRepository::class,
    ApiRepositoryInterface::class => ApiDbRepository::class
];
