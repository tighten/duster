<?php

namespace Tests;

use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public const DS = DIRECTORY_SEPARATOR;

    public const STUBS_DIR = __DIR__ . self::DS . 'stubs';

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

        array_map('unlink', array_filter((array) glob(self::STUBS_DIR . self::DS . '*')));
        rmdir(self::STUBS_DIR);
    }
}
