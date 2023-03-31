<?php

namespace App\Support;

use App\Project;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class DusterConfig
{
    /**
     * @param  array<string, array<int, string>|string>  $config
     */
    public function __construct(
        protected array $config = []
    ) {
        $this->config['includes'] = $this->expandWildcards($this->config['includes'] ?? []);

        $this->config['exclude'] = array_merge(
            $this->expandWildcards($this->config['exclude'] ?? []),
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

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * @param  array<int, string>  $paths
     * @return  array<int, string>
     */
    public function expandWildcards(array $paths): array
    {
        return collect($paths)->flatMap(function ($path) {
            if (Str::contains($path, '*')) {
                $finder = new Finder;
                $finder->ignoreUnreadableDirs()->in($path);

                return collect($finder)->keys();
            }

            return [$path];
        })->toArray();
    }
}
