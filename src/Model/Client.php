<?php declare(strict_types=1);

namespace App\Model;

use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;

/**
 * Entity Client.
 *
 * Database fields:
 *
 * @property string $id
 * @property array $assistant_id
 * @property string $platform
 * @property string $version
 * @property string $language
 * @property string $is_online
 * @property string $created_time
 *
 * Relations:
 * @property Api[] $apis
 * @property Storage[] $storageValues
 **/
class Client extends ActiveRecord {
    use MagicRelationsTrait;
    use MagicPropertiesTrait;

    public function getApisQuery(): ActiveQueryInterface {
        return $this->hasMany(Api::class, ['client_id' => 'id']);
    }

    public function getStorageValues(): ActiveQueryInterface {
        return $this->hasMany(Storage::class, ['client_id' => 'id']);
    }
}
