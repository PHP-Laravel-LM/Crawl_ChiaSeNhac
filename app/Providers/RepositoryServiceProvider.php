<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\SongRepository::class, \App\Repositories\SongRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\SongRepository::class, \App\Repositories\SongRepositoryEloquent::class);
        //:end-bindings:
    }
}
