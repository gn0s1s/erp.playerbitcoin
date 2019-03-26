<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class modelo_pagosonline extends CI_Model
{
	function val_compropago()
	{
		$compropago=$this->get_datos_compropago();
		if(!$compropago){
			$dato=array(
					"email" =>	"you@domain.com"
			);
			$this->db->insert("compropago",$dato);
			$compropago=$this->get_datos_compropago();
		}
		return $compropago;
	}

	function val_tucompra()
	{
		$tucompra=$this->get_datos_tucompra();
		if(!$tucompra){
			$dato=array(
					"email" =>	"you@domain.com"
			);
			$this->db->insert("tucompra",$dato);
			$tucompra=$this->get_datos_tucompra();
		}
		return $tucompra;
	}
	
	
	function val_payulatam()
	{
		$payulatam=$this->get_datos_payulatam();
		if(!$payulatam){
			$dato=array(
					"apykey" =>	"6u39nqhq8ftd0hlvnjfs66eh8c"
			);
			$this->db->insert("payulatam",$dato);
			$payulatam=$this->get_datos_payulatam();
		}
		return $payulatam;
	}

    function val_blockchain()
    {
        $blockchain=$this->get_datos_blockchain();
        if(!$blockchain)
            $blockchain=$this->initBlockchain();

        $wallets=$this->get_wallet_blockchain();
        if(!$wallets)
            $wallets = $this->initWallet();

        $fee=$this->get_fee_blockchain();
        if(!$fee)
            $fee = $this->initFee();

        return array($blockchain,$wallets,$fee);
    }

    function val_paypal()
	{
		$payulatam=$this->get_datos_paypal();
		if(!$payulatam){
			$dato=array(
					"email" =>	"seonetworksoft-facilitator@gmail.com"
			);
			$this->db->insert("paypal",$dato);
			$payulatam=$this->get_datos_paypal();
		}
		return $payulatam;
	}

	function get_datos_compropago()
	{
		$q=$this->db->query("select * from compropago");
		$payulatam = $q->result();
		return $payulatam;
	}
	
	function get_datos_tucompra()
	{
		$q=$this->db->query("select * from tucompra");
		$payulatam = $q->result();
		return $payulatam;
	}
	
	function get_datos_payulatam()
	{
		$q=$this->db->query("select * from payulatam");
		$payulatam = $q->result();
		return $payulatam;
	}
	function get_datos_blockchain()
	{
		$q=$this->db->query("select * from blockchain");
		$blockchain = $q->result();
		return $blockchain;
	}
	function get_wallet_blockchain($id = 1,$where = "")
	{
		$q=$this->db->query("select * from blockchain_wallet where id_usuario in ($id) $where");
		$wallets = $q->result();
		return $wallets;
	}
    function get_fee_blockchain($where = "")
    {
        if($where!="")$where = "WHERE $where";
        $q=$this->db->query("select * from blockchain_fee $where");
        $fee = $q->result();
        return $fee;
    }
	function get_datos_paypal()
	{
		$q=$this->db->query("select * from paypal");
		$paypal = $q->result();
		return $paypal;
	}

	function cliente_compropago(){

		$compropago  = $this->val_compropago(); 
		
		$test = ($compropago[0]->test!=1) ? true : false;

		$k_test = array($compropago[0]->pk_test,$compropago[0]->sk_test,$test);
		$k_live = array($compropago[0]->pk_live,$compropago[0]->sk_live,$test);

		$key = $k_test;

		if($compropago[0]->test!=1)
			$key=$k_live;

		return $key;

	}

	function actualizar_compropago()
	{
		
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
		
		$dato=array(
			
				"email"     => $_POST['email'],
				"pk_test"   		=> $_POST['pk_test'],
				"sk_test"   		=> $_POST['sk_test'],
				"pk_live"   		=> $_POST['pk_live'],
				"sk_live"   		=> $_POST['sk_live'],
				"moneda"   		=> $_POST['moneda'],
				"test"       			=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('compropago', $dato);
	
		return true;
	}
	
	
	function actualizar_tucompra()
	{
		
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
		
		$dato=array(
				"cuenta"     => $_POST['cuenta'],
				"email"     => $_POST['email'],
				"llave"   		=> $_POST['llave'],
				"moneda"   		=> $_POST['moneda'],
				"test"       			=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('tucompra', $dato);
	
		return true;
	}
	
	function actualizar_payulatam()
	{
	
		$test=0;
		$estado='DES';
	
		if(isset($_POST['test']))
			$test=1;
	
			if(isset($_POST['estatus']))
				$estado='ACT';
	
				$dato=array(
						"apykey"     => $_POST['apykey'],
						"id_comercio"   		=> $_POST['id_comercio'],
						"id_cuenta"     		=> $_POST['id_cuenta'],
						"moneda"       			=> $_POST['moneda'],
						"test"       			=> $test,
						"estatus"       		=> $estado
				);
	
				$this->db->where('apykey', $_POST['id']);
				$this->db->update('payulatam', $dato);
	
				return true;
	}

    function actualizar_blockchain()
    {
        $test=0;
        $estado='DES';

        if(isset($_POST['test']))
            $test=1;

        if(isset($_POST['estatus']))
            $estado='ACT';

        $dato = array(
            "apikey" => $_POST['key'],
			"accountid" => $_POST['accountid'],
			"accountkey" => $_POST['accountkey'],
            "currency" => $_POST['moneda'],
            "test" => $test,
            "estatus" => $estado
        );

        $this->db->where('id', $_POST['id']);
        $this->db->update('blockchain', $dato);

        $wallet_per = isset($_POST['wallet_per']) ? $_POST['wallet_per'][0] : "100";
        $xpub = isset($_POST['wallet']) ? $_POST['wallet'] : "2c303dc6-3817-4759-b0b1-a55369a56028";
        $dato=array(
            "hashkey"     => $xpub,
            "porcentaje"       		=> $wallet_per,
        );

        $this->db->where('id_usuario', 1);
        $this->db->update('blockchain_wallet', $dato);

        $fee = isset($_POST['fee']) ? $_POST['fee'] : array();
        foreach ($fee as $index => $item) {
            $per = isset($_POST['fee_per']) ? $_POST['fee_per'][$index] : 0;
            $id = isset($_POST['fee_id']) ? $_POST['fee_id'][$index] : $index+1;
            $dato=array(
                "monto"     => $item,
                "tarifa"      => $per,
            );

            $isFee = $this->get_fee_blockchain("id = $id");
            if ($isFee){
                $this->db->where('id', $id);
                $this->db->update('blockchain_fee', $dato);
            }
            else
                $this->db->insert('blockchain_fee', $dato);
        }

        return true;
    }

	function actualizar_paypal()
	{
	
		$test=0;
		$estado='DES';

		if(isset($_POST['test']))
			$test=1;
		
		if(isset($_POST['estatus']))
			$estado='ACT';
	
		$dato=array(
				"email"     => $_POST['email'],
				"moneda"       		=> $_POST['moneda'],
				"test"       		=> $test,
				"estatus"       		=> $estado
		);
	
		$this->db->where('email', $_POST['id']);
		$this->db->update('paypal', $dato);

		return true;
	}

    private function initBlockchain()
    {
        $dato = array(
            "apikey" => "78d9ce16-e1d6-47f7-acf1-f456409715f5"
        );
        $this->db->insert("blockchain", $dato);
        return $this->get_datos_blockchain();
    }

    private function initFee()
    {
        $dato = array(
            "monto" => 0,
            "tarifa" => 0
        );
        $this->db->insert("blockchain_fee", $dato);
        return $this->get_fee_blockchain();
    }

    private function initWallet()
    {
        $dato = array(
            "id_usuario" => 1,
            "hashkey" => "2c303dc6-3817-4759-b0b1-a55369a56028"
        );
        $this->db->insert("blockchain_wallet", $dato);
        return $this->get_wallet_blockchain();
    }

    function newTransferWallet($id,$amount = 0,$address = "0000"){

        $currency = "USD";$to="BTC";
        $myExchange = $this->newExchangeBlockchain();
        $amount = $myExchange->convertTo($amount,$currency,$to);

        $blockchain = $this->val_blockchain();

        $account = $blockchain[0][0];

        $accountid = $account->accountid;
        $accountkey = $account->accountkey;

        $mywallet = $this->newWalletBlockchain();

        $mywallet->setWalletGuid($accountid);
        $mywallet->setWalletPass($accountkey);

        $mywallet->Login();

        date_default_timezone_set('UTC');
        $date = date('Y-m-d H:i:s');

        $label = "pay in $id - ".date('Y-m-d H:i:s');
        $NewAddress = $mywallet->newEntry($label);

        $label = "Withdrawal for ID: $id at $date";
        $success = $mywallet->sendEntry($amount,$address);

        $log = $mywallet->getLog();

        log_message('DEV',"blkch :: ".json_encode($log));
        return $success ? $amount : false;

    }

    public function newExchangeBlockchain(){
	    $blockchain = $this->get_datos_blockchain();
        $api_key = $blockchain ? $blockchain[0]->apikey : 0000;

        $link = getcwd()."/BlockchainSdk/exec/rates.php";
        $myAPI = false;
        require_once $link;

        if(!$myAPI){
            echo "<b>.::: Blockchain in Building :::.</b>";
            exit();
        }

        return $myAPI;

    }

    public function newWalletBlockchain()
    {
        $blockchain = $this->get_datos_blockchain();
        $api_key = $blockchain ? $blockchain[0]->apikey : 0000;

        $link = getcwd() . "/BlockchainSdk/exec/walletCli.php";
        $myAPI = false;
        require $link;

        if (!$myAPI) {
            echo "<b>.::: Blockchain in Building :::.</b>";
            log_message('DEV', "Blockchain API ERROR");
            return false;
        }

        return $myAPI;
    }

}

?>
