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

it('lints only the explicit paths when project has a custom phpcs config', function () {
    chdir(__DIR__ . '/../Fixtures/PhpCodeSnifferProjectConfigWithExplicitFile');

    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferProjectConfigWithExplicitFile/CleanClass.php'),
        '--using' => 'phpcs',
    ]);

    expect($statusCode)->toBe(0)
        ->and($output)
        ->toContain('Linting using PHP_CodeSniffer')
        ->not->toContain('Comment refers to a TODO task');
});

it('falls back to ruleset file directives when the explicit path is the project root', function () {
    chdir(__DIR__ . '/../Fixtures/PhpCodeSnifferProjectConfigWithExplicitFile');

    // The `path` argument resolves to the same absolute path as the project root
    // (getcwd()), so PhpCodeSniffer treats this as the default invocation and
    // defers to the ruleset's <file> directives.
    [$statusCode, $output] = run('lint', [
        'path' => base_path('tests/Fixtures/PhpCodeSnifferProjectConfigWithExplicitFile'),
        '--using' => 'phpcs',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Linting using PHP_CodeSniffer')
        ->toContain('Comment refers to a TODO task');
});
