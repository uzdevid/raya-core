<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M250704124620Client implements RevertibleMigrationInterface, TransactionalMigrationInterface {
    public const string TABLE_NAME = '{{%client}}';

    /**
     * @param MigrationBuilder $b
     * @throws InvalidConfigException
     * @throws NotSupportedException
     */
    public function up(MigrationBuilder $b): void {
        $b->createTable(self::TABLE_NAME, [
            'id' => ColumnBuilder::uuidPrimaryKey(), // Unique identifier for the client

            'assistant_id' => ColumnBuilder::uuid()->notNull(), // Reference to the assistant table

            'platform' => ColumnBuilder::string(50)->notNull(), // windows, mac-os, linux, android, ios, web, telegram bot

            'version' => ColumnBuilder::string(20)->notNull(), // Version of the client application

            'language' => ColumnBuilder::string(20)->notNull(), // Programming language of the client application

            'is_online' => ColumnBuilder::boolean()->notNull(), // Indicates if the client is currently online

            'created_time' => ColumnBuilder::timestamp()->notNull() // Timestamp when the client was created
        ]);

        $b->createIndex(self::TABLE_NAME, 'idx_client_assistant_id', 'assistant_id');
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
