<?php

namespace App\Providers;
 
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\GitRepository;
use App\Http\Repositories\GitRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Http\Repositories\GitRoomInterface::class, function(){           
            return new \App\Http\Repositories\GitRoom();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
