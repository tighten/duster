<?php

namespace Tests;

use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const STUBS_DIR = __DIR__ . '/stubs';

    protected function setUp(): void
    {
        parent::setUp();

        if (! file_exists(self::STUBS_DIR)) {
            mkdir(self::STUBS_DIR);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        array_map('unlink', array_filter((array) glob(self::STUBS_DIR . '/*')));
        rmdir(self::STUBS_DIR);
    }
}
