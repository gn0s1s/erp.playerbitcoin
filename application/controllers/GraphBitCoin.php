<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('coinMarketCap/CoinMarketCap.php');

class GraphBitCoin extends CI_Controller
{
    private $dataCoin;

    function __construct()
    {
        $this->dataCoin = new CoinMarketCap();
    }

    public function index()
    {
        $data = $this->dataCoin->peticion();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function historical()
    {
        $data = $this->dataCoin->historical();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

}