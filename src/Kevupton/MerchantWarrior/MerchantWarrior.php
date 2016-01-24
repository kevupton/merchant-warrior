<?php namespace Kevupton\MerchantWarrior;

use Kevupton\MerchantWarrior\Models\Card;
use Kevupton\MerchantWarrior\Models\Log;

class MerchantWarrior {

    private $testing;

    private $testing_url = 'https://base.merchantwarrior.com/';
    private $live_url = 'https://api.merchantwarrior.com/';

    private $post_uri = 'post';

    private $save_data = null;

    public function __construct() {
        $this->testing = mw_conf('testing');
    }

    public function addCard(array $data) {
        $xml = $this->sendRequest('addCard', $data);
        if ($xml->responseCode == 0) {
            if ($this->saveData()) {
                return $this->saveCard($xml);
            }
        }
        return $xml;
    }

    private function saveData() {
        return (is_null($this->save_data)? $this->save_data = mw_conf('save_data'): $this->save_data);
    }

    /**
     * @param array $data
     * @return \SimpleXMLElement
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

    public function saveCard(\SimpleXMLElement $xml) {

        $card = Card::create([
            'cardID' => (string) $xml->cardID,
            'cardKey' => (string) $xml->cardKey,
            'ivrCardID' => (string) $xml->ivrCardID
        ]);

        //log the request
        $this->log($xml);

        return $card;
    }

    private function log(\SimpleXMLElement $xml) {
        Log::create(['content' => $xml->asXML()]);
    }


    /**
     * Sends the post request to merchant warrior and returns the response in xml.
     *
     * @param $method string the identifying method
     * @param array $data the data to be posted
     * @param bool $token whether or not this is a token request
     * @return \SimpleXMLElement the response of the webpage
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
            var_dump(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        return new \SimpleXMLElement($response);
    }

}