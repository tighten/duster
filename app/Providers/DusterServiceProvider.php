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

            $dusterConfig = DusterConfig::all();

            return new DusterConfig([
                'paths' => Project::paths($input),
                'lint' => ! $input->getOption('fix'),
                'fix' => $input->getOption('fix'),
                'using' => $input->getOption('using'),
                'include' => $dusterConfig['include'] ?? [],
                'exclude' => $dusterConfig['exclude'] ?? [],
                'scripts' => $dusterConfig['scripts'] ?? [],
            ]);
        });

        $this->app->bindMethod([DusterCommand::class, 'handle'], function ($command) {
            $input = $this->app->get(InputInterface::class);

            $mode = ! $input->getOption('fix') ? 'lint' : 'fix';

            $using = $input->getOption('using')
                ? explode(',', $input->getOption('using'))
                : ['tlint', 'phpcs', 'php-cs-fixer', 'pint', ...array_keys(DusterConfig::all()['scripts'][$mode] ?? [])];

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

    private function userScript(string $mode, string $scriptName): ?UserScript
    {
        $userScript = DusterConfig::all()['scripts'][$mode][$scriptName] ?? null;

        return $userScript
            ? new UserScript($scriptName, $userScript, resolve(DusterConfig::class))
            : null;
    }
}
