<?php

it('lints with PHP_CodeSniffer using project config', function () {
    chdir(__DIR__ . '/../Fixtures/PhpCodeSnifferProjectConfig');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferProjectConfig'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP_CodeSniffer')
        ->toContain('Comment refers to a TODO task');
});
