<?php
namespace Controllers;

use Core\core;

class index extends core {
    public function __construct()
    {
        parent::__construct('Home');
        callViewFrom("emptyindex");
    }

    public function render() {}
}
