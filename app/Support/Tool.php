<?php

namespace App\Support;

use function Termwind\{render};

abstract class Tool
{
    public function __construct(
        protected DusterConfig $dusterConfig,
    ) {
    }

    abstract public function lint(): int;

    abstract public function fix(): int;

    public function heading(string $heading): void
    {
        render('<div class="px-1 bg-green-300 text-black w-full text-center font-bold">' . $heading . '</div>');
    }

    public function success(string $message): void
    {
        render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-green-300 text-black">Success</div>
                <em class="ml-1">
                {$message}
                </em>
            </div>
        HTML);
    }

    public function failure(string $message): void
    {
        render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-red-300 text-black">Error</div>
                <em class="ml-1">
                {$message}
                </em>
            </div>
        HTML);
    }
}
