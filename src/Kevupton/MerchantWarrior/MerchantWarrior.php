<?php namespace Kevupton\MerchantWarrior;

class MerchantWarrior {

    private $testing;

    private $testing_url = 'https://base.merchantwarrior.com/';
    private $live_url = 'https://api.merchantwarrior.com/';

    private $post_uri = 'post';


    public function __construct() {
        $this->testing = mw_conf('testing');
    }

    /**
     * @param array $data
     * @return \SimpleXMLElement
     */
    public function processCard(array $data) {
        return $this->sendRequest('processCard', $data);
    }

    public function processAuth(array $data) {
        return $this->sendRequest('processAuth', $data);
    }

    private function url($ext = '') {
        return ($this->testing? $this->testing_url: $this->live_url) . trim($ext, ' /');
    }

    /**
     * Sends the post request to merchant warrior and returns the response in xml.
     *
     * @param $method string the identifying method
     * @param array $data the data to be posted
     * @param bool $token whether or not this is a token request
     * @return \SimpleXMLElement the response of the webpage
     */
    private function sendRequest($method, array $data, $token = false) {

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
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec ($ch);
        var_dump($response);

        if ($response === False) {
            var_dump(curl_error($ch), curl_errno($ch));
        }
        curl_close ($ch);

        //return new \SimpleXMLElement($response);
    }

}