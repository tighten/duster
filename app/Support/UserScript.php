<?php

namespace App\Support;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class UserScript extends Tool
{
    /**
     * @param  array<int, string>  $command
     */
    public function __construct(
        protected string $name,
        protected array $command,
        protected DusterConfig $dusterConfig,
    ) {
    }

    public function lint(): int
    {
        $this->heading('Linting using ' . $this->name);

        return $this->process();
    }

    public function fix(): int
    {
        $this->heading('Fixing using ' . $this->name);

        return $this->process();
    }

    private function process(): int
    {
        $process = new Process($this->command);
        $output = app()->get(OutputInterface::class);

        $process->run(fn ($type, $buffer) => $output->write($buffer));

        return $process->getExitCode();
    }
}
