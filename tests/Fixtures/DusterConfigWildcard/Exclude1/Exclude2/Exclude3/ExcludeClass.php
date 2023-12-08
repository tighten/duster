<?php

class WrongName
{
    private $private;
    protected $protected;
    public $public;

    private function method()
    {
        $a = 'b'.'c';
    }

    public function __invoke()
    {
        $a = 'b'.'c';
    }

    public function __construct()
    {
        // Fix this sniffer Generic.Commenting.Todo
    }
}
