<?php
namespace Yoeb\Paytr;

use Illuminate\Support\ServiceProvider;


class YoebServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

}



?>