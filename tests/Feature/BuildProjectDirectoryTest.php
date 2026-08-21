<?php

it('lints reports issues when running inside a build project directory with issues', function () {
    chdir(__DIR__ . '/../Fixtures/BuildProjectDirectoryIssues/build');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/BuildProjectDirectoryIssues/build'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using Pint')
        ->toContain('concat_space');
});

it('lints when running inside a build project directory', function () {
    chdir(__DIR__ . '/../Fixtures/BuildProjectDirectoryNoIssues/build');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/BuildProjectDirectoryNoIssues/build'),
    ]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('Linting using Pint');
});
