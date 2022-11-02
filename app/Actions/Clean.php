<?php

namespace App\Actions;

use App\Support\PhpCodeSniffer;
use App\Support\PhpCsFixer;
use App\Support\Pint;
use App\Support\TLint;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Clean
{
    public function __construct(
        protected string $mode = 'lint',
        protected array $paths = [],
        protected array $tools = [
            TLint::class,
            PhpCodeSniffer::class,
            PhpCsFixer::class,
            Pint::class,
        ],
    ) {
    }

    public function mode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function for(array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    public function using(string $tools): self
    {
        $tools = Str::of($tools)
            ->explode(',')
            ->collect()
            ->map(fn ($using) => match (trim($using)) {
                'tlint' => TLint::class,
                'phpcs', 'phpcodesniffer', 'php-code-sniffer' => PhpCodeSniffer::class,
                'php-cs-fixer', 'phpcsfixer' => PhpCsFixer::class,
                'pint' => Pint::class,
                default => null,
            })
            ->filter()
            ->unique()
            ->toArray();

        $this->tools = $tools ?: $this->tools;

        return $this;
    }

    public function execute(): int
    {
        $success = collect($this->tools)->filter(fn ($tool) => (new $tool)->{$this->mode}($this->paths))->isEmpty();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
