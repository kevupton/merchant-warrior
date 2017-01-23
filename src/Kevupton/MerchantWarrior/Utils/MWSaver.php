<?php namespace Kevupton\MerchantWarrior\Utils;

use Kevupton\MerchantWarrior\Models\Log;
use Kevupton\MerchantWarrior\Repositories\CardInfoRepository;
use Kevupton\MerchantWarrior\Repositories\CardRepository;
use Kevupton\MerchantWarrior\Repositories\PaymentRepository;

class MWSaver {

    private $result;
    private $sent;
    private $xml;

    public function __construct(Response $response, $method, array $sent, &$result) {
        $this->result = &$result;
        $this->sent = $sent;
        $this->xml = $response->content();

        if ($response->success()) {
            if (mw_conf('save_data')) {
                mw_log($response->content()->asXML(), $sent);

                $callable = "_save" . ucfirst($method);
                if (method_exists($this, $callable)) {
                    $this->$callable();
                }
            }
        }
    }

    /**
     * Method for procedure AddCard
     */
    private function _saveAddCard() {
        $user_id = mw_conf('add_user_to_card', false) && \Auth::check() ? \Auth::id() : null;

        $this->result  = (new CardRepository)->createOrUpdate([
            'cardID' => (string) $this->xml->cardID,
            'cardKey' => (string) $this->xml->cardKey,
            'ivrCardID' => (string) $this->xml->ivrCardID,
            'user_id' => $user_id
        ]);
    }

    /**
     * Removes the card from the database.
     */
    private function _saveRemoveCard() {
        (new CardRepository())->deleteCard($this->sent['cardID']);
    }

    /**
     * Changes the expiry date
     */
    private function _saveChangeExpiry() {
        $card = (new CardRepository())->retrieveByID($this->sent['cardID']);
        $card->cardExpiryMonth = $this->sent['cardExpiryMonth'];
        $card->cardExpiryYear = $this->sent['cardExpiryYear'];
        $card->save();
    }

    /**
     * Saves the card info
     */
    private function _saveCardInfo() {
        $this->result = (new CardRepository())->createOrUpdate([
            'cardID' => (string) $this->xml->cardID,
            'cardName' => (string) $this->xml->cardName,
            'cardExpiryMonth' => (string) $this->xml->cardExpiryMonth,
            'cardExpiryYear' => (string) $this->xml->cardExpiryYear,
            'cardNumberFirst' => (string) $this->xml->cardNumberFirst,
            'cardNumberLast' => (string) $this->xml->cardNumberLast,
            'cardAdded' => (string) $this->xml->cardAdded,
        ]);
    }

    private function _saveProcessCard() {
        $this->result = (new PaymentRepository())->create($this->sent);
    }

}