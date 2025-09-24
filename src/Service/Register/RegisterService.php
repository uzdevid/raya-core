<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Application\Websocket\Storage\Client;
use App\Application\Websocket\Storage\ClientCollectionInterface;
use App\Exception\NotFoundException;
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
            $assistant = $this->create($client);
        } else {
            $assistant = $this->update($client);
        }

        $client->add(Assistant::class, $assistant);
        $this->clientCollection->add($client);
    }

    /**
     * @throws NotSupportedException
     * @throws Exception
     * @throws Throwable
     * @throws InvalidConfigException
     * @throws ServerErrorException
     */
    private function create(Client $client): Assistant {
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

//        $this->apiRegisterService->createApis($clientModel);

        $instructions = $this->instructionsCollectService->collect(
            $clientModel->version,
            $assistant,
            $this->clientRepository->list($assistant->id)
        );

        $assistantResponse = $this->client->assistants()->create([
            'name' => sprintf('%s (%s)', $assistant->name, $assistant->id),
            'model' => $assistant->model,
            'description' => 'Assistant for ' . $client->identityId,
            'instructions' => $instructions,
        ]);

        $thread = $this->client->threads()->create();

        $assistant->assistant_id = $assistantResponse->id;
        $assistant->thread_id = $thread->id;
        $assistant->instructions = $instructions;

        try {
            $this->assistantRegisterService->save($assistant);
        } catch (ServerErrorException $e) {
            $transaction->rollBack();
            throw $e;
        }

        $transaction->commit();

        return $assistant;
    }

    /**
     * @param Client $client
     * @return Assistant
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundException
     * @throws NotSupportedException
     * @throws ServerErrorException
     * @throws Throwable
     */
    private function update(Client $client): Assistant {
        $assistant = $this->assistantRegisterService->getAssistant($client->identityId);

        if ($this->clientRegisterService->hasClient($client->id)) {
            $clientModel = $this->clientRegisterService->getClient($client->id);
        } else {
            $clientModel = $this->clientRegisterService->createClient($client, $assistant);
        }

//        $this->apiRegisterService->updateApis($clientModel);

        $instructions = $this->instructionsCollectService->collect(
            $clientModel->version,
            $assistant,
            $this->clientRepository->list($assistant->id)
        );

        $this->client->assistants()->modify($assistant->assistant_id, [
            'name' => sprintf('%s (%s)', $assistant->name, $assistant->id),
            'model' => $assistant->model,
            'description' => 'Assistant for ' . $client->identityId,
            'instructions' => $instructions,
        ]);

        $oldThreadId = $assistant->thread_id;

        $thread = $this->client->threads()->create();

        $assistant->instructions = $instructions;
        $assistant->thread_id = $thread->id;

        $this->assistantRegisterService->save($assistant);

        $this->client->threads()->delete($oldThreadId);

        return $assistant;
    }
}
