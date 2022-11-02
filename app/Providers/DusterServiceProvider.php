<?php

namespace App\Providers;

use App\Actions\Clean;
use App\Commands\DusterCommand;
use Illuminate\Support\ServiceProvider;

class DusterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindMethod([DusterCommand::class, 'handle'], function ($command) {
            return $command->handle(
                resolve(Clean::class),
            );
        });
    }
}
