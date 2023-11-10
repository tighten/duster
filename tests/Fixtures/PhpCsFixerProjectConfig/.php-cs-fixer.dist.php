<?php

use Tighten\Duster\Fixer\ClassNotation\CustomOrderedClassElementsFixer;
use Tighten\Duster\Support\PhpCsFixer;
use PhpCsFixer\Config;

return (new Config())
    ->setFinder(PhpCsFixer::getFinder())
    ->setUsingCache(false)
    ->registerCustomFixers([new CustomOrderedClassElementsFixer()])
    ->setRules([
        'Tighten/custom_ordered_class_elements' => [
            'order' => [
                'method_public',
                'method:__invoke',
            ],
        ],
    ]);
