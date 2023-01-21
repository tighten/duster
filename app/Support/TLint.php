<?php

namespace App\Support;

use Illuminate\Console\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Tighten\TLint\Commands\FormatCommand;
use Tighten\TLint\Commands\LintCommand;

class TLint extends Tool
{
    public function lint(): int
    {
        $this->heading('Linting using TLint');

        return $this->process('lint');
    }

    public function fix(): int
    {
        $this->heading('Fixing using TLint');

        return $this->process('format');
    }

    private function process(string $command): int
    {
        $tlintCommand = $command === 'lint' ? new LintCommand : new FormatCommand;
        $tlintCommand->config->excluded = [...$tlintCommand->config->excluded ?? [], ...$this->dusterConfig->get('exclude', [])];

        $application = new Application;
        $application->add($tlintCommand);
        $application->setAutoExit(false);

        $success = collect($this->dusterConfig->get('paths'))->map(function ($path) use ($application, $command) {
            $path = str_replace('\\', '\\\\', $path);

            return $application->run(new StringInput("{$command} {$path}"), app()->get(OutputInterface::class));
        })
            ->filter()
            ->isEmpty();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
