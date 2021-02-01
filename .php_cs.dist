<?php

$finder = PhpCsFixer\Finder::create()
    ->notPath('storage/*')
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=' => null,
            ],
        ],
        'blank_line_after_opening_tag' => true,
        'blank_line_before_return' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'function_typehint_space' => true,
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'native_function_casing' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_spaces_around_offset' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'object_operator_without_whitespace' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline_array' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder);
