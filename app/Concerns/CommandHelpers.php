<?php

namespace App\Concerns;

use function Termwind\render;

trait CommandHelpers
{
    public function success(string $message): void
    {
        render('<div class="text-green-900 bg-green-300 px-1 font-bold">>> success: ' . $message . '</div>');
    }
}
