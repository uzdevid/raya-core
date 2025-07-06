<?php declare(strict_types=1);

namespace App\Repository;

use App\Exception\ServerErrorException;
use App\Model\Api;
use Throwable;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

class ApiDbRepository extends Repository implements ApiRepositoryInterface {
    public static string $arClass = Api::class;

    public function __construct(
        private readonly ConnectionInterface $connection
    ) {
    }

    /**
     * @return ActiveQuery
     */
    private function scope(): ActiveQueryInterface {
        return self::queryInstance(self::$arClass);
    }

    public function deleteByClientId(string $clientId): void {
        try {
            $this->connection->createCommand()->delete('api', ['client_id' => $clientId])->execute();
        } catch (Throwable $e) {
            throw new ServerErrorException('Failed to delete APIs for client ID: ' . $clientId, 0, $e);
        }
    }
}
