<?php

namespace Efemer\Search\Factory;

use Efemer\Search\Index;
use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        //require_once __DIR__.'/../Mondo/helpers.php';
        //$this->app->configure('search');

        $configPath = __DIR__ . '/../../config/search.php';
        $this->mergeConfigFrom($configPath, 'search');

        $this->app->bind('search-index', function(){
            return new Index('my_index');
        });


    }
}
