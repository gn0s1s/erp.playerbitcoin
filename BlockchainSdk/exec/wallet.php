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

require_once("WalletService.php");

$myAPI = new mywallet($api_key);

class mywallet
{
    private $Blockchain = false;
    private $service_url = 'http://localhost:3000';
    private $examples = array("Programmatically created new address.",array("0.001"));
    private $wallet_guid = null;

    /**
     * @return null
     */
    public function getWalletGuid()
    {
        return $this->wallet_guid;
    }

    /**
     * @param null $wallet_guid
     */
    public function setWalletGuid($wallet_guid)
    {
        $this->wallet_guid = $wallet_guid;
    }

    /**
     * @return null
     */
    public function getWalletPass()
    {
        return $this->wallet_pass;
    }

    /**
     * @param null $wallet_pass
     */
    public function setWalletPass($wallet_pass)
    {
        $this->wallet_pass = $wallet_pass;
    }
    private $wallet_pass = null;
    private $id_wallet = null;
    private $balance = null;
    private $address = null;

    function __construct($api_key = 0000){

        $api_code = trim($api_key);
        $this->Blockchain = new \Blockchain\Blockchain($api_code);
        $this->setInstance();
    }

    function setInstance(){
        /** REQUIRE nodejs, npm & root env features */
        $this->Blockchain->setServiceUrl($this->service_url);
    }

    function newWallet($pass = 'weakPassword01!',$email = false,$label = false){
        return $this->Blockchain->Create->create($pass,$email,$label);
    }

    function checkLogin(){
        if(is_null($this->wallet_guid) || is_null($this->wallet_pass)) {
            pre_message("Please enter a wallet GUID and password in the source file.");
            return false;
        }

        return true;
    }

    function Login($balance =  true){
        if(!$this->checkLogin())
            return false;

        $login =  $this->Blockchain->Wallet->credentials($this->wallet_guid, $this->wallet_pass);

        if(!$login):
            pre_message("login failed :: $this->wallet_guid, $this->wallet_pass");
            return false;
        endif;

        $this->id_wallet = $this->Blockchain->Wallet->getIdentifier();
        #TODO: pre_message("Using wallet : ".$this->id_wallet);

        if($balance) {
            $this->balance = $this->Blockchain->Wallet->getBalance();
            #TODO: pre_message("Balance : ".$this->balance);
            return $balance;
        }

        return true;
    }

    function newEntry($label = false){

        if(!$label)
            $label = $this->examples[0];

        return $this->Blockchain->Wallet->getNewAddress($label);
    }

    function getEntries(){
        $addresses = $this->Blockchain->Wallet->getAddresses();
        pre_message("Addresses : ".json_encode($addresses));
        return $addresses;
    }

    function sendEntry($value = "0.001",$address = false,$fee = false){

        if(!$address)
            $address = $this->newEntry();
        if(!$fee)
            $fee = $value*0.02;

        log_text("fee : $$fee");

        try {
            // Enter recipient address here
            return $this->Blockchain->Wallet->send($address, (float) $value,null,$fee);
        } catch (\Blockchain\Exception\ApiError $e) {
            pre_message($e->getMessage());
            return false;
        }
    }

    function sendEntries($values = false,$address= false){

        if(!$values)
            $values = $this->examples[1];

        if(!$address)
            $address = array();

        // Multi-recipient format
        $entries  = array();
        foreach ($values as $key => $value) {
            $add = isset($address[$key]) ? $address[$key] : false;
            if(!$add)
                $add = $this->newEntry();

            $entries[$add] = $value;
        }

        try {
            // Enter recipient address here
            return $this->Blockchain->Wallet->sendMany($entries);
        } catch (\Blockchain\Exception\ApiError $e) {
            pre_message($e->getMessage());
            return false;
        }

    }

    function getLog(){
        // Output log of activity
        $log = $this->Blockchain->log;
        return $log;
    }


}

?>
