<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\Brain;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Application\Websocket\Storage\Client;
use App\Service\Brain\BrainInterface;
use App\Service\Client\ClientService;

readonly class ReflectionService implements HandlerServiceInterface {
    /**
     * @param BrainInterface $brain
     * @param ClientService $clientService
     */
    public function __construct(
        private BrainInterface $brain,
        private ClientService  $clientService,
    ) {
    }

    public function handle(Client $client, Message $payload): void {
        $clientService = $this->clientService;

        $code = $this->brain->reflection($client, $payload->payload['query']);

        if (!str_ends_with($code, ';')) {
            $code .= ';';
        }

        print $code . "\n";

        eval($code);
    }
}
