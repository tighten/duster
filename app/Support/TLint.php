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
    public function lint($paths): int
    {
        $this->heading('Linting using TLint');

        return $this->process('lint', $paths);
    }

    public function fix($paths): int
    {
        $this->heading('Fixing using TLint');

        return $this->process('format', $paths);
    }

    private function process($command, array $paths = []): int
    {
        $application = new Application();
        $application->add(new LintCommand);
        $application->add(new FormatCommand);
        $application->setAutoExit(false);

        $success = collect($paths)->map(fn ($path) => $application->run(new StringInput("{$command} {$path}"), app()->get(OutputInterface::class)))
            ->filter()
            ->isEmpty();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
