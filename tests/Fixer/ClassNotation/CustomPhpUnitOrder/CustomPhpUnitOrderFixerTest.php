<?php

it('fixes PHPUnit class order', function ($input, $expected) {
    $config = __DIR__ . '/.php-cs-fixer.php';
    $file = self::STUBS_DIR . '/' . md5($input) . '.php';
    file_put_contents($file, $input);

    exec("php ./vendor/bin/php-cs-fixer -q fix {$file} --config={$config}");

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
