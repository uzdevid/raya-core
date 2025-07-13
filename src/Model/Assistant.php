<?php declare(strict_types=1);

namespace App\Model;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;

/**
 * Entity Assistant.
 *
 * Database fields:
 *
 * @property string $id
 * @property string $owner_id
 * @property string $assistant_id
 * @property string $thread_id
 * @property string $name
 * @property string $language
 * @property string $model
 * @property string $instructions
 * @property string $created_time
 **/
class Assistant extends ActiveRecord {
    use MagicRelationsTrait;
    use MagicPropertiesTrait;
}
