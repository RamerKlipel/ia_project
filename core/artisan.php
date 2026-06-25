<?php
require_once __DIR__.'/../vendor/autoload.php';

$command = ($argv[1] ?? "");

match ($command) {
    'migrate' => new Core\migrationManager(),
    default => print("Command not found. \n")
};
