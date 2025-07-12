<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M250704122816Assistant implements RevertibleMigrationInterface, TransactionalMigrationInterface {
    public const string TABLE_NAME = '{{%assistant}}';

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void {
        $b->createTable(self::TABLE_NAME, [
            'id' => ColumnBuilder::uuidPrimaryKey(),

            'owner_id' => ColumnBuilder::uuid()->notNull(), // Reference to the owner of the assistant

            'assistant_id' => ColumnBuilder::char(29)->notNull(), // Unique identifier for the assistant
            'thread_id' => ColumnBuilder::char(31)->notNull(), // Identifier for the thread associated with the assistant

            'name' => ColumnBuilder::string(16)->notNull(), // Name of the assistant
            'language' => ColumnBuilder::string(10)->notNull(), // Language of the assistant, e.g., 'en', 'ru', 'fr'

            'instructions' => ColumnBuilder::text()->notNull(), // Base instructions and rules for the assistant

            'created_time' => ColumnBuilder::timestamp()->notNull() // Timestamp when the assistant was created
        ]);

        $b->createIndex(self::TABLE_NAME, 'idx_assistant_owner_id', 'owner_id');
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
