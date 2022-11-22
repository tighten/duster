<?php

it('uses duster config file', function () {
    chdir(__DIR__ . '/../Fixtures/DusterConfig');

    [$statusCode, $output] = run('duster', [
        'path' => base_path('tests/Fixtures/DusterConfig'),
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('Class members of differing visibility must be separated by a blank line')
        ->toContain('Class name doesn\'t match filename; expected "class IncludeClass"')
        ->toContain('Tighten/custom_ordered_class_elements')
        ->toContain('concat_space')
        ->not->toContain('ExcludeClass.php');
});
