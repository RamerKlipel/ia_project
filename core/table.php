<?php
namespace Core;
use Core\database;

trait table{
    protected $arrTable = [];
    protected $arrTh = [];
    protected $arrData = [];
    protected $arrPermIcon = [
        'r' => 'fa-solid fa-magnifying-glass',
        'u' => 'fa-solid fa-pen-to-square',
        'd' => 'fa-solid fa-trash'
    ];
    protected $arrPermTitle = [
        'r' => 'Read',
        'u' => 'Update',
        'd' => 'Delete'
    ];

    protected function addTable(string $idInput, string $label, array $arrAttrInput = []): void
    {
        $this->arrTh[] = $label;
        $this->arrTable[$idInput] = html::addTable($idInput, $arrAttrInput);
    }

    public function getArrTable(): array
    {
        return $this->arrTable;
    }

    public function getArrTh(): array
    {
        return $this->arrTh;
    }

    public function renderTable(): void //TODO passar para pageForm
    {
        // if (empty($this->getArrTable())) {
        //     http_response_code(500);
        //     throw new \Exception("It's necessary to have at least one column on the function Table");
        // }
        $this->setArrData();
        // $this->handleArrTrtable();
        ob_start();
            $this->callViewFrom($this->getViewTable());
        $this->setViewContent(ob_get_clean());

        $this->callViewFrom('index');
    }

    public function setArrPermIcon($arrPermIcon): void
    {
        $this->arrPermIcon = $arrPermIcon;
    }

    public function getArrPermIcon(): array
    {
        return $this->arrPermIcon;
    }

    public function setArrData(): void
    {
        $this->arrData = $this->model->getArrData();
    }

    public function getArrData(): array
    {
        return $this->arrData;
    }

    public function getArrPermTitle(): array
    {
        return $this->arrPermTitle;
    }

    public function handleDelete(): ?string // TODO passar database pro model
    {
        $id = ($this->get['id'] ?? null);
        if (($this->get['action'] ?? null) == 'd' && $id != null) {
            $table = $this->getSqlTable();
            try {
                Database::delete($table, 'ID'.strtoupper($table).' = :ID', [':ID' => $id]);
            } catch (\Exception $e) {
                http_response_code($e->getCode());
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }
        return json_encode($id);
    }
}
