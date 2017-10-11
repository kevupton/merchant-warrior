<?php namespace Kevupton\MerchantWarrior\Providers;

use Illuminate\Support\ServiceProvider;
use Kevupton\MerchantWarrior\MerchantWarrior;

class MerchantWarriorServiceProvider extends ServiceProvider
{
    const SINGLETON = 'merchant-warrior';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot ()
    {
        $this->publishes([__DIR__ . '/../../../config/config.php' => config_path(MERCHANT_WARRIOR_CONFIG . '.php')]);

        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');

        $this->app->singleton(self::SINGLETON, function () {
            return new MerchantWarrior();
        });

        class_alias('MerchantWarrior', \Kevupton\MerchantWarrior\Facades\MerchantWarrior::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php', MERCHANT_WARRIOR_CONFIG
        );
    }
}