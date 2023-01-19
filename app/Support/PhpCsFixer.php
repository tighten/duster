<?php

namespace App\Support;

use App\Actions\ElaborateSummary;
use App\Project;
use ArrayIterator;
use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\ConfigurationException\InvalidConfigurationException;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Runner;
use PhpCsFixer\ToolInfo;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PhpCsFixer extends Tool
{
    public static function getFinder(): Finder
    {
        return Finder::create()
            ->notName([
                '*.blade.php',
            ])
            ->ignoreDotFiles(true)
            ->ignoreVCS(true);
    }

    public function lint(): int
    {
        $this->heading('Linting using PHP CS Fixer');

        return $this->process();
    }

    public function fix(): int
    {
        $this->heading('Fixing using PHP CS Fixer');

        return $this->process();
    }

    private function process(): int
    {
        $output = app()->get(OutputInterface::class);

        $resolver = new ConfigurationResolver(
            $this->getConfig(),
            [
                'config' => $this->getConfigFilePath(),
                'allow-risky' => 'yes',
                'diff' => $output->isVerbose(),
                'dry-run' => $this->dusterConfig->get('lint'),
                'path' => $this->dusterConfig->get('paths'),
                'path-mode' => ConfigurationResolver::PATH_MODE_OVERRIDE,
                'stop-on-violation' => false,
                'verbosity' => $output->getVerbosity(),
                'show-progress' => 'true',
            ],
            Project::path(),
            new ToolInfo,
        );

        $changes = (new Runner(
            $this->getConfig()->getFinder(),
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

    private function getConfig(): ConfigInterface
    {
        $config = $this->includeConfig();

        if (! $config instanceof ConfigInterface) {
            throw new InvalidConfigurationException("The PHP CS Fixer config file does not return a 'PhpCsFixer\ConfigInterface' instance.");
        }

        return $config->setFinder($this->updateFinder($config->getFinder()));
    }

    /**
     * Update the finder with the paths and exclude from the config.
     * We are bypassing resolveFinder() in ConfigurationResolver
     * to allow for us to use the global duster config.
     */
    private function updateFinder(Finder $finder): Finder
    {
        collect($this->dusterConfig->get('paths', []))->each(function ($path) use ($finder) {
            if (is_dir($path)) {
                $finder = $finder->in($path);
            } elseif (is_file($path)) {
                $finder = $finder->append([$path]);
            }
        });

        collect($this->dusterConfig->get('exclude', []))->each(function ($path) use ($finder) {
            if (is_dir($path)) {
                $finder = $finder->exclude($path);
            } elseif (is_file($path)) {
                $finder = $finder->notPath($path);
            }
        });

        return $finder;
    }

    private function includeConfig(): Config
    {
        return include $this->getConfigFilePath();
    }

    private function getConfigFilePath(): string
    {
        return (string) collect([
            Project::path() . '/.php-cs-fixer.dist.php',
            Project::path() . '/.php-cs-fixer.php',
            base_path('standards/.php-cs-fixer.dist.php'),
        ])->first(fn ($path) => file_exists($path));
    }
}
