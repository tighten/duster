<?php

namespace Tighten\Duster\Commands;

use Exception;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Exceptions\ConsoleException;
use Tighten\Duster\Support\ConfiguresForLintOrFix;
use Tighten\Duster\Support\GetsCleaner;

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

            return 1;
        }
    }
}
