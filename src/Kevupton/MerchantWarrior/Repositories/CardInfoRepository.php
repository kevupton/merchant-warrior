<?php namespace Kevupton\MerchantWarrior\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\MerchantWarrior\Exceptions\CardInfoException;
use Kevupton\MerchantWarrior\Models\CardInfo;


class CardInfoRepository extends Repository {

    protected $exceptions = [
        'main' => CardInfoException::class,
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return CardInfo::class;
    }

}