<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use Tighten\Duster\Fixer\ClassNotation\CustomOrderedClassElementsFixer;

$finder = Finder::create()
    ->in(array_filter(
        [
            './app',
            './config',
            './database',
            './public',
            './resources',
            './routes',
            './tests',
        ],
        fn($dir) => is_dir($dir)
    ))
    ->notName('*.blade.php');

return (new Config())
    ->setFinder($finder)
    ->setUsingCache(false)
    ->registerCustomFixers([new CustomOrderedClassElementsFixer()])
    ->setRules([
        'Tighten/custom_ordered_class_elements' => [
            'order' => [
                'use_trait',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'invoke',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_public',
                'method_protected',
                'method_private',
                'magic',
            ],
        ],
    ]);
