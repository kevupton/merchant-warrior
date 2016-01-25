<?php namespace Kevupton\MerchantWarrior\Utils;

use Illuminate\Config\Repository;
use Kevupton\Ethereal\Models\Ethereal;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Log;
use Kevupton\MerchantWarrior\Repositories\CardRepository;

class Response {

    /** @var \SimpleXMLElement */
    private $xml;
    /** @var  Ethereal */
    private $result;

    public function __construct($method, $response) {
        $this->xml = new \SimpleXMLElement($response);

        if ($this->isSuccess()) {
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
        (new CardRepository())->removeByID((string) $this->xml->cardID);
    }

    private function _saveCardInfo() {

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
    public function getResult() {
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
    public function isSuccess() {
        return $this->getCode() == 0;
    }

    /**
     * Opposite of isSuccess
     *
     * @return bool
     */
    public function isFailure() {
        return !$this->isSuccess();
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
