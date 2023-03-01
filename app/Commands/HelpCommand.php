<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class HelpCommand extends Command
{
    protected $signature = 'commands';

    protected $description = 'Learn about Duster commands';

    public function handle(): int
    {
        return $this->call('list');
    }
}
