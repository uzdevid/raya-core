<?php declare(strict_types=1);

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Exception\ServerErrorException;
use App\Model\Assistant;
use Throwable;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;

class AssistantDbRepository extends Repository implements AssistantRepositoryInterface {

    public static string $arClass = Assistant::class;

    /**
     * @return ActiveQuery
     */
    private function scope(): ActiveQueryInterface {
        return self::queryInstance(self::$arClass);
    }

    /**
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function getByOwnerId(string $ownerId): Assistant {
        try {
            /** @var Assistant|null $model */
            $model = $this->scope()->where(['owner_id' => $ownerId])->one();
        } catch (Throwable $e) {
            throw new ServerErrorException($e->getMessage(), $e->getCode(), $e);
        }

        if (is_null($model)) {
            throw new NotFoundException('Assistant not found');
        }

        return $model;
    }

    /**
     * @throws ServerErrorException
     */
    public function existsByOwnerId(string $ownerId): bool {
        try {
            return $this->scope()->where(['owner_id' => $ownerId])->exists();
        } catch (Throwable $e) {
            throw new ServerErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
