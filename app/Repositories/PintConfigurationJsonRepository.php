<?php

namespace App\Repositories;

use App\Support\DusterConfig;

class PintConfigurationJsonRepository extends ConfigurationJsonRepository
{
    public function __construct(
        protected $path,
        protected $preset,
        protected DusterConfig $dusterConfig)
    {
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function get(): array
    {
        $config = $this->getPintConfig();

        collect($this->dusterConfig->get('exclude', []))
            ->each(function ($path) use (&$config) {
                $config = $this->addPathToConfig($path, $config);
            });

        return $config;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function getPintConfig(): array
    {
        if (file_exists((string) $this->path)) {
            return tap(json_decode(file_get_contents($this->path), true), function ($configuration) {
                if (! is_array($configuration)) {
                    abort(1, sprintf('The configuration file [%s] is not valid JSON.', $this->path));
                }
            });
        }

        return [];
    }

    /**
     * @param  array<string, array<int, string>|string>  $config
     * @return  array<string, array<int, string>|string>
     */
    protected function addPathToConfig(string $path, array $config): array
    {
        if (is_dir($path)) {
            $config['exclude'][] = $path;
        } elseif (is_file($path)) {
            $config['notPath'][] = $path;
        }

        return $config;
    }
}
