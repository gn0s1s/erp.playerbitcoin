<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

if(!function_exists("pre_message")){
    function pre_message($string,$exit = false){
        echo "<div style='border:thin solid #03c;margin:2rem;padding:1rem;background:#e0e0e0;'>$string</div>\n";
        if($exit)
            exit();
    }
}

$api_code = null;
if(!isset($api_key))
    pre_message("MUST BE SET AN API KEY",true);

require_once("wallet.php");
$myAPI = new mywallet($api_key);

return $myAPI->newWallet($pass,$email);

?>
