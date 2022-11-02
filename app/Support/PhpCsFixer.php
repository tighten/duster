<?php

namespace App\Support;

use App\Actions\ElaborateSummary;
use ArrayIterator;
use PhpCsFixer\Config;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Runner;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PhpCsFixer extends Tool
{
    public static function getFinder(): Finder
    {
        return Finder::create()
            ->notName([
                '_ide_helper_actions.php',
                '_ide_helper_models.php',
                '_ide_helper.php',
                '.phpstorm.meta.php',
                '*.blade.php',
            ])
            ->exclude([
                'bootstrap/cache',
                'build',
                'node_modules',
                'storage',
            ])
            ->ignoreDotFiles(true)
            ->ignoreVCS(true);
    }

    public function lint(array $paths): int
    {
        $this->heading('Linting using PHP CS Fixer');

        return $this->process($paths);
    }

    public function fix(array $paths): int
    {
        $this->heading('Fixing using PHP CS Fixer');

        return $this->process($paths);
    }

    /**
     * @param array<int, string> $paths
     */
    private function process(array $paths = []): int
    {
        $input = app()->get(InputInterface::class);
        $output = app()->get(OutputInterface::class);

        $resolver = new ConfigurationResolver(
            new Config(),
            [
                'allow-risky' => 'yes',
                'config' => $this->getConfigFile(),
                'diff' => $output->isVerbose(),
                'dry-run' => $input->getOption('lint'),
                'path' => $paths,
                'path-mode' => ConfigurationResolver::PATH_MODE_OVERRIDE,
                'stop-on-violation' => false,
                'verbosity' => $output->getVerbosity(),
                'show-progress' => 'true',
            ],
            Project::path(),
            new ToolInfo(),
        );

        $changes = (new Runner(
            $resolver->getFinder(),
            $resolver->getFixers(),
            $resolver->getDiffer(),
            app()->get(EventDispatcher::class),
            app()->get(ErrorsManager::class),
            $resolver->getLinter(),
            $resolver->isDryRun(),
            $resolver->getCacheManager(),
            $resolver->getDirectory(),
            $resolver->shouldStopOnViolation()
        ))->fix();

        $totalFiles = count(new ArrayIterator(iterator_to_array(
            $resolver->getFinder(),
        )));

        return app()->get(ElaborateSummary::class)->execute($totalFiles, $changes);
    }

    private function getConfigFile(): string
    {
        return (string) collect([
            Project::path() . '/.php-cs-fixer.dist.php',
            Project::path() . '/.php-cs-fixer.php',
            base_path('standards/.php-cs-fixer.dist.php'),
        ])->first(function ($path) {
            return file_exists($path);
        });
    }
}
