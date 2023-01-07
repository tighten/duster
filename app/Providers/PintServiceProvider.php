<?php

namespace App\Providers;

use App\Actions\ElaborateSummary;
use App\Actions\FixCode;
use App\Commands\DefaultCommand;
use App\Contracts\PintInputInterface;
use App\Output\ProgressOutput;
use App\Output\SummaryOutput;
use App\Repositories\ConfigurationJsonRepository;
use App\Repositories\PintConfigurationJsonRepository;
use App\Support\DusterConfig;
use App\Support\Project;
use Illuminate\Support\ServiceProvider;
use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PintServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ErrorsManager::class, function () {
            return new ErrorsManager;
        });

        $this->app->singleton(EventDispatcher::class, function () {
            return new EventDispatcher;
        });

        $this->app->singleton(PintInputInterface::class, function () {
            $input = $this->app->get(InputInterface::class);

            return new ArrayInput(
                ['--test' => ! $input->getOption('fix'), 'path' => $input->getArgument('path')],
                resolve(DefaultCommand::class)->getDefinition()
            );
        });

        $this->app->singleton(FixCode::class, function () {
            return new FixCode(
                resolve(ErrorsManager::class),
                resolve(EventDispatcher::class),
                resolve(PintInputInterface::class),
                resolve(OutputInterface::class),
                new ProgressOutput(
                    resolve(EventDispatcher::class),
                    resolve(PintInputInterface::class),
                    resolve(OutputInterface::class),
                )
            );
        });

        $this->app->singleton(ElaborateSummary::class, function () {
            return new ElaborateSummary(
                resolve(ErrorsManager::class),
                resolve(PintInputInterface::class),
                resolve(OutputInterface::class),
                new SummaryOutput(
                    resolve(ConfigurationJsonRepository::class),
                    resolve(ErrorsManager::class),
                    resolve(PintInputInterface::class),
                    resolve(OutputInterface::class),
                )
            );
        });

        $this->app->singleton(ConfigurationJsonRepository::class, function () {
            $config = (string) collect([
                Project::path() . '/pint.json',
                base_path('standards/pint.json'),
            ])->first(function ($path) {
                return file_exists($path);
            });

            return new PintConfigurationJsonRepository($config, null, resolve(DusterConfig::class));
        });
    }
}
