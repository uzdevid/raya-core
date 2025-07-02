<?php declare(strict_types=1);

namespace App\Application\Websocket;

use ErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Workerman\Worker;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Yii\Runner\ApplicationRunner;

class WebSocketRunner extends ApplicationRunner {
    public function __construct(
        private readonly string $address,
        private readonly string $name,
        private readonly int    $workersCount,
        string                  $rootPath,
        bool                    $debug = false,
        bool                    $checkEvents = false,
        ?string                 $environment = null,
        string                  $bootstrapGroup = 'bootstrap-websocket',
        string                  $eventsGroup = 'events-websocket',
        string                  $diGroup = 'di-websocket',
        string                  $diProvidersGroup = 'di-providers-websocket',
        string                  $diDelegatesGroup = 'di-delegates-websocket',
        string                  $diTagsGroup = 'di-tags-websocket',
        string                  $paramsGroup = 'params-websocket',
        array                   $nestedParamsGroups = ['params'],
        array                   $nestedEventsGroups = ['events'],
        array                   $configModifiers = [],
        string                  $configDirectory = 'config',
        string                  $vendorDirectory = 'vendor',
        string                  $configMergePlanFile = '.merge-plan.php',
    ) {
        parent::__construct(
            $rootPath,
            $debug,
            $checkEvents,
            $environment,
            $bootstrapGroup,
            $eventsGroup,
            $diGroup,
            $diProvidersGroup,
            $diDelegatesGroup,
            $diTagsGroup,
            $paramsGroup,
            $nestedParamsGroups,
            $nestedEventsGroups,
            $configModifiers,
            $configDirectory,
            $vendorDirectory,
            $configMergePlanFile,
        );
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws InvalidConfigException
     * @throws ContainerExceptionInterface
     * @throws ErrorException
     */
    public function run(): void {
        $worker = new Worker($this->address);

        $worker->name = $this->name;
        $worker->count = $this->workersCount;

        $dispatcher = $this->getContainer()->get(ApplicationInterface::class);

        $worker->onConnect = [$dispatcher, 'onConnect'];

        $worker->onMessage = [$dispatcher, 'onMessage'];

        $worker->onClose = [$dispatcher, 'onClose'];

        $worker->onError = [$dispatcher, 'onError'];

        $worker->onWorkerExit = [$dispatcher, 'onWorkerExit'];

        Worker::runAll();
    }
}
