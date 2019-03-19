<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GraphBitCoin extends CI_Controller
{
    private $bitcoinCap;

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('security');
        $this->load->library('tank_auth');
        $this->load->library('cart');
        $this->lang->load('tank_auth');
        $this->load->model('model_coinmarketcap');
        $this->bitcoinCap = new $this->model_coinmarketcap();
    }

    public function index()
    {
        $data = $this->bitcoinCap->getLatest();

        if(isset($_GET['enc']))
            return $this->getData();

        return $data;
    }

    public function chartData()
    {
        $data = $this->bitcoinCap->report();

        if(isset($_GET['enc']))
            return $this->getData();

        return $data;
    }

    function getData(){
        $data = $this->bitcoinCap->getData();
        return $data;
    }

    /**
     * @param $data
     */


}