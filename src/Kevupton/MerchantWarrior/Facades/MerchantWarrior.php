<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 19/09/2017
 * Time: 1:14 PM
 */

namespace Kevupton\MerchantWarrior\Facades;

use Illuminate\Support\Facades\Facade;
use Kevupton\MerchantWarrior\Providers\MerchantWarriorServiceProvider;

class MerchantWarrior extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return MerchantWarriorServiceProvider::SINGLETON; }
}