<?php

namespace App\Commands;

use App\Concerns\CommandHelpers;
use LaravelZero\Framework\Commands\Command;
use RuntimeException;
use Symfony\Component\Process\Process;

class HuskyHooksCommand extends Command
{
    use CommandHelpers;

    protected $signature = 'husky-hooks';

    protected $description = 'Publish Husky Hooks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $choices = [
            'Lint only' => 'lint',
            'Fix and commit' => 'fix',
        ];

        $choice = $this->choice('Which Husky hook would you like?', array_keys($choices), 0);

        $lintStagedConfigFile = $choices[$choice];

        $this->components->info('Installing and building Node dependencies.');

        $this->runCommands(['git init']);

        if (! file_exists(base_path('node_modules/husky'))) {
            $this->runCommands(['npx husky-init']);
        }

        if (! file_exists(base_path('node_modules/lint-staged'))) {
            $this->runCommands(['npm install lint-staged --save-dev']);
        }

        $preCommit = file_get_contents(__DIR__ . '/../../stubs/husky-hooks/pre-commit');

        $lintStaged = file_get_contents(__DIR__ . "/../../stubs/husky-hooks/duster-{$lintStagedConfigFile}.js");

        if (! is_dir(getcwd() . '/.husky')) {
            mkdir(getcwd() . '/.husky', 0777, true);
        }

        file_put_contents(getcwd() . '/.husky/pre-commit', $preCommit);

        file_put_contents(getcwd() . '/lint-staged.config.js', $lintStaged);

        $this->runCommands(["npx husky add ./.husky/pre-commit 'npx --no-install lint-staged'"]);

        $this->success('Husky Pre-Commit Git Hook added');

        return Command::SUCCESS;
    }

    /**
     * Run the given commands.
     *
     * @param  array<string>  $commands
     */
    protected function runCommands(array $commands): void
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }
}
