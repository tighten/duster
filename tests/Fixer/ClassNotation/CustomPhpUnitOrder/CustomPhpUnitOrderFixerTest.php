<?php

use Tests\TestCase;

it('fixes PHPUnit class order', function ($input, $expected) {
    $config = __DIR__ . TestCase::DS . '.php-cs-fixer.php';
    $file = TestCase::STUBS_DIR . TestCase::DS . md5((string) $input) . '.php';
    file_put_contents($file, $input);

    if (str_starts_with(strtoupper(PHP_OS), 'WIN')) {
        exec("SET PHP_CS_FIXER_IGNORE_ENV=true && vendor\\bin\\php-cs-fixer -q fix {$file} --config={$config} 2>NUL");
    } else {
        exec("PHP_CS_FIXER_IGNORE_ENV=true ./vendor/bin/php-cs-fixer -q fix {$file} --config={$config} 2>/dev/null");
    }

    $this->assertSame($expected, file_get_contents($file));
})->with([
    [
        <<<'EOT'
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function example_test_1()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function example_test_2()
    {
        $this->assertTrue(true);
    }

    public function setUp(): void
    {
        parent::setUp();
    }
}
EOT,
        <<<'EOT'
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }
    /** @test */
    public function example_test_1()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function example_test_2()
    {
        $this->assertTrue(true);
    }
}
EOT,
    ],
    [
        <<<'EOT'
<?php

namespace App\Console\Commands;

class Example extends Command
{
    public function handle()
    {
        $this->setUp();
    }

    public function setUp()
    {
        // do something
    }
}
EOT,
        <<<'EOT'
<?php

namespace App\Console\Commands;

class Example extends Command
{
    public function handle()
    {
        $this->setUp();
    }

    public function setUp()
    {
        // do something
    }
}
EOT,
    ],
]);
