<?php

namespace App\Support;

use App\Actions\ElaborateSummary;
use App\Actions\FixCode;
use App\Commands\DefaultCommand;
use App\Contracts\Tool;

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

        return $defaultCommand->handle(resolve(FixCode::class), resolve(ElaborateSummary::class));
    }
}
