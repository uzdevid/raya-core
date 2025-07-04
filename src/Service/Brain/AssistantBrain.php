<?php declare(strict_types=1);

namespace App\Service\Brain;

use GuzzleHttp\Client as GuzzleClient;
use OpenAI;
use OpenAI\Client;
use OpenAI\Responses\Threads\Messages\ThreadMessageResponse;
use OpenAI\Responses\Threads\ThreadResponse;

class AssistantBrain implements BrainInterface {
    private static ThreadResponse $thread;
    private Client $client;

    public function __construct() {
        $this->client = OpenAI::factory()
            ->withApiKey($_ENV['OPENAI_API_KEY'])
            ->withHttpClient(new GuzzleClient([
                'proxy' => "https://GfxXEV:eknThT@38.153.3.24:8000",
                'timeout' => 5,
                'connect_timeout' => 2,
            ]))
            ->make();
    }

    public function createThread(): void {
        self::$thread = $this->client->threads()->create();
    }

    public function reflection(string $query): string {
        $this->client->threads()->messages()->create(self::$thread->id, [
            'role' => 'user',
            'content' => $query,
        ]);

        $run = $this->client->threads()->runs()->create(self::$thread->id, [
            'assistant_id' => 'asst_6oRFNaNScY1tlmpnzCWqvVD9',
        ]);

        do {
            sleep(1);
            $runStatus = $this->client->threads()->runs()->retrieve(self::$thread->id, $run->id);
        } while ($runStatus->status !== 'completed');

        $messages = $this->client->threads()->messages()->list(self::$thread->id, [
            'run_id' => $run->id,
        ]);

        $messages = $messages->data;

        /** @var ThreadMessageResponse $message */
        $message = end($messages);

        return $message->content[0]->text->value;
    }

    public function answer(string $question, string $answer): string {
        return '';
    }

    public function reReflection(string $query): string {
        return '';
    }
}
