<?php namespace Kevupton\MerchantWarrior\Providers;

use Kevupton\LaravelPackageServiceProvider\ServiceProvider;
use Kevupton\MerchantWarrior\Facades\MerchantWarrior as MerchantWarriorFacade;
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
        $this->registerConfig(__DIR__ . '/../../../config/config.php', MERCHANT_WARRIOR_CONFIG . '.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        $this->app->singleton(self::SINGLETON, function () {
            return new MerchantWarrior();
        });

        $this->registerAlias(MerchantWarriorFacade::class, 'MerchantWarrior');

        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php', MERCHANT_WARRIOR_CONFIG
        );
    }
}