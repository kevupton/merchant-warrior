<?php namespace Kevupton\MerchantWarrior\Utils;

use Illuminate\Config\Repository;
use Kevupton\Ethereal\Models\Ethereal;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Log;
use Kevupton\MerchantWarrior\Repositories\CardInfoRepository;
use Kevupton\MerchantWarrior\Repositories\CardRepository;

class Response {

    /** @var \SimpleXMLElement */
    private $xml;
    /** @var  Ethereal */
    private $result;
    private $sent;

    public function __construct($method, $response, $sent) {
        $this->xml = new \SimpleXMLElement($response);
        $this->sent = $sent;

        if ($this->success()) {
            if (mw_conf('save_data')) {
                $callable = "_save" . ucfirst($method);
                if (method_exists($this, $callable)) {
                    $this->$callable();
                }

                $this->_log();
            }
        }
    }

    /**
     * Method for procedure AddCard
     */
    private function _saveAddCard() {
        $this->result = (new CardRepository)->createOrUpdate([
            'cardID' => (string) $this->xml->cardID,
            'cardKey' => (string) $this->xml->cardKey,
            'ivrCardID' => (string) $this->xml->ivrCardID
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
        $card_info = (new CardInfoRepository())->retrieveByID($this->sent['cardID']);
        $card_info->cardExpiryMonth = $this->sent['cardExpiryMonth'];
        $card_info->cardExpiryYear = $this->sent['cardExpiryYear'];
        $card_info->save();
    }

    /**
     * Saves the card info
     */
    private function _saveCardInfo() {
        $this->result = (new CardInfoRepository())->createOrUpdate([
            'cardID' => (string) $this->xml->cardID,
            'cardName' => (string) $this->xml->cardName,
            'cardExpiryMonth' => (string) $this->xml->cardExpiryMonth,
            'cardExpiryYear' => (string) $this->xml->cardExpiryYear,
            'cardNumberFirst' => (string) $this->xml->cardNumberFirst,
            'cardNumberLast' => (string) $this->xml->cardNumberLast,
            'cardAdded' => (string) $this->xml->cardAdded,
        ]);
    }

    /**
     * Logs the data in the database, of the request.
     */
    private function _log() {
        Log::create(['content' => $this->xml->asXML()]);
    }


    /**
     * Gets the result model from creation
     *
     * @return Ethereal
     */
    public function result() {
        return $this->result;
    }

    /**
     * Gets the response code
     *
     * @return string
     */
    public function getCode() {
        return (string) $this->xml->responseCode;
    }

    /**
     * Whether or not the response was successful
     *
     * @return bool
     */
    public function success() {
        return $this->getCode() == 0;
    }

    /**
     * Opposite of isSuccess
     *
     * @return bool
     */
    public function fails() {
        return !$this->success();
    }

    /**
     * Returns the response message
     *
     * @return string
     */
    public function getMessage() {
        return (string) $this->xml->responseMessage;
    }

    /**
     * Returns the xml content of the response
     *
     * @return \SimpleXMLElement
     */
    public function content() {
        return $this->xml;
    }
}
