<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Conf\Menu;
use Illuminate\Support\Facades\Http;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        


        view()->composer('layouts.app', function($view) {
            //$url = json_decode(Http::get("https://s3.amazonaws.com/dolartoday/data.json"), true);
            $view->with('menus', Menu::menus());
        });
    }
}
