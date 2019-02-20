<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('coinMarketCap/CoinMarketCap.php');

class GraphBitCoin extends CI_Controller
{
    public function index()
    {
        $obj = new CoinMarketCap();
        $data = $obj->peticion();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

}