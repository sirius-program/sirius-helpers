<?php

namespace SiriusProgram\SiriusHelpers;

use Illuminate\Support\ServiceProvider;

class SiriusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Sirius', Sirius::class);
    }

    public function boot()
    {
        //
    }
}
