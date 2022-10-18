<?php

namespace Tighten\Duster\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
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
