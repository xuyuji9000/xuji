<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use League\Glide\ServerFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('League\Glide\Server', function ($app){
            $filesystem = $app->make('Illuminate\Contracts\Filesystem\Filesystem');

            return ServerFactory::create([
                'source'=>$filesystem->getDriver(),
                'cache'=>$filesystem->getDriver(),
                'source_path_prefix'=>'images',
                'cache_path_prefix'=>'images/.cache',
                
                // 'response' => new LaravelResponseFactory()
            ]);
        });
    }
}
