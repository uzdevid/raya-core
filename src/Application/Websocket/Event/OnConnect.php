<?php declare(strict_types=1);

namespace App\Application\Websocket\Event;

use App\Application\Websocket\Storage\Client;
use App\Event\NewClient;
use App\Repository\ClientRepositoryInterface;
use App\Service\Brain\BrainInterface;
use App\Service\Register\RegisterService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

readonly class OnConnect implements OnConnectInterface {
    public function __construct(
        private EventDispatcherInterface  $dispatcher,
        private RegisterService           $registerService,
        private BrainInterface $brain,
        private ClientRepositoryInterface $clientRepository
    ) {
    }

    /**
     * @param Client $client
     */
    public function handle(Client $client): void {
        $this->dispatcher->dispatch(new NewClient($client));

        try {
            $this->registerService->register($client);
        } catch (Throwable $e) {
            print_r($e->getMessage());
            $client->connection->close(['error' => 'Server error occurred during registration.']);
            return;
        }
        
        $this->brain->init($client);

        $this->clientRepository->updateOnline($client->id, true);
    }
}
