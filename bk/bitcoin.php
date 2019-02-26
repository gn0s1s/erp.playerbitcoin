<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 25/02/2019
 * Time: 11:50 AM
 */

date_default_timezone_set('UTC');#city
echo "Leyendo datos... \n";
$code = "->playerbitcoin";#code
$val = md5(date('Y-m-d')."^".date('H:i:s').$code);
$ruta = str_replace("/public_html", "/erp.playerbitcoin", getcwd());
if(stripos($ruta,"/erp.playerbitcoin")===false)
    $ruta.="/erp.playerbitcoin";
$link = $ruta.'/bk/dataset.php';

require $link;

$API = new model_coinmarketcap($db,0);

$function = isset($argv[1]) ? $argv[1] : false;

if (! $function || ! method_exists($API,$function)) {
    echo "Process not defined  \n";
    exit();
}

$params =  sizeof($argv) > 1 ? $argv : "";
if($params != ""){
    unset($params[0]);
    unset($params[1]);

    if(sizeof($params)>0)
        $params = "'".implode("','",$params)."'";
    else
        $params = "";
}

$function_exec = "\$API->$function($params);";
echo $function_exec." \n";

eval($function_exec);

class model_coinmarketcap 
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
    private $db;

    function __construct($db,$test = false)
    {
        echo "INIT CONMARKETCAP \n";
        $this->db = $db;
        $get = $this->getCoinmarket();
        if($test===false)
            $test = $get["test"];

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

    public function newHistorical(){
        $json = $this->getLatest();
        $data = $this->getData();
        $bitcoin_value = 1000;
        if(isset($data["data"]))
            $bitcoin_value = $data["data"][0]["quote"]["USD"]["price"];
        echo ("bitcoin----> ".json_encode($bitcoin_value)."\n");

        $update = date('Y-m-d H:i:s').",".$bitcoin_value;
        $update.=";$bitcoin_value";
        $update.=";$bitcoin_value";
        $this->write_file($update,"bitcoin.txt");
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

        $q = newQuery($this->db,"SELECT * FROM coinmarketcap");

        if (!$q)
            return false;

        return $q[1];
    }

    /**
     * @return string
     */
    public function getApiKeyPro()
    {
        $get = $this->getCoinmarket();
        $this->setApiKeyPro($get["apikey"]);
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
        $this->setApiKeySandbox($get["testkey"]);
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

        echo ( "CNMKTCP {$this->timeapi} ::>> $Curl_link  \n");

        curl_setopt_array($this->curl, $this->optionCurl);
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
            $json = json_encode($params);
            echo ( "cURL Error $json #: $err  \n");
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
        echo ("getdata C->> ".json_encode($data)." \n");
        return $data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    function write_file($texto =  "",$file = false){

        if(strlen($texto)<3)
            return false;

        if(!$file)
            $file = "log.php";

        $new_file = setDir() . "/bk/$file";
        $linea="$texto\n";
        $file = fopen($new_file, "a");
        fputs($file, $linea);
        fclose($file);
    }

}


