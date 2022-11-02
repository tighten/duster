<?php

it('lints with pint using project config', function () {
    chdir(__DIR__ . '/../Fixtures/PintProjectConfig');

    [$statusCode, $output] = run('duster', [
        'path' => base_path('tests/Fixtures/PintProjectConfig'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using Pint')
        ->toContain('concat_space');
});
