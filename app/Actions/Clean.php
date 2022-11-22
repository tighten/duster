<?php

namespace App\Actions;

use App\Support\DusterConfig;
use Illuminate\Console\Command;

class Clean
{
    /**
     * @param  array<int, string>  $tools
     */
    public function __construct(
        protected string $mode,
        protected array $tools,
        protected DusterConfig $dusterConfig,
    ) {
    }

    public function execute(): int
    {
        $success = collect($this->tools)
            ->filter(fn ($tool) => (new $tool($this->dusterConfig))->{$this->mode}())->isEmpty();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
