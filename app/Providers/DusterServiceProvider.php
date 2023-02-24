<?php

namespace App\Providers;

use App\Actions\Clean;
use App\Commands\DusterCommand;
use App\Project;
use App\Support\DusterConfig;
use App\Support\PhpCodeSniffer;
use App\Support\PhpCsFixer;
use App\Support\Pint;
use App\Support\TLint;
use App\Support\Tool;
use App\Support\UserScript;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Input\InputInterface;

class DusterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DusterConfig::class, function () {
            $input = $this->app->get(InputInterface::class);

            $dusterConfig = $this->getDusterConfig();

            return new DusterConfig([
                'paths' => $input->getArgument('path'),
                'lint' => $input->getOption('lint'),
                'fix' => $input->getOption('fix'),
                'using' => $input->getOption('using'),
                'include' => $dusterConfig['include'] ?? [],
                'exclude' => $dusterConfig['exclude'] ?? [],
                'scripts' => $dusterConfig['scripts'] ?? [],
            ]);
        });

        $this->app->bindMethod([DusterCommand::class, 'handle'], function ($command) {
            $input = $this->app->get(InputInterface::class);

            $mode = $input->getOption('fix') ? 'fix' : 'lint';

            $using = $input->getOption('using')
                ? explode(',', $input->getOption('using'))
                : ['tlint', 'phpcs', 'php-cs-fixer', 'pint', ...array_keys($this->getDusterConfig()['scripts'][$mode] ?? [])];

            $tools = collect($using)
                ->map(fn ($using): Tool => match (trim($using)) {
                    'tlint' => resolve(TLint::class),
                    'phpcs', 'phpcodesniffer', 'php-code-sniffer' => resolve(PhpCodeSniffer::class),
                    'php-cs-fixer', 'phpcsfixer' => resolve(PhpCsFixer::class),
                    'pint' => resolve(Pint::class),
                    default => $this->userScript($mode, $using),
                })
                ->filter()
                ->unique()
                ->toArray();

            return $command->handle(
                new Clean(
                    mode: $mode,
                    tools: $tools
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
            return tap(json_decode(file_get_contents(Project::path() . '/duster.json'), true, 512, JSON_THROW_ON_ERROR), function ($configuration) {
                if (! is_array($configuration)) {
                    abort(1, 'The configuration file duster.json is not valid JSON.');
                }
            });
        }

        return [];
    }

    private function userScript(string $mode, string $scriptName): ?UserScript
    {
        $userScript = $this->getDusterConfig()['scripts'][$mode][$scriptName] ?? null;

        return $userScript
            ? new UserScript($scriptName, $userScript, resolve(DusterConfig::class))
            : null;
    }
}
