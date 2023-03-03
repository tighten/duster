<?php

it('lints with PHP CS Fixer using project config', function () {
    chdir(__DIR__ . '/../Fixtures/PhpCsFixerProjectConfig');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCsFixerProjectConfig'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP CS Fixer')
        ->toContain('Tighten/custom');
});
