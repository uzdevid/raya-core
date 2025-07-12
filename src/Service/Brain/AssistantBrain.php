<?php declare(strict_types=1);

namespace App\Service\Brain;

use App\Exception\NotFoundException;
use App\Model\Assistant;
use OpenAI\Client;
use OpenAI\Responses\Threads\Messages\ThreadMessageResponse;

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
            sleep(1);
            $runStatus = $this->client->threads()->runs()->retrieve($threadId, $run->id);
        } while ($runStatus->status !== 'completed');

        $messages = $this->client->threads()->messages()->list($threadId, [
            'run_id' => $run->id,
        ]);

        $messages = $messages->data;

        /** @var ThreadMessageResponse $message */
        $message = end($messages);

        return $message->content[0]->text->value;
    }
}
