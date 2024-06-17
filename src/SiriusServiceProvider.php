<?php

namespace SiriusProgram\SiriusHelpers;

use Illuminate\Support\ServiceProvider;

class SiriusHelpersServiceProvider extends ServiceProvider
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