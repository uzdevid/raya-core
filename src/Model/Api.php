<?php declare(strict_types=1);

namespace App\Model;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;

/**
 * Entity Api.
 *
 * Database fields:
 *
 * @property string $id
 * @property string $client_id
 * @property string $code
 * @property string $description
 * @property array<string, array<string, string>> $arguments
 * @property string $returns
 * @property array $examples
 * @property string $created_time
 **/
class Api extends ActiveRecord {
    use MagicRelationsTrait;
    use MagicPropertiesTrait;
}
