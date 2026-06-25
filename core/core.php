<?php

namespace Core;

use Core\session;

abstract class core
{
    public $get = [];
    public $post = [];
    public $request = [];
    public $server = [];
    public $action = "";
    public ?int $id = null;
    protected object $model;
    protected string $viewContent;
    protected $arrJs = [];
    protected $arrCss = [];
    protected $arrPermCRUD = ["c" => true, "r" => true, "u" => true, "d" => true];


    public function __construct(string $sqlTable)
    {
        $this->handleGlobalVariables();
        $arrUrl = !empty($this->get['url'] ?? "") ? $this->handleUrl($this->get['url']) : [];
        $this->setModel($arrUrl['CLASS'] ?? "");
        Session::start();
        $this->setSqlTable($sqlTable);

        if (empty($arrUrl['METHOD'])) {
            $this->main();
        }
    }

    protected function setSql(string $sql): void
    {
        $sql = $this->handleSqlVar($sql);
        $this->model->setSql($sql);
    }

    protected function getSql(): string
    {
        return $this->model->getSql();
    }

    protected function setArrPdo(array $arrPdo): void
    {
        $this->model->setArrPdo($arrPdo);
    }

    protected function getArrPdo(): array
    {
        return $this->model->getArrPdo();
    }

    protected function callViewFrom(String $path): void
    {
        include_once __DIR__ . "/../view/$path.view.php";
    }

    private function handleGlobalVariables(): void
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = array_merge($this->get, $this->post);
        $this->server = $_SERVER;
        $this->action = ($this->get["action"] ?? "");
        $this->id = filter_var(($this->get['id'] ?? ""), FILTER_VALIDATE_INT);

        $jsonInput = file_get_contents('php://input');

        if (!empty($jsonInput)) {
            $jsonInput = json_decode($jsonInput, true);
            if (is_array($jsonInput)) {
                $this->post = array_merge($this->post, $jsonInput);
            }
        }

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
        unset($_GET);
        unset($_GET);
    }

    private function setModel(string $model): void
    {
        if (file_exists(__DIR__ . "/../model/" . $model . "Model.php")) {
            require_once __DIR__ . "/../model/" . $model . "Model.php";
            $model = "\model\\" . $model . "Model";
            $this->model = new $model();
        } else {
            require_once __DIR__ . "/../model/model.php";
            $model = "\model\\Model";
            $this->model = new $model();
        }
    }

    protected function setSqlTable(string $strTable): void
    {
        $this->model->setSqlTable($strTable);
    }

    protected function getSqltable(): string
    {
        return $this->model->getSqltable();
    }

    protected function addJs(string $js, $attrScrpit = []): void
    {
        $arrAttrScript = [];
        foreach ($attrScrpit as $key => $val) {
            $arrAttrScript[] = " $key=\"$val\"";
        }
        $this->arrJs[] = "<script " . implode(" ", $arrAttrScript) . " src=\"./public/js/$js.js\"></script>";
    }

    protected function addCss(string $css): void
    {
        $this->arrCss[] = $css;
    }

    protected function getArrJs(): array
    {
        return $this->arrJs;
    }

    protected function getArrCss(): array
    {
        return $this->arrCss;
    }

    public function setViewContent(string $viewContent): void
    {
        $this->viewContent = $viewContent;
    }

    public function getViewContent(): ?string
    {
        return $this->viewContent;
    }

    public function getArrPermCrud(): array
    {
        return $this->arrPermCRUD;
    }

    protected function handleUrl(string $url): array
    {
        preg_match("/^([^@?]+)(?:@([^?]+))?/", $url, $match);
        $arrUrl = [
            'FULLURL' => ($match[0] ?? ''),
            'CLASS' => ($match[1] ?? ''),
            'METHOD' => ($match[2] ?? '')
        ];
        return ($arrUrl ?? []);
    }

    public function handleSqlVar(string $sql): string
    {
        $idTable = "ID" . strtoupper($this->model->getSqlTable());
        if (!empty($this->action) && in_array($this->action, ['r', 'u']) && !empty($this->id)) {
            $sql = str_replace('{{WHERE}}', "AND $idTable = $this->id", $sql);
        } else {
            $sql = str_replace('{{WHERE}}', "", $sql);
        }

        if (!str_contains($sql, $idTable)) { //TODO make tratament for dev only, just in case
            throw new \Exception("Table ID not especified in the main sql", 500);
        }
        return $sql;
    }

    public function getDebugSql(): string
    {
        return $this->model->getDebugSql();
    }

    public function main():void {}
}
