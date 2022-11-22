<?php

namespace App\Providers;

use App\Actions\Clean;
use App\Commands\DusterCommand;
use App\Support\DusterConfig;
use App\Support\PhpCodeSniffer;
use App\Support\PhpCsFixer;
use App\Support\Pint;
use App\Support\Project;
use App\Support\TLint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;

class DusterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DusterConfig::class, function () {
            $input = $this->app->get(InputInterface::class);

            return new DusterConfig(['paths' => $input->getArgument('path'), ...$this->getDusterConfig()]);
        });

        $this->app->bindMethod([DusterCommand::class, 'handle'], function ($command) {
            $input = $this->app->get(InputInterface::class);

            $mode = ! $input->getOption('fix') ? 'lint' : 'fix';

            $tools = Str::of($input->getOption('using') ?? 'tlint,phpcs,php-cs-fixer,pint')
                ->explode(',')
                ->collect()
                ->map(fn ($using) => match (trim($using)) {
                    'tlint' => TLint::class,
                    'phpcs', 'phpcodesniffer', 'php-code-sniffer' => PhpCodeSniffer::class,
                    'php-cs-fixer', 'phpcsfixer' => PhpCsFixer::class,
                    'pint' => Pint::class,
                    default => null,
                })
                ->filter()
                ->unique()
                ->toArray();

            return $command->handle(
                new Clean(
                    mode: $mode,
                    tools: $tools,
                    dusterConfig: resolve(DusterConfig::class),
                ),
            );
        });
    }

    /**
     * @return  array<string, mixed>
     */
    private function getDusterConfig(): array
    {
        if (file_exists(Project::path() . '/duster.json')) {
            return tap(json_decode(file_get_contents(Project::path() . '/duster.json'), true), function ($configuration) {
                if (! is_array($configuration)) {
                    abort(1, 'The configuration file duster.json is not valid JSON.');
                }
            });
        }

        return [];
    }
}
