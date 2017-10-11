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

        class_alias(\Kevupton\MerchantWarrior\Facades\MerchantWarrior::class, 'MerchantWarrior');
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