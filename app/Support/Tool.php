<?php

namespace App\Support;

use function Termwind\{render};

abstract class Tool
{
    /**
     * @param array<int, string> $paths
     */
    abstract public function lint(array $paths): int;

    /**
     * @param array<int, string> $paths
     */
    abstract public function fix(array $paths): int;

    public function heading(string $heading): void
    {
        render('<div class="px-1 bg-green-300 w-full text-center font-bold">' . $heading . '</div>');
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
