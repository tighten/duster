<?php

namespace App\Commands;

use App\Concerns\ConfiguresForLintOrFix;
use App\Concerns\GetsCleaner;
use Exception;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Exceptions\ConsoleException;

class LintCommand extends Command
{
    use ConfiguresForLintOrFix;
    use GetsCleaner;

    protected $signature = 'lint';

    protected $description = 'Lint your code';

    public function handle(): int
    {
        try {
            $clean = $this->getCleaner('lint', $this->input->getOption('using'));

            return $clean->execute();
        } catch (ConsoleException $exception) {
            $this->error($exception->getMessage());

            return $exception->getCode();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
