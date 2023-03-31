<?php

it('allows wildcard includes and excludes', function () {
    chdir(__DIR__ . '/../Fixtures/DusterConfigWildcard');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/DusterConfigWildcard'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Class members of differing visibility must be separated by a blank line')
        ->toContain('Class name doesn\'t match filename')
        ->toContain('Tighten')
        ->toContain('concat_space')
        ->not->toContain('ExcludeClass.php');
});
