<?php

namespace App\Support;

use App\Actions\ElaborateSummary;
use App\Actions\EnsurePrettierIsConfigured;
use App\Actions\FixCode;
use App\Commands\DefaultCommand;
use App\Contracts\Tool;
use Symfony\Component\Console\Input\ArrayInput;

class Pint extends Tool
{
    public function lint(): int
    {
        $this->heading('Linting using Pint');

        return $this->process();
    }

    public function fix(): int
    {
        $this->heading('Fixing using Pint');

        return $this->process();
    }

    private function process(): int
    {
        $defaultCommand = new DefaultCommand;

        $input = new ArrayInput([]);
        $input->bind($defaultCommand->getDefinition());
        $defaultCommand->setInput($input);

        return $defaultCommand->handle(resolve(FixCode::class), resolve(ElaborateSummary::class), resolve(EnsurePrettierIsConfigured::class));
    }
}
