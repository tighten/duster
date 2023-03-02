<?php

namespace App\Commands;

use App\Support\ConfiguresForLintOrFix;
use App\Support\GetsCleaner;
use LaravelZero\Framework\Commands\Command;

class LintCommand extends Command
{
    use GetsCleaner;
    use ConfiguresForLintOrFix;

    protected $signature = 'lint';

    protected $description = 'Lint your code';

    public function handle(): int
    {
        $clean = $this->getCleaner('lint', $this->input->getOption('using'));

        return $clean->execute();
    }
}
