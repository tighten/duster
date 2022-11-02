<?php

namespace App\Support;

use App\Actions\ElaborateSummary;
use App\Actions\FixCode;
use App\Commands\DefaultCommand;

class Pint extends Tool
{
    public function lint($paths): int
    {
        $this->heading('Linting using Pint');

        return $this->process();
    }

    public function fix($paths): int
    {
        $this->heading('Fixing using Pint');

        return $this->process();
    }

    private function process(): bool
    {
        $pint = new DefaultCommand();

        return $pint->handle(resolve(FixCode::class), resolve(ElaborateSummary::class));
    }
}
