<?php

namespace Tighten\Duster\Tests\Fixer\ClassNotation;

use Tighten\Duster\Tests\TestCase;

class CustomOrderedClassElementsFixerTest extends TestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function test_fix($input, $expected): void
    {
        $config = __DIR__ . '/.php-cs-fixer.php';
        $file = self::STUBS_DIR . '/' . md5($input) . '.php';
        file_put_contents($file, $input);

        exec("php ./vendor/bin/php-cs-fixer -q fix {$file} --config={$config}");

        $this->assertSame($expected, file_get_contents($file));
    }

    public function provideFixCases(): array
    {
        return [
            [
                <<<'EOT'
<?php

class Example_Staff
{
    public const EXAMPLE = true;

    public function __call($name, $arguments){}

    public function __get($name){}

    public function helperFunction(){}

    public function __invoke(){}

    public function __set($name, $value){}

    protected function test(){}
}
EOT,
                <<<'EOT'
<?php

class Example_Staff
{
    public const EXAMPLE = true;

    public function __invoke(){}

    public function helperFunction(){}

    protected function test(){}

    public function __call($name, $arguments){}

    public function __get($name){}

    public function __set($name, $value){}
}
EOT,
            ],
        ];
    }
}
