<?php
namespace Core;
abstract class migration
{
    abstract public function up(): string;
    abstract public function down(): string;
}
