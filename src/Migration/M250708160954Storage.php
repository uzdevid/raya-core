<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M250708160954Storage implements RevertibleMigrationInterface {
    public const string TABLE_NAME = '{{%storage}}';

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void {
        $b->createTable(self::TABLE_NAME, [
            'id' => ColumnBuilder::primaryKey(),

            'user_id' => ColumnBuilder::uuid()->notNull(), // Reference to the user table

            'client_id' => ColumnBuilder::uuid()->notNull(), // Reference to the client table

            'key' => ColumnBuilder::string()->notNull(), // md5(value)
            'value' => ColumnBuilder::text()->notNull(), // Serialized value

            'description' => ColumnBuilder::text()->notNull(), // Description of the API

            'created_time' => ColumnBuilder::timestamp()->notNull() // Timestamp when the assistant was created
        ]);

        $b->createIndex(self::TABLE_NAME, 'idx_storage_client_id_key', ['client_id', 'key']);
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
