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
        $this->config['exclude'] = array_merge(
            $this->config['exclude'] ?? [],
            [
                '_ide_helper_actions.php',
                '_ide_helper_models.php',
                '_ide_helper.php',
                '.phpstorm.meta.php',
                'bootstrap/cache',
                'build',
                'node_modules',
                'storage',
            ]
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }
}
