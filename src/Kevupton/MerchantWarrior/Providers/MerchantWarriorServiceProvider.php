<?php namespace Kevupton\MerchantWarrior\Providers;

use Illuminate\Support\ServiceProvider;
use Kevupton\Ethereal\CustomValidator;

class MerchantWarriorServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../../config/config.php' => config_path(MERCHANT_WARRIOR_CONFIG . '.php')]);
        $this->publishes([
            __DIR__.'/../../../database/migrations/' => database_path('migrations')
        ], 'migrations');
//        $this->publishes([
//            __DIR__.'/../../../../database/seeds/' => database_path('seeds')
//        ], 'seeds');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}