<?php

namespace Tighten\Duster;

use LaravelZero\Framework\Kernel as BaseKernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DusterKernel extends BaseKernel
{
    /**
     * {@inheritdoc}
     */
    public function handle($input, $output = null)
    {
        $this->app->instance(InputInterface::class, $input);
        $this->app->instance(OutputInterface::class, $output);

        return parent::handle($input, $output);
    }
}
