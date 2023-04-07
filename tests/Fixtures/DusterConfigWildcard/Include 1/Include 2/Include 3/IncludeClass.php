<?php

class WrongName
{
    private $private;
    protected $protected;
    public $public;

    private function method()
    {
        // Tighten/custom_ordered_class_elements
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
