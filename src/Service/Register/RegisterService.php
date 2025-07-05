<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Application\Websocket\Storage\Client;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Exception\ServerErrorException;
use App\Model\Assistant;
use App\Repository\ClientRepositoryInterface;
use Throwable;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

readonly class RegisterService {
    public function __construct(
        private \OpenAI\Client             $client,
        private AssistantRegisterService   $assistantRegisterService,
        private ClientRegisterService      $clientRegisterService,
        private ApiRegisterService         $apiRegisterService,
        private ClientRepositoryInterface  $clientRepository,
        private InstructionsCollectService $instructionsCollectService,
        private ConnectionInterface        $connection,
        private ClientCollectionInterface  $clientCollection
    ) {
    }

    /**
     * @param Client $client
     * @throws ServerErrorException
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function register(Client $client): void {
        if (!$this->assistantRegisterService->hasAssistant($client->identityId)) {
            $transaction = $this->connection->createTransaction();
            $transaction->begin();

            try {
                $assistant = $this->assistantRegisterService->createAssistant($client->identityId);
            } catch (ServerErrorException $e) {
                $transaction->rollBack();
                throw $e;
            }

            try {
                $clientModel = $this->clientRegisterService->createClient($client, $assistant);
            } catch (ServerErrorException $e) {
                $transaction->rollBack();
                throw $e;
            }

            $this->apiRegisterService->createApis($clientModel);

            $instructions = $this->instructionsCollectService->collect(
                $clientModel->version,
                $assistant,
                $this->clientRepository->list($assistant->id)
            );

            $assistantResponse = $this->client->assistants()->create([
                'name' => sprintf('%s (%s)', $assistant->name, $assistant->id),
                'model' => 'gpt-4.1',
                'description' => 'Assistant for ' . $client->identityId,
                'instructions' => $instructions,
            ]);

            $assistant->assistant_id = $assistantResponse->id;
            $assistant->instructions = $instructions;

            try {
                $this->assistantRegisterService->save($assistant);
            } catch (ServerErrorException $e) {
                $transaction->rollBack();
                throw $e;
            }

            $transaction->commit();
        } else {
            $assistant = $this->assistantRegisterService->getAssistant($client->identityId);
        }

        $client->add(Assistant::class, $assistant);
        $this->clientCollection->add($client);
    }
}
