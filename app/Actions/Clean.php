<?php

namespace App\Actions;

use App\Support\Tool;
use Illuminate\Console\Command;

class Clean
{
    /**
     * @param  array<int, Tool>  $tools
     */
    public function __construct(
        protected string $mode,
        protected array $tools,
    ) {
    }

    public function execute(): int
    {
        $success = collect($this->tools)
            ->filter(fn ($tool) => $tool->{$this->mode}())->isEmpty();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
