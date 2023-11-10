<?php

namespace Tighten\Duster\Actions;

use Illuminate\Console\Command;
use Tighten\Duster\Support\Tool;

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
