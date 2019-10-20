<?php
namespace Sender\Senders;

class Zmtech
{
    private $url;
    private $id;
    private $key;
    private $response;

    public function __construct($url, $id, $key) {
        $this->url = $url;
        $this->id = $id;
        $this->key = $key;
    }
    
    /**
     * Get account info
     *
     * @return stdClass
     */
    public function getInfo() {
        return $this->request('/');
    }
    /**
     * Send sms message
     *
     * @param $pack
     * @return stdClass
     */
    public function sendSms($pack) {
        if (!isset($pack[0]) || !is_array($pack[0])) {
            $pack = [$pack];
        }
        return $this->request('/brand', ['pack' => $pack] );
    }
    /**
     * Send viber message
     *
     * @param $pack
     * @return stdClass
     */
    public function sendViber($pack) {
        if (!isset($pack[0]) || !is_array($pack[0])) {
            $pack = [$pack];
        }
        return $this->request('/viber', ['pack' => $pack]);
    }
    /**
     * Get statuses
     *
     * @return stdClass
     */
    public function getStatuses() {
        return $this->request('/statuses');
    }
    /**
     * Get Response
     *
     * @return stdClass
     */
    public function getLastResponse() {
        return $this->response;
    }
    /**
     * Request zmtech with params
     *
     * @param $path
     * @param $params
     * @return stdClass
     */
    private function request($path, $params = []) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url . $path);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ] );
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array_merge(['id' => $this->id,
            'password' => $this->key], $params)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        $response = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $body = substr($response, $header_size);
        $headers = substr($response, 0, $header_size);
        curl_close($curl);

        $this->response = new \stdClass();
        $this->response->result = true;
        $this->response->data = json_decode($body);
        $this->response->http_code = $http_code;
        if ($http_code != 200) {
            $this->response->result = false;
        }
        return $this->response;
    }
}