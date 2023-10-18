<?php

declare(strict_types=1);

namespace Kalinin\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Kalinin\Framework\Console\CommandInterface;
use Doctrine\DBAL\Schema\Schema;

class MigrateCommand implements CommandInterface
{

    private string $name = 'migrate';
    private const MIGRATION_TABLE = 'migrations';

    public function __construct(
        private Connection $connection,
        private string $migration_path
    ) {
    }

    public function execute(array $parameters = []): int
    {

        try {
            $this->connection->setAutoCommit(false);
            //проверка таблицы миграций
            $this->createMigrationTable();

            $this->connection->beginTransaction();

            $appliedMigrations = $this->getApplyMigrations();
            $migrationFiles = $this->getMigrationsFiles();
            $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

            $schema = new Schema();

            foreach ($migrationsToApply as $migrate) {
                $migrationInstance = require $this->migration_path . "/$migrate";
                $migrationInstance->up($schema);

                $this->addMigration($migrate);
            }
            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();

        } catch (\Throwable $e) {
            //откат миграций
            $this->connection->rollBack();

            throw $e;
        }
        $this->connection->setAutoCommit(true);
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

    private function getMigrationsFiles(): array
    {
        $migrationsFiles = scandir($this->migration_path);
        return array_filter($migrationsFiles, function ($fileName) {
           return !in_array($fileName, ['.', '..']);
        });
        return array_values($migrationsFiles);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATION_TABLE)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }
}
