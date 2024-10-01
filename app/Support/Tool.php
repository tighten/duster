<?php

namespace App\Support;

use App\Concerns\CommandHelpers;

abstract class Tool
{
    use CommandHelpers;

    public function __construct(
        protected DusterConfig $dusterConfig,
    ) {}

    abstract public function lint(): int;

    abstract public function fix(): int;
}
