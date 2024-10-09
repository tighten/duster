<?php

namespace App\Concerns;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

trait ConfiguresForLintOrFix
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDefinition(
                [
                    new InputArgument(
                        name: 'path',
                        mode: InputArgument::IS_ARRAY,
                        description: 'The path to lint/fix',
                        default: [(string) getcwd()],
                    ),
                    new InputOption(
                        name: 'using',
                        shortcut: 'u',
                        mode: InputOption::VALUE_REQUIRED,
                        description: 'Lint/fix using specified (comma separated) tools: tlint,phpcodesniffer,phpcsfixer,pint',
                    ),
                    new InputOption(
                        name: 'dirty',
                        mode: InputOption::VALUE_NONE,
                        description: 'Only lint/fix files that have uncommitted changes'
                    ),
                ]
            );
    }
}
