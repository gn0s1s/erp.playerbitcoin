<?php

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

log_text("initialize Wallet service");
$service = "blockchain-wallet-service";
$command = " start --port 3000 ";
$background = " > /dev/null &";

try{
	#echo shell_exec($service.$command.$background);
    log_text("-> SERVICE READY");
    $return = shell_exec($service . " -V");
    log_text("BLOCKCHAIN WALLLET API v$return");
}catch(Exception $e){
    log_text("-> SERVICE UNAVAILABLE");
}



