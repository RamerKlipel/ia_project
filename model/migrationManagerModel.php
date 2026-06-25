<?php
namespace model;
use Core\database;
class migrationManagerModel
{
    public function getMigrationsExecuted(): array
    {
        $sql = 'SELECT IDMIGRATION, NMMIGRATION, DAEXECUTED
                FROM migration';
        $arrMigrationsExecuted = database::ExecuteSqlData($sql);
        $arrMigrations = [];
        foreach($arrMigrationsExecuted as $arrDataMigrations) {
            $arrMigrations[] = $arrDataMigrations['NMMIGRATION'];
        }
        return $arrMigrations;
    }

    public function createTableMigration(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS migration (
                    IDMIGRATION INT NOT NULL AUTO_INCREMENT,
                    NMMIGRATION VARCHAR(50) NOT NULL,
                    DAEXECUTED TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (IDMIGRATION)
                );";
        database::ExecuteSql($sql);
    }

    public function executeSqlMigration(string $sql): string|int
    {
        return database::ExecuteSql($sql);
    }

    public function insertMigration(string $nmMigration)
    {
        database::insert('migration', ['NMMIGRATION' => ':NMMIGRATION'], [":NMMIGRATION" => $nmMigration]);
    }

    public function transactionStart()
    {
        database::transactionStart();
    }

    public function transactionCommit()
    {
        database::transactionCommit();
    }

    public function transactionRollback()
    {
        database::transactionRollback();
    }
}
