<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

use function Termwind\{render};

class GitHubActionsCommand extends Command
{
    protected $signature = 'github-actions';

    protected $description = 'Publish GitHub Actions';

    public function handle(): int
    {
        $branch = $this->anticipate('What is the name of your primary branch?', ['main', 'develop', 'master'], 'main');
        $commit = $this->choice('Would you like the action to automatically commit fixes?', ['yes', 'no'], 1);

        $workflowName = $commit === 'yes' ? 'duster-fix' : 'duster-lint';

        $workflow = file_get_contents(__DIR__ . "/../../stubs/github-actions/{$workflowName}.yml");
        $workflow = str_replace('YOUR_BRANCH_NAME', $branch, $workflow);

        if (! is_dir(getcwd() . '/.github/workflows')) {
            mkdir(getcwd() . '/.github/workflows', 0777, true);
        }

        file_put_contents(getcwd() . "/.github/workflows/{$workflowName}.yml", $workflow);

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
