<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Application\Websocket\Storage\Client;
use App\Exception\ServerErrorException;
use App\Model\Assistant;
use Throwable;

class ClientRegisterService {
    /**
     * @param Client $client
     * @param Assistant $assistant
     * @return \App\Model\Client
     * @throws ServerErrorException
     */
    public function createClient(Client $client, Assistant $assistant): \App\Model\Client {
        $model = new \App\Model\Client();

        $model->id = $client->id;
        $model->assistant_id = $assistant->id;
        $model->platform = $client->platform;
        $model->version = $client->version;
        $model->language = $client->language;
        $model->is_online = true;
        $model->created_time = date('Y-m-d H:i:s');

        return $this->save($model);
    }

    /**
     * @param \App\Model\Client $client
     * @return \App\Model\Client
     * @throws ServerErrorException
     */
    public function save(\App\Model\Client $client): \App\Model\Client {
        try {
            $isSaved = $client->save();
        } catch (Throwable $exception) {
            throw new ServerErrorException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (!$isSaved) {
            throw new ServerErrorException('Client could not be created');
        }

        return $client;
    }
}
