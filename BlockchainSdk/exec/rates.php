<?php
require_once(dirname(__DIR__) . '/vendor/autoload.php');

if(!function_exists("pre_message")){
    function pre_message($string,$exit = false){
        echo "<div style='border:thin solid #03c;margin:2rem;padding:1rem;background:#e0e0e0;'>$string</div>\n";
        if($exit)
            exit();
    }
}
if(!function_exists("log_text")){
    function log_text($texto =  ""){

        if(strlen($texto)<3)
            return false;

        $log_file = getcwd() . "/log.php";
        $linea=date('Y-m-d H:i:s')." - $texto \n\n ";
        $file = fopen($log_file, "a");
        fputs($file, $linea);
        fclose($file);
    }
}

$api_code = null;
if(!isset($api_key))
    pre_message("MUST BE SET AN API KEY",true);

$myAPI = new rates($api_key);

class rates{

    private $Blockchain = false;
    private $examples = array();

    function __construct($api_key = 0000){

        $api_code = trim($api_key);
        $this->Blockchain = new \Blockchain\Blockchain($api_code);
    }

    function convertTo($units = 500,$currency = 'USD',$to = "BTC"){
        // Convert a fiat amount to BTC
        $funct = "to".$to;
        $amount = $this->Blockchain->Rates->$funct($units, $currency);

        if(stripos("$amount","E")!==false):
            $setter = explode("E-","$amount");
            $leftcount = isset($setter[1]) ? $setter[1] : 8;
            $factor = pow(10,$leftcount);
            $factor = str_replace("10","0.","$factor");

            log_text("E factor :: $factor");
            $amount = $setter[0];
            $amount = str_replace(".","",$amount);
            $amount = $factor.$amount;
        endif;

        log_text("convert to :: $amount");
        return $amount;
    }

    function convertFrom($units = 500,$currency = 'USD',$to = "BTC"){
        // Convert a fiat amount to BTC
        $funct = "from".$to;
        $amount = $this->Blockchain->Rates->$funct($units, $currency);
        return $amount;
    }

    function getRates($cur = false){
        // Get Exchanges Rates
        $rates = $this->Blockchain->Rates->get($cur);
        return $rates;
    }

    function getLog(){
        // Output log of activity
        $log = $this->Blockchain->log;
        return $log;
    }

}

?>