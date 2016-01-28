<?php namespace Kevupton\MerchantWarrior\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\MerchantWarrior\Exceptions\PaymentException;
use Kevupton\MerchantWarrior\Models\Payment;


class PaymentRepository extends Repository {

    protected $exceptions = [
        'main' => PaymentException::class,
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return Payment::class;
    }

    /**
     * Removes the cardID from all instances of the payments data
     *
     * @param $cardID
     */
    public function removeCardID($cardID) {
        $this->query()->where('cardID', $cardID)->update(['cardID' => null]);
    }

}