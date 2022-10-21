<?php

namespace Tighten\Sniffs\PHP;

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;

class UseConfigOverEnvSniff extends ForbiddenFunctionsSniff
{
    public $forbiddenFunctions = [
        'env' => 'config',
    ];
}
