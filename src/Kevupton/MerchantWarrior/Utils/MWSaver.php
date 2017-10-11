<?php namespace Kevupton\MerchantWarrior\Utils;

use Kevupton\MerchantWarrior\Models\BaseModel;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Payment;

class MWSaver
{

    private $result;
    private $sent;
    private $xml;

    public function __construct (Response $response, $method, array $sent, &$result)
    {
        $this->result = &$result;
        $this->sent = $sent;
        $this->xml = $response->content();

        if (mw_conf('save_data')) {
            mw_log($response->content()->asXML(), $sent);

            $callable = "_save" . ucfirst($method);
            if (method_exists($this, $callable)) {
                $this->$callable();
            } else if ($response->success()) {
                $callable = "_saveSuccess" . ucfirst($method);
                if (method_exists($this, $callable)) {
                    $this->$callable();
                }
            }
        }
    }

    /**
     * Method for procedure AddCard
     */
    private function _saveSuccessAddCard ()
    {
        $user_id = mw_conf('add_user_to_card', false) && \Auth::check() ? \Auth::id() : null;

        $this->result = Card::updateOrCreate([
            'cardID' => (string)$this->xml->cardID
        ], [
            'cardID' => (string)$this->xml->cardID,
            'cardKey' => (string)$this->xml->cardKey,
            'ivrCardID' => (string)$this->xml->ivrCardID,
            'cardNumber' => (string)$this->xml->cardNumber,
            'user_id' => $user_id
        ]);
    }

    /**
     * Removes the card from the database.
     */
    private function _saveSuccessRemoveCard ()
    {
        /** @var BaseModel $card */
        $card = Card::find($this->sent['cardID']);
        if ($card) $card->delete();
    }

    /**
     * Saves the card info
     */
    private function _saveSuccessCardInfo ()
    {

        /** @var Card $card */
        $card = Card::find((string)$this->xml->cardID);

        $this->result = $card->update([
            'cardName' => (string)$this->xml->cardName,
            'cardExpiryMonth' => (string)$this->xml->cardExpiryMonth,
            'cardExpiryYear' => (string)$this->xml->cardExpiryYear,
            'cardNumberFirst' => (string)$this->xml->cardNumberFirst,
            'cardNumberLast' => (string)$this->xml->cardNumberLast,
            'cardAdded' => (string)$this->xml->cardAdded,
        ]);
    }

    private function _saveProcessCard ()
    {
        $this->result = Payment::create(array_merge($this->sent, [
            'responseCode' => (string)$this->xml->responseCode,
            'responseMessage' => (string)$this->xml->responseMessage,
            'transactionID' => (string)$this->xml->transactionID,
            'authCode' => (string)$this->xml->authCode,
            'receiptNo' => (string)$this->xml->receiptNo,
            'authMessage' => (string)$this->xml->authMessage,
            'authResponseCode' => (string)$this->xml->authResponseCode,
            'authSettledDate' => (string)$this->xml->authSettledDate,
            'paymentCardNumber' => (string)$this->xml->paymentCardNumber,
            'transactionAmount' => (string)$this->xml->transactionAmount,
            'customHash' => (string)$this->xml->customHash,
        ]));
    }

}