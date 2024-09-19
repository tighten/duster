<?php

namespace App\Support;

use function Termwind\{render};

abstract class Tool
{
    public function __construct(
        protected DusterConfig $dusterConfig,
    ) {}

    abstract public function lint(): int;

    abstract public function fix(): int;

    public function heading(string $heading): void
    {
        render('<div class="font-bold bg-yellow-800 px-1">=> ' . $heading . '</div>');
    }

    public function success(string $message): void
    {
        render('<div class="text-green-900 bg-green-300 px-1 font-bold">>> ' . $message . '</div>');
    }

    public function failure(string $message): void
    {
        render('<div class="text-red-900 bg-red-300 px-1 font-bold">!! ' . $message . '</div>');
    }
}
