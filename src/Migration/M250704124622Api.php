<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M250704124622Api implements RevertibleMigrationInterface, TransactionalMigrationInterface {
    public const string TABLE_NAME = '{{%api}}';

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void {
        $b->createTable(self::TABLE_NAME, [
            'id' => ColumnBuilder::primaryKey(),

            'client_id' => ColumnBuilder::uuid()->notNull(), // Reference to the client table

            'code' => ColumnBuilder::string()->notNull(), // api.speak({text})

            'description' => ColumnBuilder::text(255)->notNull(), // Description of the API

            'arguments' => ColumnBuilder::json()->notNull(), // Arguments for the API, e.g., {"arg1": {"type":"string","description":"value1"}, "arg2": "value2"}

            'returns' => ColumnBuilder::text()->notNull(), // Returns for the API, e.g. string<text of the response>

            'examples' => ColumnBuilder::json()->notNull(), // Examples for the API, e.g., {"example1": "value1", "example2": "value2"}

            'created_time' => ColumnBuilder::timestamp()->notNull() // Timestamp when the assistant was created
        ]);

        $b->createIndex(self::TABLE_NAME, 'idx_api_client_id', 'client_id');
    }

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function down(MigrationBuilder $b): void {
        $b->dropTable(self::TABLE_NAME);
    }
}
