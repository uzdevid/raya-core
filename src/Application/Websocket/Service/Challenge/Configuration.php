<?php declare(strict_types=1);

namespace App\Application\Websocket\Service\Challenge;

use App\Application\Websocket\Dto\Message;
use App\Application\Websocket\HandlerServiceInterface;
use App\Service\Client\Send;
use Workerman\Connection\TcpConnection;

class Configuration implements HandlerServiceInterface {
    /**
     * @param TcpConnection $tcpConnection
     * @param Message $payload
     */
    public function handle(TcpConnection $tcpConnection, Message $payload): void {
        Send::to($tcpConnection)->message('bootstrap:config', [
            'dispatcher' => [
                'enabled' => true,
                'params' => [
                    'type' => 'dispatcher.raya',
                ]
            ],
            'input' => [
                'enabled' => true,
                'params' => [
                    'type' => 'input.text',
                    'placeholder' => 'Prompt: ',
                ]
            ],
            'output' => [
                'enabled' => true,
                'params' => [
                    'type' => 'output.openai.tts',
                    'voice' => 'nova',
                    'model' => 'tts-1-hd-1106',
                    'apiKey' => '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'
                ]
            ],
        ]);
    }
}
