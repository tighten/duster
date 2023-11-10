<?php

namespace Tighten\Duster\Providers;

use App\Actions\ElaborateSummary;
use App\Actions\FixCode;
use App\Commands\DefaultCommand;
use App\Contracts\PathsRepository;
use App\Output\ProgressOutput;
use App\Output\SummaryOutput;
use App\Project;
use App\Repositories\ConfigurationJsonRepository;
use App\Repositories\GitPathsRepository;
use Illuminate\Support\ServiceProvider;
use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tighten\Duster\Contracts\PintInputInterface;
use Tighten\Duster\Repositories\PintConfigurationJsonRepository;
use Tighten\Duster\Support\DusterConfig;

class PintServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ErrorsManager::class, fn () => new ErrorsManager);

        $this->app->singleton(EventDispatcher::class, fn () => new EventDispatcher);

        $this->app->singleton(PintInputInterface::class, function () {
            $input = $this->app->get(InputInterface::class);

            return new ArrayInput(
                ['--test' => $input->getArgument('command') === 'lint', 'path' => Project::paths($input)],
                resolve(DefaultCommand::class)->getDefinition()
            );
        });

        $this->app->singleton(FixCode::class, fn () => new FixCode(
            resolve(ErrorsManager::class),
            resolve(EventDispatcher::class),
            resolve(PintInputInterface::class),
            resolve(OutputInterface::class),
            new ProgressOutput(
                resolve(EventDispatcher::class),
                resolve(PintInputInterface::class),
                resolve(OutputInterface::class),
            )
        ));

        $this->app->singleton(ElaborateSummary::class, fn () => new ElaborateSummary(
            resolve(ErrorsManager::class),
            resolve(PintInputInterface::class),
            resolve(OutputInterface::class),
            new SummaryOutput(
                resolve(ConfigurationJsonRepository::class),
                resolve(ErrorsManager::class),
                resolve(PintInputInterface::class),
                resolve(OutputInterface::class),
            )
        ));

        $this->app->singleton(ConfigurationJsonRepository::class, function () {
            $config = (string) collect([
                Project::path() . '/pint.json',
                base_path('standards/pint.json'),
            ])->first(fn ($path) => file_exists($path));

            $dusterConfig = DusterConfig::scopeConfigPaths(DusterConfig::loadLocal());

            return new PintConfigurationJsonRepository($config, null, $dusterConfig['exclude']);
        });

        $this->app->singleton(PathsRepository::class, function () {
            return new GitPathsRepository(
                Project::path(),
            );
        });
    }
}
