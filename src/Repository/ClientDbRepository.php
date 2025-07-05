<?php declare(strict_types=1);

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Exception\ServerErrorException;
use App\Model\Client;
use Throwable;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;

class ClientDbRepository extends Repository implements ClientRepositoryInterface {
    public static string $arClass = Client::class;

    /**
     * @return ActiveQuery
     */
    private function scope(): ActiveQueryInterface {
        return self::queryInstance(self::$arClass);
    }

    public function getByAssistantIdAndPlatform(string $assistantId, string $platform): Client {
        try {
            /** @var Client|null $model */
            $model = $this->scope()->where(['assistant_id' => $assistantId, 'platform' => strtolower($platform)])->one();
        } catch (Throwable $e) {
            throw new ServerErrorException($e->getMessage(), $e->getCode(), $e);
        }

        if (is_null($model)) {
            throw new NotFoundException('Client not found for assistant ID: ' . $assistantId . ' and platform: ' . $platform);
        }

        return $model;
    }

    public function list(string $assistantId): array {
        try {
            /** @var Client[] $models */
            return $this->scope()->with(['apis'])->where(['assistant_id' => $assistantId])->all();
        } catch (Throwable $e) {
            throw new ServerErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function updateOnline(string $id, bool $isOnline): void {
        try {
            /** @var Client|null $model */
            $model = $this->scope()->where(['id' => $id])->one();
        } catch (Throwable $e) {
            throw new ServerErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $model->is_online = $isOnline;
        $model->save();
    }
}
