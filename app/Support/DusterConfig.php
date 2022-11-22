<?php

namespace App\Support;

use Illuminate\Support\Arr;

class DusterConfig
{
    /**
     * @param  array<string, array<int, string>|string>  $config
     */
    public function __construct(
        protected array $config = []
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }
}
