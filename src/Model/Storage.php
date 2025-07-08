<?php declare(strict_types=1);

namespace App\Model;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;

/**
 * Entity Storage.
 *
 * Database fields:
 *
 * @property int $id
 * @property string $user_id
 * @property string $client_id
 * @property string $key
 * @property string $value
 * @property string $description
 * @property string $created_time
 **/
class Storage extends ActiveRecord {
    use MagicRelationsTrait;
    use MagicPropertiesTrait;
}
