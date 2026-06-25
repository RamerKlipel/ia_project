<?php

namespace Core\form;

abstract class Field
{
    abstract public function render(mixed $arrData): string;
}
