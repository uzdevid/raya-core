<?php declare(strict_types=1);

namespace App\Service\Storage;

use App\Model\Storage;
use ReflectionException;
use Throwable;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

class StorageService {
    /**
     * @param string $clientId
     * @param string $key
     * @return string
     * @throws ReflectionException
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function get(string $clientId, string $key): string {
        $query = new ActiveQuery(Storage::class);

        /** @var Storage $model */
        $model = $query->where(['client_id' => $clientId, 'key' => $key])->one();

        return $model->value;
    }
}
