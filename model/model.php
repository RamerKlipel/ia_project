<?php
namespace model;
use Core\database;

class model {
    protected $sql = '';
    protected $arrPdo = [];
    protected $strTable = '';

    public function setSql(string $sql): void
    {
        $this->sql = $sql;
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getDebugSql(): string
    {
        return database::debugPDO($this->sql, $this->getArrPdo());
    }

    public function setArrPdo(array $arrPdo): void
    {
        $this->arrPdo = $arrPdo;
    }

    public function getArrPdo(): array
    {
        return $this->arrPdo;
    }

    public function getArrData(): array
    {
        $arrDados = database::ExecuteSqlData($this->sql, $this->getArrPdo());
        $this->handleTypeDataGet($arrDados);
        return $arrDados;
    }

    public function setSqlTable(string $strTable):void
    {
        $this->strTable = $strTable;
    }

    public function getSqlTable(): string
    {
        return $this->strTable;
    }

    public function getArraySelect(string $table, string $nmIdTable = '', string $nmValTable = ''): array
    {
        $strUpperTable = strtoupper($table);
        $nmIdTable = !empty($nmIdTable) ? $nmIdTable : "ID$strUpperTable";
        $nmValTable = !empty($nmValTable) ? $nmValTable : "NM$strUpperTable";

        $sql = "SELECT $nmIdTable, $nmValTable
                FROM $table
                ORDER BY $nmIdTable";
        $arrAssociative = database::executeSqlMountAssociativeArray($sql, $nmIdTable, $nmValTable);

        return $arrAssociative;
    }

    public function getArrCreditCard(): array
    {
        $sql = "SELECT IDCREDITCARD, CONCAT(NMCREDITCARD, ' (',NRFINALFOURNUMBER, ')') NMCREDITCARD
                FROM creditcard";
        $arrCreditCard = Database::executeSqlMountAssociativeArray($sql, 'IDCREDITCARD', 'NMCREDITCARD');

        return $arrCreditCard ?? [];
    }

    public function handleTypeDataGet(array &$post)
    {
        foreach(($post[0] ?? []) as $nmCampo => $value) {
            if (empty($value)) {
                continue;
            }
            $prefix = substr($nmCampo, 0, 2);
            switch ($prefix) {
                case 'DA':
                    if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $value)) {
                        if (strlen($value) > 11) {
                            $post[0][$nmCampo] = formatDateHourUser($value);
                        } else {
                            $post[0][$nmCampo] = formatDateUser($value);
                        }
                    }
                    break;
                case 'VL':
                    $post[0][$nmCampo] = formatNumberUser($value);
                    break;
            }
        }
    }
}
