<?php

namespace App\Support;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Runner;
use Symfony\Component\Console\Output\OutputInterface;

class PhpCodeSniffer extends Tool
{
    public function lint(array $paths): int
    {
        $this->heading('Linting using PHP_CodeSniffer');

        return $this->process('runPHPCS', $this->cleanPaths($paths));
    }

    public function fix(array $paths): int
    {
        $this->heading('Fixing using PHP_CodeSniffer');

        $fix = $this->process('runPHPCBF', $this->cleanPaths($paths));

        $lint = $this->process('runPHPCS', ['-n', '--report=summary', ...$this->cleanPaths($paths)]);

        if ($lint) {
            $this->failure('PHP Code_Sniffer found errors that cannot be fixed automatically.');
        }

        return $fix;
    }

    /**
     * @param string $tool
     * @param array<int, string> $params
     */
    private function process(string $tool, array $params = []): int
    {
        $serverArgv = $_SERVER['argv'];

        $this->installTightenCodingStandard();

        $_SERVER['argv'] = ['Duster', '--standard=' . $this->getConfigFile(), ...$params];

        $runner = new Runner();

        ob_start();

        $exitCode = $runner->$tool();

        app()->get(OutputInterface::class)->write(ob_get_contents());

        ob_end_clean();

        $_SERVER['argv'] = $serverArgv;

        return $exitCode;
    }

    /**
     * @param array<int, string> $paths
     *
     * @return array<int, string>
     */
    private function cleanPaths(array $paths): array
    {
        return $paths === [Project::path()] ? $this->getDefaultDirectories() : $paths;
    }

    private function installTightenCodingStandard(): void
    {
        Config::setConfigData('installed_paths', base_path('standards/Tighten'), true);
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
            ],
            fn ($dir) => is_dir($dir)
        ) ?: [Project::path()];
    }
}
