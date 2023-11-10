<?php

use Tighten\Duster\Support\DusterConfig;

it('provides config values', function () {
    $dusterConfig = new DusterConfig([
        'paths' => ['path1', 'path2'],
        'using' => ['tlint', 'phpcs', 'php-cs-fixer', 'pint'],
    ]);

    expect($dusterConfig->get('paths'))->toBe(['path1', 'path2']);
    expect($dusterConfig->get('using'))->toBe(['tlint', 'phpcs', 'php-cs-fixer', 'pint']);
});

it('provides default exclude config values', function () {
    $dusterConfig = new DusterConfig([
        'paths' => ['path1', 'path2'],
        'lint' => true,
        'fix' => false,
        'using' => ['tlint', 'phpcs', 'php-cs-fixer', 'pint'],
    ]);

    expect($dusterConfig->get('exclude'))->toBe([
        '_ide_helper_actions.php',
        '_ide_helper_models.php',
        '_ide_helper.php',
        '.phpstorm.meta.php',
        'bootstrap/cache',
        'build',
        'node_modules',
        'storage',
    ]);
});

it('merges provided exclude with default exclude config values', function () {
    $dusterConfig = new DusterConfig([
        'paths' => ['path1', 'path2'],
        'lint' => true,
        'fix' => false,
        'using' => ['tlint', 'phpcs', 'php-cs-fixer', 'pint'],
        'exclude' => ['vendor'],
    ]);

    expect($dusterConfig->get('exclude'))->toBe([
        'vendor',
        '_ide_helper_actions.php',
        '_ide_helper_models.php',
        '_ide_helper.php',
        '.phpstorm.meta.php',
        'bootstrap/cache',
        'build',
        'node_modules',
        'storage',
    ]);
});
