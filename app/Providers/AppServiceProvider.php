<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Wn\Generators\CommandsServiceProvider');
            $this->app->register('STS\Fixer\FixerServiceProvider');
            $this->app->register('Thedevsaddam\LumenRouteList\LumenRouteListServiceProvider');
        }

        $this->app->register('\Tymon\JWTAuth\Providers\LumenServiceProvider');
        
        $this->app->register('App\Services\DropBoxService');
    }
}
