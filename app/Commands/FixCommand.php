<?php

namespace Tighten\Duster\Commands;

use Exception;
use LaravelZero\Framework\Commands\Command;
use LaravelZero\Framework\Exceptions\ConsoleException;
use Tighten\Duster\Support\ConfiguresForLintOrFix;
use Tighten\Duster\Support\GetsCleaner;

class FixCommand extends Command
{
    use ConfiguresForLintOrFix;
    use GetsCleaner;

    protected $signature = 'fix';

    protected $description = 'Fix your code';

    public function handle(): int
    {
        try {
            $clean = $this->getCleaner('fix', $this->input->getOption('using'));

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
