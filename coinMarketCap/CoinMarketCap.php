<?php

class CoinMarketCap
{
    private $environment = 'sandbox';
    private $curl;
    private $url;
    private $apiKey;
    private $apiKey_pro     = "ead923ef-7a18-4b31-8582-1aac0d4017fc";
    private $apiKey_sandbox = "9d540fa4-8a95-4ff2-81d8-c54c07143429";
    private $optionCurl = array();

    function __construct()
	{
        $this->url = "https://{$this->environment}-api.coinmarketcap.com/v1/cryptocurrency/";
        // validar que apiKey utilizar
        $this->apiKey = ($this->environment == 'pro') ? $this->apiKey_pro : $this->apiKey_sandbox;

        $this->curl = curl_init();
        $this->optionCurl = array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "undefined=",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "X-CMC_PRO_API_KEY: {$this->apiKey}",
                "cache-control: no-cache"
            )
        );
    }

    public function peticion()
    {
        // Completar la url y los parámetros
        $this->optionCurl[CURLOPT_URL] .= "listings/latest?start=1&limit=1&convert=USD";
        
        echo $this->optionCurl[CURLOPT_URL]."<br>";

        curl_setopt_array($this->curl, $this->optionCurl);
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function historical()
    {
        // Completar la url y los parámetros
        $this->optionCurl[CURLOPT_URL] .= "ohlcv/historical?id=1&time_start=2018-12-01&time_end=2018-12-31&convert=USD";

        echo $this->optionCurl[CURLOPT_URL]."<br>";

        curl_setopt_array($this->curl, $this->optionCurl);
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}

?>