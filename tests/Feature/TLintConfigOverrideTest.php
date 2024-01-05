<?php

it('lints with tlint using project config', function () {
    chdir(__DIR__ . '/../Fixtures/TlintProjectConfig');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/TlintProjectConfig'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using TLint')
        ->toContain('Use blade `{{ $model }}` auto escaping for models')
        ->not->toContain('Spaces around blade rendered content');
});
