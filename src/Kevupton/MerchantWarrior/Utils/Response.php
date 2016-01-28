<?php namespace Kevupton\MerchantWarrior\Utils;

use Illuminate\Config\Repository;
use Kevupton\Ethereal\Models\Ethereal;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Log;
use Kevupton\MerchantWarrior\Repositories\CardInfoRepository;
use Kevupton\MerchantWarrior\Repositories\CardRepository;
use Kevupton\MerchantWarrior\Repositories\PaymentRepository;

class Response {

    /** @var \SimpleXMLElement */
    private $xml;
    /** @var  Ethereal */
    private $result;
    private $sent;

    public function __construct($method, $response, $sent) {
        $this->xml = new \SimpleXMLElement($response);
        $this->sent = $sent;

        $saver = new MWSaver($this, $method, $sent, $this->result);
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
