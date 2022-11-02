<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use function Termwind\{render};

class DusterCommand extends Command
{
    protected $signature = 'duster';

    protected $description = 'Clean up your code';

    /**
     * @param  \App\Actions\Clean  $clean
     */
    public function handle($clean): int
    {
        if ($this->input->getOption('github-actions')) {
            return $this->gitHubActions();
        }

        if ($this->input->getOption('lint') || ! ($this->input->getOption('fix') || $this->input->getOption('github-actions'))) {
            $this->input->setOption('lint', true);
        }

        if ($this->input->getOption('lint')) {
            return $clean->mode('lint')
                ->for($this->input->getArgument('path'))
                ->using($this->input->getOption('using') ?? '')
                ->execute();
        }

        if ($this->input->getOption('fix')) {
            return $clean->mode('fix')
                ->for($this->input->getArgument('path'))
                ->using($this->input->getOption('using') ?? '')
                ->execute();
        }

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDefinition(
                [
                    new InputArgument(
                        name: 'path',
                        mode: InputArgument::IS_ARRAY,
                        default:[(string) getcwd()],
                        description: 'The path to fix',
                    ),
                    new InputOption(
                        name: 'lint',
                        shortcut: 'l',
                        mode: InputOption::VALUE_NONE,
                        description: 'Lint using all tools',
                    ),
                    new InputOption(
                        name: 'fix',
                        shortcut: 'f',
                        mode: InputOption::VALUE_NONE,
                        description: 'Fix using all tools',
                    ),
                    new InputOption(
                        name: 'using',
                        shortcut: 'u',
                        mode: InputOption::VALUE_REQUIRED,
                        description: 'Lint/Fix using specified (comma separated) tools: tlint,phpcodesniffer,phpcsfixer,pint',
                    ),
                    new InputOption(
                        name: 'github-actions',
                        shortcut: 'g',
                        mode: InputOption::VALUE_NONE,
                        description: 'Publish GitHub Actions',
                    ),
                ]
            );
    }

    private function gitHubActions(): int
    {
        $branch = $this->anticipate('What is the name of your primary branch?', ['main', 'develop', 'master'], 'main');
        $phpVersion = $this->anticipate('What PHP version do you want to use?', ['8.1', '8.0'], '8.1');

        $workflow = file_get_contents(__DIR__ . '/../../stubs/github-actions/lint.yml');

        $workflow = str_replace(
            ['YOUR_BRANCH_NAME', 'YOUR_PHP_VERSION'],
            [$branch, $phpVersion],
            $workflow
        );

        if (! is_dir(getcwd() . '/.github/workflows')) {
            mkdir(getcwd() . '/.github/workflows', 0777, true);
        }

        file_put_contents(getcwd() . '/.github/workflows/lint.yml', $workflow);

        $this->success('GitHub Actions added');

        return Command::SUCCESS;
    }

    private function success(string $message): void
    {
        render(<<<HTML
            <div class="py-1 ml-2">
                <div class="px-1 bg-green-300 text-black">Success</div>
                <em class="ml-1">
                {$message}
                </em>
            </div>
        HTML);
    }
}
