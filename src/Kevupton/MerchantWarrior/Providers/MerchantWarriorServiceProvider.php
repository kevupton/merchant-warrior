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

        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php', MERCHANT_WARRIOR_CONFIG
        );
    }
}