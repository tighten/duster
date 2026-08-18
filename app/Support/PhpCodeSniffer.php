<?php

namespace App\Support;

use App\Contracts\Tool;
use App\Project;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Runner;
use Symfony\Component\Console\Output\OutputInterface;

class PhpCodeSniffer extends Tool
{
    public function lint(): int
    {
        $this->heading('Linting using PHP_CodeSniffer');

        $paths = $this->resolvePaths();

        if ($paths === null) {
            return 0;
        }

        return $this->process('runPHPCS', $paths);
    }

    public function fix(): int
    {
        $this->heading('Fixing using PHP_CodeSniffer');

        $paths = $this->resolvePaths();

        if ($paths === null) {
            return 0;
        }

        $fix = $this->process('runPHPCBF', $paths);

        $lint = $this->process('runPHPCS', ['-n', '--report=summary', ...$paths]);

        if ($lint !== 0) {
            $this->failure('PHP Code_Sniffer found errors that cannot be fixed automatically. Run `duster lint` for details.');
        }

        return $fix || $lint ? 1 : 0;
    }

    /**
     * @param  array<int, string>  $params
     */
    private function process(string $tool, array $params = []): int
    {
        $serverArgv = $_SERVER['argv'];

        if (defined('PHP_CODESNIFFER_CBF') === false) {
            define('PHP_CODESNIFFER_CBF', $tool === 'runPHPCBF');
        }

        $ignore = $this->dusterConfig->get('exclude')
            ? ['--ignore=' . implode(',',
                array_map(fn ($path) => str_contains($path, getcwd()) ? $path : getcwd() . '/*' . $path, $this->dusterConfig->get('exclude')))]
            : [];

        $_SERVER['argv'] = [
            'Duster',
            '--standard=' . $this->getConfigFile(),
            ...$ignore,
            ...$params,
        ];

        $this->installTightenCodingStandard();

        $this->resetConfig();

        $runner = new Runner;

        ob_start();

        $exitCode = $runner->$tool();

        app()->get(OutputInterface::class)->write(ob_get_contents());

        ob_end_clean();

        $_SERVER['argv'] = $serverArgv;

        return $exitCode;
    }

    /**
     * Resolve the paths to hand to PHP_CodeSniffer.
     *
     * Returns:
     *   - `null`           when PHPCS should be skipped entirely (e.g. only Blade
     *                      files were passed explicitly, or there is nothing to lint).
     *   - An empty array   when PHPCS should fall back to the project's custom
     *                      ruleset (custom config present and no explicit paths).
     *   - A list of paths  otherwise.
     *
     * @return array<int, string>|null
     */
    private function resolvePaths(): ?array
    {
        if ($this->hasExplicitPaths()) {
            $paths = $this->filterBladeFiles($this->dusterConfig->get('paths'));

            return empty($paths) ? null : $paths;
        }

        if ($this->hasCustomConfig()) {
            return [];
        }

        $paths = $this->filterBladeFiles($this->getDefaultDirectories());

        return empty($paths) ? null : $paths;
    }

    private function hasExplicitPaths(): bool
    {
        return $this->dusterConfig->get('paths') !== [Project::path()];
    }

    /**
     * @param  array<int, string>  $paths
     * @return array<int, string>
     */
    private function filterBladeFiles(array $paths): array
    {
        return array_values(array_filter($paths, function ($path) {
            if (is_dir($path)) {
                return true;
            }

            return ! str_ends_with($path, '.blade.php');
        }));
    }

    private function hasCustomConfig(): bool
    {
        return $this->getConfigFile() !== 'Tighten';
    }

    private function installTightenCodingStandard(): void
    {
        (new Config)->setConfigData('installed_paths', base_path('standards/Tighten'), true);
    }

    /**
     * Config uses a private static property $overriddenDefaults
     * which doesn't allow us to update the config between runs
     * we need to reset it so we can also lint in the fix command.
     */
    private function resetConfig(): void
    {
        invade(new Config)->overriddenDefaults = [];
    }

    private function getConfigFile(): string
    {
        return match (true) {
            file_exists(Project::path() . '/.phpcs.xml') => Project::path() . '/.phpcs.xml',
            file_exists(Project::path() . '/phpcs.xml') => Project::path() . '/phpcs.xml',
            file_exists(Project::path() . '/.phpcs.xml.dist') => Project::path() . '/.phpcs.xml.dist',
            file_exists(Project::path() . '/phpcs.xml.dist') => Project::path() . '/phpcs.xml.dist',
            default => 'Tighten',
        };
    }

    /**
     * @return array<int, string>
     */
    private function getDefaultDirectories(): array
    {
        return array_filter(
            [
                Project::path() . '/app',
                Project::path() . '/config',
                Project::path() . '/database',
                Project::path() . '/public',
                Project::path() . '/resources',
                Project::path() . '/routes',
                Project::path() . '/tests',
                ...$this->dusterConfig->get('include', []),
            ],
            fn ($dir) => is_dir($dir)
        ) ?: [Project::path()];
    }
}
