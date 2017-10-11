<?php namespace Kevupton\MerchantWarrior\Utils;

use Kevupton\MerchantWarrior\Models\BaseModel;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Payment;

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

        $this->result  = Card::updateOrCreate([
            'cardID' => (string) $this->xml->cardID
        ],[
            'cardID' => (string) $this->xml->cardID,
            'cardKey' => (string) $this->xml->cardKey,
            'ivrCardID' => (string) $this->xml->ivrCardID,
            'cardNumber' => (string) $this->xml->cardNumber,
            'user_id' => $user_id
        ]);
    }

    /**
     * Removes the card from the database.
     */
    private function _saveRemoveCard() {
        /** @var BaseModel $card */
        $card = Card::find($this->sent['cardID']);
        if ($card) $card->delete();
    }

    /**
     * Saves the card info
     */
    private function _saveCardInfo() {

        /** @var Card $card */
        $card = Card::find((string) $this->xml->cardID);

        $this->result = $card->update([
            'cardName' => (string) $this->xml->cardName,
            'cardExpiryMonth' => (string) $this->xml->cardExpiryMonth,
            'cardExpiryYear' => (string) $this->xml->cardExpiryYear,
            'cardNumberFirst' => (string) $this->xml->cardNumberFirst,
            'cardNumberLast' => (string) $this->xml->cardNumberLast,
            'cardAdded' => (string) $this->xml->cardAdded,
        ]);
    }

    private function _saveProcessCard() {
        $this->result = Payment::create($this->sent);
    }

}