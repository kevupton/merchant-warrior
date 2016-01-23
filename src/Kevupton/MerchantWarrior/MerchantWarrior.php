<?php namespace Kevupton\MerchantWarrior;

class MerchantWarrior {
    const TESTING = true;

    private $testing_url = 'https://base.merchantwarrior.com/post/';
    private $live_url = 'https://api.merchantwarrior.com/post/';


    public function processCard($data) {

    }

    public function processAuth($data) {

    }

    /**
     * Sends the post request to merchant warrior and returns the response in xml.
     *
     * @param array $data the data to be posted
     * @return \SimpleXMLElement the response of the webpage
     */
    private function sendRequest(array $data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::TESTING? $this->testing_url: $this->live_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec ($ch);

        curl_close ($ch);

        return new \SimpleXMLElement($response);
    }

}