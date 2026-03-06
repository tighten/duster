<?php

namespace App\Providers;

use App\Project;
use App\Support\DusterConfig;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\InputInterface;

class DusterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DusterConfig::class, function () {
            $input = $this->app->get(InputInterface::class);

            $mode = match ($input->getArgument('command')) {
                'lint' => 'lint',
                'fix' => 'fix',
                default => 'other',
            };

            $dusterConfig = DusterConfig::loadLocal();
            $excludeFromCli = array_filter(explode(',', (string) $input->getOption('exclude')));

            return new DusterConfig([
                'paths' => Project::paths($input),
                'using' => $input->getOption('using'),
                'mode' => $mode,
                'include' => $dusterConfig['include'] ?? [],
                'exclude' => array_merge($dusterConfig['exclude'] ?? [], $excludeFromCli),
                'scripts' => $dusterConfig['scripts'] ?? [],
            ]);
        });
    }
}
