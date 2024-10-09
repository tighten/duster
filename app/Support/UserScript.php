<?php

namespace App\Support;

use App\Contracts\Tool;
use Illuminate\Console\Command;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
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
        parent::__construct($dusterConfig);
    }

    public function lint(): int
    {
        $this->heading('Linting using ' . $this->name);

        return $this->process();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     */
    public function fix(): int
    {
        $this->heading('Fixing using ' . $this->name);

        return $this->process();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     */
    private function process(): int
    {
        $dusterConfig = DusterConfig::loadLocal();

        $process = new Process($this->command);
        $process->setTimeout($dusterConfig['processTimeout'] ?? 60);
        $output = app()->get(OutputInterface::class);

        try {
            $process->run(fn ($type, $buffer) => $output->write($buffer));

            return $process->getExitCode();
        } catch (ProcessTimedOutException $e) {
            $this->failure($e->getMessage() . '<br />You can overwrite this timeout with the processTimeout key in your duster.json file.');

            return Command::FAILURE;
        }
    }
}
