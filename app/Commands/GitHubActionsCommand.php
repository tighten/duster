<?php

namespace App\Commands;

use App\Concerns\CommandHelpers;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class GitHubActionsCommand extends Command
{
    use CommandHelpers;

    protected $signature = 'github-actions';

    protected $description = 'Publish GitHub Actions';

    public function handle(): int
    {
        $choices = [
            'Lint only' => 'duster-lint',
            'Fix and commit' => 'duster-fix',
            'Fix, commit, and update .git-blame-ignore-revs' => 'duster-fix-blame',
        ];

        $branch = $this->anticipate('What is the name of your primary branch?', ['main', 'develop', 'master'], 'main');
        $choice = $this->choice('Which GitHub action would you like?', array_keys($choices), 0);

        $workflowName = $choices[$choice];

        if (Str::contains($workflowName, 'fix')) {
            $this->warn('The resulting commit will stop any currently running workflows and will not trigger another.');
            $this->warn('Checkout Duster\'s documentation for a workaround.');
            if (! $this->confirm('Do you wish to continue?', true)) {
                return Command::FAILURE;
            }
        }

        $workflow = file_get_contents(__DIR__ . "/../../stubs/github-actions/{$workflowName}.yml");
        $workflow = str_replace('YOUR_BRANCH_NAME', $branch, $workflow);

        if (! is_dir(getcwd() . '/.github/workflows')) {
            mkdir(getcwd() . '/.github/workflows', 0777, true);
        }

        file_put_contents(getcwd() . "/.github/workflows/{$workflowName}.yml", $workflow);

        $this->success('GitHub Actions added');

        return Command::SUCCESS;
    }
}
