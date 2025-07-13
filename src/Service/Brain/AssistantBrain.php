<?php declare(strict_types=1);

namespace App\Service\Brain;

use App\Exception\NotFoundException;
use App\Model\Assistant;
use OpenAI\Client;

readonly class AssistantBrain implements BrainInterface {
    public function __construct(
        private Client $client
    ) {
    }

    public function init(\App\Application\Websocket\Storage\Client $client): void {
        //        $client->add(ThreadResponse::class, $this->client->threads()->create());
        //        $this->clientCollection->add($client);
    }

    /**
     * @throws NotFoundException
     */
    public function reflection(\App\Application\Websocket\Storage\Client $client, string $query): string {
        $assistant = $client->get(Assistant::class);

        if (is_null($assistant)) {
            throw new NotFoundException('Assistant not found for client: ' . $client->id);
        }

        $threadId = $assistant->thread_id;

        $this->client->threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => $query,
        ]);

        $run = $this->client->threads()->runs()->create($threadId, [
            'assistant_id' => $assistant->assistant_id,
        ]);

        do {
            usleep(250_000);
            $runStatus = $this->client->threads()->runs()->retrieve($threadId, $run->id);
        } while ($runStatus->status !== 'completed');

        // ~ 1 second
        $messages = $this->client->threads()->messages()->list($threadId, [
            'run_id' => $run->id,
            'limit' => 1,
            'order' => 'desc',
        ]);

        $messages = $messages->data;

        $message = $messages[0];

        $this->client->threads()->messages()->delete($threadId, $message->id);
        
        return $message->content[0]->text->value;
    }
}
