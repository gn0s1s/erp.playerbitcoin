<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('UTC');#city


class model_coinmarketcap extends CI_Model
{
    private $environment = 'sandbox';
    private $curl;
    private $url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/";
    private $apiKey;
    private $apiKey_pro = "ead923ef-7a18-4b31-8582-1aac0d4017fc";
    private $apiKey_sandbox = "9d540fa4-8a95-4ff2-81d8-c54c07143429";
    private $optionCurl = array();
    private $timeapi;
    private $data = array();

    function __construct($test = false)
    {
        parent::__construct();
        $get = $this->getCoinmarket();
        if($test===false)
            $test = isset($get->test) ? $get->test : 1;

        parent::__construct();
        $this->timeapi = date('Y-m-d H:i:s');
        $this->setUrl($test);
        // validar que apiKey utilizar
        $this->apiKey = $this->getApiKey($test);

        $this->curl = curl_init();
        $this->optionCurl = $this->getCurlOptions();
    }

    public function getLatest()
    {
        $params = array(
            "start" => 1,
            "limit" => 1,
            "convert" => "USD"
        );
        $this->data = $this->setCurlRequest($params);
        return $this->data;
    }

    public function report($start = "2018-12-01", $end = "2018-12-31")
    {
        $params = array(
            "id" => 1,
            "time_start" => $start,
            "time_end" => $end,
            "time_period" => "hourly",
            "convert" => "USD"
        );
        // Completar la url y los parámetros
        $request_uri = "ohlcv/historical";
        $this->data = $this->setCurlRequest($params, $request_uri);
        return $this->data;
    }

    /**
     * @return string
     */
    public function getEnvironment($test = 0)
    {
        $mode = ($test == 0) ? "pro" : "sandbox";
        $this->setEnvironment($mode);
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getApiKey($test = 0)
    {
        $mode = ($test == 0) ? $this->getApiKeyPro() : $this->getApiKeySandbox();
        $this->setApiKey($mode);
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function getCurlOptions()
    {
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            "X-CMC_PRO_API_KEY: {$this->apiKey}",
            "cache-control: no-cache"
        );
        $options = array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "undefined=",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $headers
        );

        return $options;
    }

    /**
     * @param $test
     */
    private function setUrl($test)
    {
        $mode = $this->getEnvironment($test);
        $this->url = str_replace("//pro", "//$mode", $this->url);
    }

    function getCoinmarket()
    {
        $q = $this->db->query("SELECT * FROM coinmarketcap");
        $q = $q->result();

        if (!$q)
            return false;

        return $q[0];
    }

    /**
     * @return string
     */
    public function getApiKeyPro()
    {
        $get = $this->getCoinmarket();
        $apikey = $testkey = isset($get->apikey) ? $get->apikey : 0;
        $this->setApiKeyPro($apikey);
        return $this->apiKey_pro;
    }

    /**
     * @param string $apiKey_pro
     */
    public function setApiKeyPro($apiKey_pro)
    {
        $this->apiKey_pro = $apiKey_pro;
    }

    /**
     * @return string
     */
    public function getApiKeySandbox()
    {
        $get = $this->getCoinmarket();
        $testkey = isset($get->testkey) ? $get->testkey : 0;
        $this->setApiKeySandbox($testkey);
        return $this->apiKey_sandbox;
    }

    /**
     * @param string $apiKey_sandbox
     */
    public function setApiKeySandbox($apiKey_sandbox)
    {
        $this->apiKey_sandbox = $apiKey_sandbox;
    }

    private function setCurlRequest($params, $request_uri = "listings/latest")
    {
        // Completar la url y los parámetros
        $Curl_link = $this->optionCurl[CURLOPT_URL];
        $params_query = http_build_query($params);
        $Curl_link .= $request_uri . "?" . $params_query;
        $this->optionCurl[CURLOPT_URL] = $Curl_link;

        log_message('DEV', "CNMKTCP {$this->timeapi} ::>> $Curl_link");

        curl_setopt_array($this->curl, $this->optionCurl);
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
            $json = json_encode($params);
            log_message('ERROR', "cURL Error $json #: $err");
            return false;
        }

        $this->printData($response);
        return $response;
    }

    private function printData($data)
    {
        log_message('DEBUG',"<pre>$data</pre>");
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = json_decode($this->data,true);
        log_message('DEV',"getdata C->> ".json_encode($data));
        return $data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}

?>