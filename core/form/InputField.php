<?php

namespace Core\form;

use Core\form\Field;
use Core\html;

class InputField extends Field
{
    public function __construct(
        private string $type,
        private string $idInput,
        private string $label = "",
        private array $arrAttrInput = [],
        private array $arrAttrDiv = [],
    ) {}

    public function render(mixed $arrData): string
    {
        return html::addInput($this->type, $this->idInput, $this->label, $this->arrAttrInput, $this->arrAttrDiv, $arrData);
    }
}
