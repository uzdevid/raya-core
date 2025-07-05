<?php declare(strict_types=1);

namespace App\Service\Brain;

use OpenAI;
use OpenAI\Client;

class PromptsBrain implements BrainInterface {
    private Client $client;

    public function __construct() {
        $this->client = OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function init(\App\Application\Websocket\Storage\Client $client): void {
        // TODO: Implement init() method.
    }

    public function reflection(\App\Application\Websocket\Storage\Client $client, string $query): string {
        $response = $this->client->responses()->create([
            'model' => 'gpt-4.1',
            'input' => $query,
            'prompt' => [
                'id' => 'pmpt_686121a2a8b88196980438dab96cbbf206ea80b56b4f96ae',
                'version' => '20',
                'variables' => [
                    'device' => 'diko.desktop',
                ]
            ]
        ]);

        return $response->outputText;
    }
}
