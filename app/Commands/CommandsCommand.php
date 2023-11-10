<?php

namespace Tighten\Duster\Commands;

use LaravelZero\Framework\Commands\Command;

class CommandsCommand extends Command
{
    protected $signature = 'commands';

    protected $description = 'Learn about Duster commands';

    public function handle(): int
    {
        return $this->call('list');
    }
}
