<?php

namespace App\Concerns;

use function Termwind\render;

trait CommandHelpers
{
    public function success(string $message): void
    {
        render('<div class="text-green-900 bg-green-300 px-1 font-bold">>> success: ' . $message . '</div>');
    }

    public function heading(string $heading): void
    {
        render('<div class="font-bold bg-yellow-800 px-1">=> ' . $heading . '</div>');
    }

    public function failure(string $message): void
    {
        render('<div class="text-red-900 bg-red-300 px-1 font-bold">!! error: ' . $message . '</div>');
    }
}
