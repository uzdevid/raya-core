<?php declare(strict_types=1);

namespace App\Service\Brain;

use OpenAI;
use OpenAI\Client;

class OpenAiBrain implements BrainInterface {
    private Client $client;

    public function __construct() {
        $this->client = OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function reflection(string $query): string {
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

    public function answer(string $question, string $answer): string {
        $response = $this->client->responses()->create([
            'model' => 'gpt-4.1',
            'prompt' => [
                'id' => 'pmpt_685e44bdc7108197801dd0d7eea4b5580a36405f9eca1233',
                'version' => '6',
                'variables' => [
                    'question' => $question,
                    'answer' => $answer
                ]
            ]
        ]);

        return $response->outputText;
    }

    public function reReflection(string $query): string {
        $response = $this->client->responses()->create([
            'model' => 'gpt-4.1',
            'prompt' => [
                'id' => 'pmpt_685e4f0738148193b9e80b7db9e8a4b90062559638e2a345',
                'version' => '3',
                'variables' => [
                    'prompt' => $query
                ]
            ]
        ]);

        return $response->outputText;
    }
}
