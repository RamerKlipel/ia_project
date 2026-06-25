<?php
namespace Core;
use Core\database;
use Exception;

use Core\form\InputField;
use Core\form\SelectField;


trait form {
    protected $arrInputs = [];
    public function setArrInputs(array $arrInputs): void
    {
        $this->arrInputs = $arrInputs;
    }

    public function renderForm(): void //TODO passar para o pageForm
    {
        if (empty($this->getArrInputs())) {
            http_response_code(500);
            throw new Exception("It's necessary to have at least one field on the function Form");

        }
        if (in_array($this->action, ['r', 'u']) && !empty($this->id)) {
            $this->setArrData();
        }

        ob_start();
            $this->callViewFrom($this->getViewForm());
        $this->setViewContent(ob_get_clean());

        $this->callViewFrom('index');
    }

    public function getArrInputs(): array
    {
        return $this->arrInputs;
    }

    public function Submit() // TODO passar para o pageform
    {
        try {
            if ($this->post && !($this->get['complete'] ?? false)) {
                $this->post = array_filter($this->post);
                $this->post = array_intersect_key($this->post, array_flip($this->fieldsSubmit[0] ?? []));
                switch ($this->action) {
                    case 'c':
                        $arrPdo = $arrInsert = [];

                        $this->handleTypeData($this->post);
                        foreach($this->post as $nmCampo => $value) {
                            $nmCampo = strtoupper($nmCampo);
                            $arrPdo[":$nmCampo"] = $value;
                            $arrInsert[$nmCampo] = ":$nmCampo";
                        }

                        Database::insert($this->getSqlTable(), $arrInsert, $arrPdo);
                    case 'u':
                        $arrPdo = $arrUpdate = [];
                        $nmTable = $this->getSqlTable();
                        $strIdTable = "ID" .strtoupper($nmTable);

                        $this->handleTypeData($this->post);
                        foreach($this->post as $nmCampo => $value) {
                            $nmCampo = strtoupper($nmCampo);
                            $arrPdo[":$nmCampo"] = $value;
                            $arrUpdate[] = "$nmCampo = :$nmCampo";
                        };

                        $arrPdo[":$strIdTable"] = $this->id;
                        $where = "$strIdTable = :$strIdTable";

                        Database::update($nmTable, $arrUpdate, $where, $arrPdo);
                }
            }
        } catch (Exception $e) {
            http_response_code(500);
            throw new Exception($e->getMessage());
        }
    }

    protected function addInput(string $type, string $idInput, string $label = '', array $arrAttrInput = [], array $arrAttrDiv = []): void
    {
        if ($this->action == "r") {
            $arrAttrInput['disabled'] = true;
        }

        $this->arrInputs[] = new InputField($type, $idInput, $label, $arrAttrInput, $arrAttrDiv);
    }

    protected function addSelect(string $idInput, string $label = '', array $arrSelectOptions = [], array $arrAttrInput = [], array $arrAttrDiv = []): void
    {
        if ($this->action == "r") {
            $arrAttrInput['disabled'] = true;
        }

        $this->arrInputs[] = new SelectField($idInput, $label, $arrSelectOptions, $arrAttrInput, $arrAttrDiv);
    }

    public function getWidth($nr = 0): string //TODO passar para a classe html
    {
        return "col-$nr";
    }

    public function handleTypeData($post)
    {
        foreach($this->post as $nmCampo => $value) {
            $prefix = substr($nmCampo, 0, 2);
            switch ($prefix) {
                case 'DA':
                    if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $value)) {
                        if (strlen($value) > 10) {
                            $this->post[$nmCampo] = formatDateHourDB($value);
                        } else {
                            $this->post[$nmCampo] = formatDateDB($value);
                        }
                    }
                    break;
                case 'VL':
                    $this->post[$nmCampo] = formatNumberBD($value);

            }
        }
    }
}
