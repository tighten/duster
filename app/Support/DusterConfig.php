<?php

namespace Tighten\Duster\Support;

use App\Project;
use Illuminate\Support\Arr;

class DusterConfig
{
    /**
     * @param  array<string, array<int, string>|string>  $config
     */
    public function __construct(
        protected array $config = []
    ) {
        $this->config = static::scopeConfigPaths($this->config);
    }

    /**
     * @return  array<string, mixed>
     */
    public static function loadLocal(): array
    {
        if (file_exists(Project::path() . '/duster.json')) {
            return tap(json_decode(file_get_contents(Project::path() . '/duster.json'), true, 512, JSON_THROW_ON_ERROR), function ($configuration) {
                if (! is_array($configuration)) {
                    abort(1, 'The configuration file duster.json is not valid JSON.');
                }
            });
        }

        return [];
    }

    /**
     * @param  array<string, array<int, string>|string>  $config
     * @return  array<string, array<int, string>|string>
     */
    public static function scopeConfigPaths(array $config): array
    {
        $config['include'] = static::expandWildcards($config['include'] ?? []);

        $config['exclude'] = array_merge(
            static::expandWildcards($config['exclude'] ?? []),
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

        return $config;
    }

    /**
     * @param  array<int, string>  $paths
     * @return  array<int, string>
     */
    public static function expandWildcards(array $paths): array
    {
        return collect($paths)->flatMap(function ($path) {
            return collect(glob($path, GLOB_NOCHECK))
                ->filter(fn ($path) => file_exists($path))
                ->all();
        })->toArray();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }
}
