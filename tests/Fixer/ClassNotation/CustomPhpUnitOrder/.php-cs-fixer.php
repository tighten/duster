<?php

use Tighten\Duster\Fixer\ClassNotation\CustomPhpUnitOrderFixer;
use PhpCsFixer\Config;

return (new Config())
    ->setUsingCache(false)
    ->registerCustomFixers([new CustomPhpUnitOrderFixer])
    ->setRules(['Tighten/custom_phpunit_order' => true]);
