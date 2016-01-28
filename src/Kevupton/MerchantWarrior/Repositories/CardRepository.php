<?php namespace Kevupton\MerchantWarrior\Repositories;

use Kevupton\Ethereal\Repositories\Repository;
use Kevupton\MerchantWarrior\Exceptions\CardException;
use Kevupton\MerchantWarrior\Models\Card;


class CardRepository extends Repository {

    protected $exceptions = [
        'main' => CardException::class,
    ];

    /**
     * Retrieves the class instance of the specified repository.
     *
     * @return string the string instance of the defining class
     */
    function getClass()
    {
        return Card::class;
    }

    /**
     * Deletes both from card info and from cards
     *
     * @param $cardID string
     */
    public function deleteCard($cardID)
    {
        $card_info_repo = new CardInfoRepository();
        $payment_repo = new PaymentRepository();

        $card_info_repo->removeByID($cardID);
        $payment_repo->removeCardID($cardID);
        $this->removeByID($cardID);
    }

}