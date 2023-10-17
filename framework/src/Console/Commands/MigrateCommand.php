<?php

declare(strict_types=1);

namespace Kalinin\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Kalinin\Framework\Console\CommandInterface;
use Doctrine\DBAL\Schema\Schema;

class MigrateCommand implements CommandInterface
{

    private string $name = 'migrate';
    private const MIGRATION_TABLE = 'migrations';

    public function __construct(
        private Connection $connection
    ) {
    }

    public function execute(array $parameters = []): int
    {

        try {
            //проверка таблицы миграций
            $this->createMigrationTable();
            $this->connection->beginTransaction();

            $appliedMigrations = $this->getApplyMigrations();
            dd($appliedMigrations);
            $this->connection->commit();

        } catch (\Throwable $e) {
            //откат миграций
            $this->connection->rollBack();

            throw $e;
        }
//        echo "Foo value is {$parameters['foo']}";
        return 0;
    }

    private function createMigrationTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(self::MIGRATION_TABLE)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATION_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP'
            ]);

            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            echo 'Migrations table created' . PHP_EOL;
        }

    }

    private function getApplyMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATION_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }
}
