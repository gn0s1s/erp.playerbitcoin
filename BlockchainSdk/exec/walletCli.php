<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 18/03/19
 * Time: 12:52 PM
 */

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

require_once("wallet.php");
$myAPI = new mywallet($api_key);

?>
