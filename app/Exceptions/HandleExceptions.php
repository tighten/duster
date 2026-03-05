<?php

namespace App\Exceptions;

use Illuminate\Foundation\Bootstrap\HandleExceptions as BaseHandleExceptions;

class HandleExceptions extends BaseHandleExceptions
{
    /**
     * {@inheritdoc}
     */
    protected function shouldIgnoreDeprecationErrors()
    {
        return true;
    }
}
