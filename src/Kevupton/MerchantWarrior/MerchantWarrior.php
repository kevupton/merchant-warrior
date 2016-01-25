<?php namespace Kevupton\MerchantWarrior;

use Kevupton\MerchantWarrior\Exceptions\MerchantWarriorException;
use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Log;
use Kevupton\MerchantWarrior\Utils\Response;

class MerchantWarrior {

    private $testing;

    private $testing_url = 'https://base.merchantwarrior.com/';
    private $live_url = 'https://api.merchantwarrior.com/';

    private $post_uri = 'post';

    public function __construct() {
        $this->testing = mw_conf('testing');
    }

    /**
     * The addCard method is the method used to add a new card to MWE.
     *
     * @param array $data
        cardName
        cardNumber
        cardExpiryMonth
        cardExpiryYear
        cardGlobal*
        cardEmail*
        cardContact*
     * @return Response
     * @throws MerchantWarriorException
     */
    public function addCard(array $data) {
        return $this->sendRequest('addCard', $data);
    }

    /**
     * The removeCard method is the method used to remove a card from the MWV once it’s been added.
     *
     * @param array $data
        cardID
        cardKey?
        cardGlobal*
        cardEmail*
        code*
     * @return Response
     * @throws MerchantWarriorException
     */
    public function removeCard(array $data) {
        return $this->sendRequest('removeCard', $data);
    }

    /**
     * The removeCard method is the method used to remove a card from the MWV once it’s been added.
     *
     * @param array $data
        cardID
        cardKey?
     * @return Response
     * @throws MerchantWarriorException
     */
    public function cardInfo(array $data) {
        return $this->sendRequest('cardInfo', $data);
    }

    /**
     * The changeExpiry method is the method used to change a card’s expiry once it’s been added into
    the MWV.

     *
     * @param array $data
        merchantUUID
        apiKey
        cardID
        cardKey?
        cardExpiryMonth
        cardExpiryYear
     * @return Response
     * @throws MerchantWarriorException
     */
    public function changeExpiry(array $data) {
        return $this->sendRequest('changeExpiry', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function processCard(array $data) {
        $data['hash'] = $this->hashTransactionType($data['transactionAmount'], $data['transactionCurrency']);
        return $this->sendRequest('processCard', $data);
    }

    public function processAuth(array $data) {
        return $this->sendRequest('processAuth', $data);
    }

    private function url($ext = '') {
        return ($this->testing? $this->testing_url: $this->live_url) . trim($ext, ' /');
    }

    private function hashTransactionType($transactionAmount, $transactionCurrency) {
        return md5(strtolower(md5(mw_api_passphrase()) . mw_uuid() . $transactionAmount . $transactionCurrency));
    }

    private function hashQueryType($transaction) {
        return md5(strtolower(md5(mw_api_passphrase()) . mw_uuid() . $transaction));
    }

    private function hashCustomFields($custom1, $custom2, $custom3) {
        return md5(strtolower(md5(mw_api_passphrase()) . mw_uuid() . $custom1 . $custom2 . $custom3));
    }


    /**
     * Sends the post request to merchant warrior and returns the response in xml.
     *
     * @param $method string the identifying method
     * @param array $data the data to be posted
     * @param bool $token whether or not this is a token request
     * @return Response the response of the webpage
     *
     * @throws MerchantWarriorException if the request fails
     */
    private function sendRequest($method, array $data, $token = true) {

        //Adds the authentication to the request.
        $joiner = [
            'merchantUUID' => mw_uuid(),
            'apiKey' => mw_api_key()
        ];
        if (!$token) {
            $joiner['method'] = $method;
            $url = $this->url($this->post_uri);
        } else {
            $url = $this->url('token/' . $method);
        }

        $data = array_merge($data, $joiner);

        $ch = curl_init();
        $post_data = http_build_query($data);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec ($ch);

        if ($response === False) {
            throw new MerchantWarriorException(curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return new Response($method, $response, $data);
    }

}