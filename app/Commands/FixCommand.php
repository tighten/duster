<?php

namespace App\Commands;

use App\Support\ConfiguresForLintOrFix;
use App\Support\GetsCleaner;
use LaravelZero\Framework\Commands\Command;

class FixCommand extends Command
{
    use GetsCleaner;
    use ConfiguresForLintOrFix;

    protected $signature = 'fix';

    protected $description = 'Fix your code';

    public function handle(): int
    {
        $clean = $this->getCleaner('fix', $this->input->getOption('using'));

        return $clean->execute();
    }
}
