<?php

namespace Core\form;

use Core\form\Field;
use Core\html;

class SelectField extends Field
{
    public function __construct(
        private string $idInput,
        private string $label = "",
        private array $arrSelectOptions = [],
        private array $arrAttrInput = [],
        private array $arrAttrDiv = [],
    ) {}

    public function render(mixed $arrData): string
    {
        return html::addSelect($this->idInput, $this->label, $this->arrSelectOptions, $this->arrAttrInput, $this->arrAttrDiv, $arrData);
    }
}
