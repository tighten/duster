<?php

use Tests\TestCase;

it('fixes class order', function ($input, $expected) {
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
]);
