<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class compras extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->library('cart');
        $this->load->library('config');
		$this->lang->load('tank_auth');
		$this->load->model('ov/general');
		$this->load->model('ov/modelo_compras');
        $this->load->model('ov/modelo_billetera');
		$this->load->model('ov/model_perfil_red');
		$this->load->model('ov/model_afiliado');
		$this->load->model('model_tipo_red');
		$this->load->model('model_user_profiles');
		$this->load->model('bo/modelo_historial_consignacion');
		$this->load->model('bo/model_mercancia');
		$this->load->model('bo/model_admin');
		$this->load->model('bo/model_bonos');
		$this->load->model('model_user_webs_personales');
		$this->load->model('model_comprador');
		$this->load->model('model_carrito_temporal');
		$this->load->model('model_servicio');
		$this->load->model('bo/modelo_pagosonline');
        $this->load->model('bo/modelo_logistico');
        $this->load->model('cemail');
        $this->load->model('/bo/bonos/clientes/playerbitcoin/playerbonos');
		$this->load->model('ov/model_web_personal_reporte');
	}
	
	private $afiliados = array();
	private $afiliadosEstadisticas = array();
	
function index()
{
	if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		$direccion=$this->modelo_compras->get_direccion_comprador($id);
		$pais=$this->modelo_compras->get_pais();
		$info_compras=Array();
		$producto=0;
		if($this->cart->contents())
		{ 
			foreach ($this->cart->contents() as $items) 
			{	
				$imgn=$this->modelo_compras->get_img($items['id']);
				if(isset($imgn[0]->url))
				{
					$imagen=$imgn[0]->url;
				}
				else
				{
					$imagen="";
				}
				switch($items['name'])
				{
					case 1:
						$detalles=$this->modelo_compras->detalles_productos($items['id']);
						break;
					case 2:
						$detalles=$this->modelo_compras->detalles_servicios($items['id']);
						break;
					case 3:
						$detalles=$this->modelo_compras->comb_espec($items['id']);
						break;
					case 4:
						$detalles=$this->modelo_compras->comb_paquete($items['id']);
						break;
					case 5:
						$detalles=$this->modelo_compras->detalles_prom_serv($items['id']);
						break;
					case 6:
						$detalles=$this->modelo_compras->detalles_prom_comb($items['id']);
						break;
				}
				$info_compras[$producto]=Array(
					"imagen" => $imagen,
					"nombre" => $detalles[0]->nombre
				);
				$producto++;
			} 
		} 
		$data=array();
		$data["direccion"]=$direccion;
		$data["compras"]=$info_compras;
		$data["pais"]=$pais;
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/iniciar_transacion',$data);
	}

	
	function carrito()
	{
		
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$id = $this->tank_auth->get_user_id();
		
		
		$validacionCompraMercancia=$this->general->isActived($id);
		if($validacionCompraMercancia>0){
			$this->carritoTipoMercancia($validacionCompraMercancia);
			return true;
		}
		
		$usuario = $this->general->get_username($id);
		$grupos = $this->model_mercancia->CategoriasMercancia();
		$redes = $this->model_tipo_red->RedesUsuario($id);
		
		$style=$this->general->get_style(1);
		$this->template->set("style",$style);

		$this->template->set("usuario",$usuario);
		$this->template->set("grupos",$grupos);
		
		$this->template->set("redes", $redes);
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		
		
		$data=$this->get_content_carrito ();
	
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/carrito',$data);
	}
	
	function carritoTipoMercancia($id_tipo_mercancia)
	{
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		
		if(!isset($id_tipo_mercancia)){
			redirect('/shoppingcart');
		}
	
		$mostrarMercanciaTipo=0;
		
		if($id_tipo_mercancia==1)
			$mostrarMercanciaTipo=1;
		else if($id_tipo_mercancia==2)
			$mostrarMercanciaTipo=2;
		else if($id_tipo_mercancia==3)
			$mostrarMercanciaTipo=3;

		$id = $this->tank_auth->get_user_id();
	
		$usuario = $this->general->get_username($id);

		if($id_tipo_mercancia==3){
			$grupos = $this->model_mercancia->CategoriasMercancia();
			$redes = $this->model_tipo_red->RedesUsuario($id);

			$this->template->set("redes", $redes);
			$this->template->set("grupos",$grupos);
		}

		$this->template->set("usuario",$usuario);
		$this->template->set("mostrarMercancia",$mostrarMercanciaTipo);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');

        $style=$this->general->get_style(1);
        $this->template->set("style",$style);
	
		$data=$this->get_content_carrito ();
	
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/carrito',$data);
	}
	
	/**
	 * @param detalles
	 */private function get_content_carrito() {
	 	
	 	$id_usuario = $this->tank_auth->get_user_id();
	 	$pais = $this->general->get_pais($id_usuario);
	 
		$data=array();
		$contador=0;
		$info_compras=Array();
		
		foreach ($this->cart->contents() as $items)
		{

		$imagenes=$this->modelo_compras->get_imagenes($items['id']);
		$id_tipo_mercancia=$items['name'];
		
		if($id_tipo_mercancia==1)
			$detalles=$this->modelo_compras->detalles_productos($items['id']);
		else if($id_tipo_mercancia==2)
			$detalles=$this->modelo_compras->detalles_servicios($items['id']);
		else if($id_tipo_mercancia==3)
			$detalles=$this->modelo_compras->detalles_combinados($items['id']);
		else if($id_tipo_mercancia==4)
			$detalles=$this->modelo_compras->detalles_paquete($items['id']);
		else if($id_tipo_mercancia==5)
			$detalles=$this->modelo_compras->detalles_membresia($items['id']);
		
		$costosImpuestos=$this->modelo_compras->getCostosImpuestos($pais[0]->pais,$items['id']);
		
		$cantidad=$items['qty'];

		#log_message('DEV',"detalle get content : ".json_encode($detalles));

         $categoria = isset($detalles[0]->id_red) ? $detalles[0]->id_red : $detalles[0]->id_grupo;

            $info_compras[$contador]=array(
				"imagen" => $imagenes[0]->url,
				"nombre" => $detalles[0]->nombre,
				"puntos" => $detalles[0]->puntos_comisionables,
				"descripcion" => $detalles[0]->descripcion,
                "categoria" => $categoria,
				"costos" => $costosImpuestos,
				"cantidad" => $cantidad
		);
		$contador++;
		
		}

		$data['compras']= $info_compras;

		return $data;
	}

	
	function carrito_publico()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$afiliado = $this->model_user_webs_personales->traer_afiliado_por_username($_GET['usernameAfiliado']);
		$id_afiliado = $afiliado[0]->id;
		
		$usuario = $this->general->get_username($id_afiliado);
		$grupos = $this->model_mercancia->CategoriasMercancia();
		$redes = $this->model_tipo_red->RedesUsuario($id_afiliado);
	
		$this->template->set("usuario",$usuario);
		$this->template->set("grupos",$grupos);
		$this->template->set("id_afiliado",$id_afiliado);
	
		$info_compras=Array();
		$producto=0;
		if($this->cart->contents())
		{
			foreach ($this->cart->contents() as $items)
			{
				$imgn=$this->modelo_compras->get_img($items['id']);
				if(isset($imgn[0]->url))
				{
					$imagen = $imgn[0]->url;
				}
				else
				{
					$imagen = "";
				}
				switch($items['name'])
				{
					case 1:
						$detalles=$this->modelo_compras->detalles_productos($items['id']);
						break;
					case 2:
						$detalles=$this->modelo_compras->detalles_servicios($items['id']);
						break;
					case 3:
						$detalles=$this->modelo_compras->comb_espec($items['id']);
						break;
					case 4:
						$detalles=$this->modelo_compras->comb_paquete($items['id']);
						break;
					case 5:
						$detalles=$this->modelo_compras->detalles_prom_serv($items['id']);
						break;
					case 6:
						$detalles=$this->modelo_compras->detalles_prom_comb($items['id']);
						break;
				}
				$info_compras[$producto]=Array(
						"imagen" => $imagen,
						"nombre" => $detalles[0]->nombre
				);
				$producto++;
			}
		}
		$data=array();
	
		$data['compras']= $info_compras;
		$this->template->set("redes", $redes);
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
	
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/carritoWebPersonal',$data);
	}
	
	function comprar()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		
		$datos_afiliado = $this->model_perfil_red->datos_perfil($id);
		$this->template->set("datos_afiliado",$datos_afiliado);
		
		$pais = $this->general->get_pais($id);
		$this->template->set("pais_afiliado",$pais);
		
		$empresa  = $this->model_admin->val_empresa_multinivel();
		$this->template->set("empresa",$empresa);
		
		$canal = $this->model_admin->getCanalesWHERE('id = 1');
		$this->template->set("envio",$canal[0]->gastos);
		
		$contenidoCarrito=$this->get_content_carrito ();


        if(!$contenidoCarrito['compras'])
			redirect('/shoppingcart');
		
		$cartItem = array(); 
		
		foreach ($this->cart->contents() as $item){
			array_push($cartItem, $item);
		}
		
		$puntos = 0;	
		$i=0;
		foreach ($contenidoCarrito['compras'] as $item){
			$puntos += $item['puntos']*$cartItem[$i]['qty'] ;
			$i++;
		}
		
		$paypal  = $this->modelo_pagosonline->val_paypal();
		$payulatam  = $this->modelo_pagosonline->val_payulatam();
		$tucompra  = $this->modelo_pagosonline->val_tucompra();
		$compropago  = $this->modelo_pagosonline->val_compropago();

        $blockchain  = $this->modelo_pagosonline->val_blockchain();
        $this->template->set('blockchain',$blockchain[0]);

		$this->template->set('puntos',$puntos);
		$this->template->set('paypal',$paypal);
		$this->template->set('payulatam',$payulatam);
		$this->template->set('tucompra',$tucompra);
		$this->template->set('compropago',$compropago);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/comprar',$contenidoCarrito);
    }

    function payBackEarnings(){

        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        if(!$this->cart->contents()){
            echo "<script>window.location='/ov/dashboard';</script>";
            echo "The purchase cannot success";
            return 0;
        }

        $id = $this->tank_auth->get_user_id();
        $email = $this->general->get_email($id);
        $email = $email[0]->email;

        $contenidoCarrito=$this->get_content_carrito ();

        $deposits = 3;
        $totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito,false, $deposits);

        if($totalCarrito<=0){
            log_message('DEV',"deposits do not pay itself :: ".json_encode($contenidoCarrito));
            echo "Payment option do not apply.";
            return false;
        }

        $comisiones = $this->modelo_billetera->get_total_comisiones_afiliado($id);
        $retenciones = $this->modelo_billetera->ValorRetencionesTotalesAfiliado($id);
        $cobrosPagos=$this->modelo_billetera->get_cobros_total_afiliado($id);
        $cobroPendientes=$this->modelo_billetera->get_cobros_pendientes_total_afiliado($id);
        $total_transact = $this->modelo_billetera->get_total_transact_id($id);
        $total_bonos = $this->model_bonos->ver_total_bonos_id($id);

        /*	echo $comisiones."<br>";
         * 	echo $total_bonos."<br>";
            echo $retenciones."<br>";
            echo $cobrosPagos."<br>";
            echo $cobroPendientes."<br>"; */

        $cobrar = $retenciones + $cobrosPagos +  $cobroPendientes;
        $comisiones_neto = $comisiones - $cobrar;
        $saldo = $comisiones_neto + $total_transact + $total_bonos;

        $saldo -= $totalCarrito;

        if($saldo<0){
            log_message('DEV',"refunds earnings :: ".json_encode($saldo));
            echo "Insufficient Balance";
            return false;
        }

        $id_venta = $this->modelo_compras->registrar_venta_tipo($id,'BILLETERA');

        $this->setDescuentoCompra($id,$id_venta,$totalCarrito);

        $this->registrarFacturaDatosDefaultAfiliado ($id,$id_venta);

        $this->registrarFacturaMercancia ( $contenidoCarrito ,$id_venta);

        $this->cart->destroy();

        $emailPagos = $this->general->emailPagos();
        $emailPagos = $emailPagos[0]->email;

        $this->pagarComisionVenta($id_venta,$id);

        $typesec = $this->typeSERVER();
        $SERVER_NAME = $_SERVER["SERVER_NAME"];

        $view = "<h2>PURCHASE SUCCESS</h2><hr/><br/><h3># ORDER ID : $id_venta</h3>";
        $factura = "$typesec://$SERVER_NAME/bo/ventas/factura?id=$id";
        $view .= file_get_contents($factura);

        $this->cemail->sendPHPmail($email,"PAY BACK EARNINGS",$view,$emailPagos);

        $saldo = number_format($saldo,2);
        echo "<h2>AMOUNT TRANSFERED SUCESSFULLY</h2>
               <br/>WALLET AMOUNT: <strong>$saldo</strong>";
	}
	
	function RegistrarVentaConsignacion(){
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$id = $this->tank_auth->get_user_id();
		
		$contenidoCarrito=$this->get_content_carrito ();
		
		$totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);

		$fecha = date("Y-m-d");
			
		
		$id_venta = $this->modelo_compras->registrar_ventaConsignacion($id,$fecha);
		
		$this->registrarFacturaDatosDefaultAfiliado ($id,$id_venta);
		
		$this->registrarFacturaMercancia ( $contenidoCarrito ,$id_venta);
		
		$this->cart->destroy();

		$banco = $this->modelo_compras->RegistrarPagoBanco($id, $_POST['banco'],$id_venta,$totalCarrito);
		$emailPagos = $this->general->emailPagos();
		
		if(isset($banco[0]->id_banco)){
		echo '<div class="jumbotron">
				<h1>Congratulations!</h1>
				<p>The transaction has ended successfully.</p>
				<p class="text-danger">
				To complete your purchase you must send an email with proof of payment to the payment department
					(<b>'.$emailPagos[0]->email.'</b>)
				</p>
				<p>
				</p><div class="alert alert-success alert-block">
		 		<p><b>Bank name</b> : '.$banco[0]->descripcion.'</p>
				<p><b>Account number</b>: '.$banco[0]->cuenta.'</p>
			';
		
			if($banco[0]->swift){
			echo '
				<p><b>SWIFT</b> :'.$banco[0]->swift.'</p>
				';	
			}
			if($banco[0]->otro){
				echo '
				<p><b>Headline</b> :'.$banco[0]->otro.'</p>
				';
			}
			if($banco[0]->dir_postal){
				echo '
				<p><b>Postal address</b>  :'.$banco[0]->dir_postal.'</p>
				';
			}
			if($banco[0]->clave){
				echo '
				<p><b>KEY</b> :'.$banco[0]->clave.'</p>
				';
			}
			echo '<p>
				</p>
				</div>';
		}	
	}

	/** Virtual */

    function pagarVentaBlockchain(){

        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id = $this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if(!$this->cart->contents()){
            echo "<script>window.location='/shoppingcart';</script>";
            echo "The purchase can not be registered";
            return 0;
        }

        $contenidoCarrito=$this->get_content_carrito ();
        $totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito,true);
        $totalapagar=$this->get_valor_total_contenido_carrito($contenidoCarrito);

        $wallet = $this->modelo_pagosonline->get_wallet_blockchain();
        $blockchain = $this->modelo_pagosonline->get_datos_blockchain();
        $api_key = $blockchain[0]->apikey;

        $isWallets = false;#TODO: (sizeof($wallet) > 1);
        $POST_wallet = isset($_POST['wx']) ? $_POST['wx'] : false;

        if($isWallets)
            $isWallets = !($POST_wallet);

        if($isWallets){
            $this->template->set("providers",$wallet);
            $this->template->build('website/ov/compra_reporte/blockchain/explorar');
            return false;
        }

        if($POST_wallet) {
            $where = "AND id = $POST_wallet";
            $wallet = $this->modelo_pagosonline->get_wallet_blockchain(1, $where);
        }

        $content = json_encode($contenidoCarrito);
        $xpub = $wallet[0]->hashkey;

        if(strlen($xpub)<100){
            echo "<!> Blockchain is in TEST mode. Please, contact the site administrator. !!!";
            return false;
        }

        list($response,$id_proceso) = $this->setPeticionBlockchain($id, $xpub, $content,$totalCarrito);

        $address = isset($response->address) ? $response->address : false;
        if(!$response||!$address){
            echo "<abbr title='".json_encode($response)."'>?</abbr> Blockchain does not generate the address for payment. Try again later!!!";
            log_message('DEV',"response : ".json_encode($response));
            return false;
        }
        $this->updateCarritoProceso($id_proceso, $address,"address");

        $link = getcwd()."/BlockchainSdk/exec/rates.php";
        $myAPI = false;$amount = 0;
        require_once $link;

        if($myAPI){
            $currency = "USD";$to="BTC";
            $amount = $myAPI->convertTo($totalCarrito,$currency,$to);
            $pagar = $myAPI->convertTo($totalapagar,$currency,$to);
        }

        $filename = $this->setBlockchainQR($id,$address,$id_proceso,$amount);
        $this->template->set("qr",$filename);

        $this->template->set("id",$id);
        $this->template->set("direccion",$address);
        $this->template->set("pagar",$pagar);
        $this->template->set("total",$amount);
        $this->template->build('website/ov/compra_reporte/blockchain/Recibo');

    }

    function RegistrarBlockchain(){

        $id_pago = isset($_GET['id']) ? $_GET['id'] : false;
        $code = isset($_GET['k']) ? $_GET['k'] : false;

        if($code && $id_pago){

            $to = "BTC";$currency = "USD";
            $estado = "ACT";
            $metodo_pago = 'BLOCKCHAIN';
            $fecha = date("Y-m-d");

            $proceso = $this->getCarritoProceso($id_pago);

            if(!$proceso){
                $error = "Blockchain process not found :::: $id_pago";
                echo "ERROR -> ".$error;
                log_message('DEV', $error);
                return false;
            }

            $id = $proceso[0]->id_usuario;
            $requested= $proceso[0]->confirmations;
            $email = $this->general->get_email($id);
            $email = $email[0]->email;
            $referencia = json_encode($proceso[0]->carrito);

            $contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProceso($id_pago);

            $contenidoCarrito=json_decode($contenido_carrito_proceso[0]->contenido,true);
            $carrito=json_decode($contenido_carrito_proceso[0]->carrito,true);

            $firma = isset($carrito[0]->firma) ? $carrito[0]->firma : $carrito[0]["firma"];
            $address = isset($carrito[0]->address) ? $carrito[0]->address : $carrito[0]["address"];
            if($firma != $code || !$address){
                $error = "Blockchain: id:$id p:$id_pago f::e:$firma] r:[$code] a:[$address]";
                echo "ERROR -> ".$error;
                log_message('DEV', $error);
                return false;
            }

            $myAPI = $this->getExploreBlockchain();
            $tx = $myAPI->getAddressHash($address);

            if(!$tx){
                $error = "Blockchain API ERROR -> address:$address";
                echo "ERROR -> ".$error;
                log_message('DEV', $error);
                redirect("/auth");
                exit();
            }

            $exp = pow(10,8);
            $received = isset($tx->total_received) ? $tx->total_received*$exp : 0;

            $requerido = $this->getAmountCarrito($carrito);

            $myAPI = false;
            $myAPI = $this->getRatesBlockchain();
            $currency = "USD";$to="BTC";
            $recibido = $myAPI->convertFrom($received,$currency);

            $incompleto = false;#TODO: ($recibido < $requerido);

            log_message('DEV',"data :: $recibido -> $requerido");

            if($incompleto){
                $error = "Blockchain: Payment Insufficient -> id:$id";
                echo "ERROR -> ".$error;
                log_message('DEV', $error);

                $faltante = $requerido - $recibido;
                $view = "<h2>Address : $address</h2><hr/><br/>
                                <h3>You must pay the purchase required amount</h3>
                                <b>Required Amount: <strong>$ $faltante $currency</strong></b>";
                $this->cemail->sendPHPmail($email,$metodo_pago,$view);
                redirect("/auth");
                exit();
            }

            $where = "AND transaction_id = '$code'";
            $isRepeated = $this->modelo_compras->getPagoOnlineBy($id, $where);
            if($isRepeated){
                $error = "Blockchain BOT DETECTED : $id_pago -> $code";
                echo "ERROR -> ".$error;
                log_message('DEV', $error);
                redirect("/auth");
                exit();
            }

            $confirmed = 3;
            $requested++;
            $this->addConfirmationOnline($id_pago,$requested);
            if($requested < $confirmed){
                $error = "Blockchain confirmations ($requested | $confirmed): id:$id p:$id_pago";
                echo "DEV -> ".$error;
                log_message('DEV', $error);
                return false;
            }

            unset($carrito[0]);

            $id_venta = $this->modelo_compras->registrar_venta_pago_online($id,$metodo_pago,$fecha);

            $this->modelo_compras->registrar_pago_online
            ($id_venta,$id,$code,$fecha,$address,$metodo_pago,$estado, $referencia,$currency,$to);

            $this->registrarFacturaDatosDefaultAfiliado($id,$id_venta);
            $this->registrarFacturaMercanciaPagoOnline ( $contenidoCarrito,$carrito ,$id_venta);

            $this->modelo_logistico->setPedidoOnline($id_venta);

            $this->pagarComisionVenta($id_venta,$id);

            $typesec = $this->typeSERVER();
            $SERVER_NAME = $_SERVER["SERVER_NAME"];

            $view = "<h2>PURCHASE SUCCESS</h2><hr/><br/><h3># ORDER ID : $id_venta</h3>";
            $factura = "$typesec://$SERVER_NAME/bo/ventas/factura?id=$id";
            $view .= file_get_contents($factura);

            $this->cemail->sendPHPmail($email,$metodo_pago,$view);
            echo "OK";
            return true;
        }
        echo "FAIL";
        return false;

    }

    public function getExploreBlockchain()
    {
        $blockchain = $this->modelo_pagosonline->get_datos_blockchain();
        $api_key = $blockchain[0]->apikey;

        $link = getcwd() . "/BlockchainSdk/exec/explorer.php";
        $myAPI = false;
        require $link;

        if (!$myAPI) {
            echo "<b>.::: Blockchain in Built :::.</b>";
            log_message('DEV', "Blockchain API ERROR");
            return false;
        }

        return $myAPI;
    }

    public function getRatesBlockchain()
    {
        $blockchain = $this->modelo_pagosonline->get_datos_blockchain();
        $api_key = $blockchain[0]->apikey;

        $link = getcwd() . "/BlockchainSdk/exec/rates.php";
        $myAPI = false;
        require $link;

        if (!$myAPI) {
            echo "<b>.::: Blockchain in Built :::.</b>";
            log_message('DEV', "Blockchain API ERROR");
            return false;
        }

        return $myAPI;
    }

    public function getAmountCarrito($carrito)
    {
        $amount = isset($carrito[0]["amount"]) ? $carrito[0]["amount"] : false;
        $montoPago = isset($carrito[0]->amount) ? $carrito[0]->amount : $amount;
        if (!$montoPago) {
            $montoPago = 0;
            foreach ($carrito as $cart) {
                if (isset($cart["price"])) {
                    $montoPago += $cart["price"] * $cart["qty"];
                }
            }
        }
        return $montoPago;
    }

    function comprobarBlockchain(){

        $code = isset($_POST['code']) ? $_POST['code'] : false;

        $query = "SELECT * FROM pago_online_transaccion 
                          WHERE reference_sale = '$code' AND payment_method_id = 'BLOCKCHAIN'";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q){
            echo "FAIL";
            return false;
        }

        echo $q[0]->id_usuario;
    }

    function RespuestaBlockchain(){

        $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
        if ($this->tank_auth->is_logged_in() && !$id)
        {																		// logged in
            $id=$this->tank_auth->get_user_id();
        }

        if ($id) {
            $this->cart->destroy();
            $usuario=$this->general->get_username($id);
            $style=$this->general->get_style(1);

            $this->template->set("style",$style);
            $this->template->set("usuario",$usuario);

            $this->template->set_theme('desktop');
            $this->template->set_layout('website/main');
            $this->template->set_partial('header', 'website/ov/header',array("id"=>$id));
            $this->template->set_partial('footer', 'website/ov/footer');
            $this->template->build('website/ov/compra_reporte/transaccionExitosa');
            return true;
        }
        redirect('/');
    }

    function consultarBlockchain(){

        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        if(!$this->cart->contents()){
            echo "<script>window.location='/shoppingcart';</script>";
            echo "The purchase couldn't success!";
            return 0;
        }

        $contenidoCarrito=$this->get_content_carrito ();
        $totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);

        $blockchain = $this->modelo_pagosonline->get_datos_blockchain();
        $api_key = $blockchain[0]->apikey;

        $link = getcwd()."/BlockchainSdk/exec/rates.php";
        $myAPI = false;
        require_once $link;

        if(!$myAPI){
            echo "<b>.::: Blockchain En desarrollo :::.</b>";
            exit();
        }

        $currency = "USD";$to="BTC";
        $amount = $myAPI->convertTo($totalCarrito,$currency,$to);
        $rates = $myAPI->getRates($currency);

        $this->template->set("amount","$amount");
        $this->template->set("rates",$rates);
        $this->template->set("xe",$to);
        $this->template->set("currency",$currency);
        $this->template->set("value","$totalCarrito");

        $this->template->build('website/ov/compra_reporte/blockchain/consultar');

    }


    public function setPeticionBlockchain($id, $xpub, $content,$total)
    {
        $blockchain = $this->modelo_pagosonline->get_datos_blockchain();
        $api_key = trim($blockchain[0]->apikey);

        $firma = $this->setFirmaProceso($id, $api_key, $content, $total);

        $id_proceso = $this->cargarProcesoOnline($id, $content, $firma);

        $callback = $this->setCallback($firma, $id_proceso);

        $xpub = trim($xpub);

        $url = "https://api.blockchain.info/v2/receive";
        $url .= "?xpub=$xpub&callback=$callback&key=$api_key&gap_limit=1000";
        $command = 'curl "' . $url . '"';

        log_message('DEV',"new blockchain request ::>> ".$url);
        #TODO: echo $command;exit();
        $cargar = shell_exec($command);
        $response = json_decode($cargar);

        return array($response,$id_proceso);
    }

    public function setCallback($firma, $id_proceso)
    {
        $site = $_SERVER['SERVER_NAME'];
        $link = "/ov/compras/RegistrarBlockchain";
        $site .= $link;
        $params = "?k=$firma&id=$id_proceso";
        #$params.= "&seclink=$link";#TODO:
        $typesec = $this->typeSERVER();
        $uri = $site.$params;
        $callback = "$typesec://$uri";
        log_message('DEV',"callback : $callback");
        $callback = urlencode($callback);

        return $callback;
    }

    public function cargarProcesoOnline($id, $content, $firma)
    {
        $carritoCompras = $this->cart->contents();
        $carritoCompras[0]["firma"] = $firma;
        $carrito = json_encode($carritoCompras);
        $id_proceso = $this->modelo_compras->registrar_pago_online_proceso($id, $content, $carrito);
        return $id_proceso;
    }

    public function setFirmaProceso($id, $key, $content, $total)
    {
        $currency = "USD";
        $to = "BTC";
        $time = time();
        $sistema = $this->config->item('website_name', 'tank_auth');
        $usuario = $this->general->username($id);

        $codigoAutorizacion = md5($sistema . $time . $usuario[0]->username);
        $firma = md5($key . "-" . $codigoAutorizacion . "-" . $currency . "-" . $content . "-" . $total);
        return $firma;
    }

    function RegistrarVentaTucompra(){ //WOWCONEXION
	
		$id = $_POST['campoExtra1'];
		$id_pago = $_POST['campoExtra2'];
		$identificado_transacion = $_POST['firmaTuCompra'];
		$fecha=date("Y-m-d");
		$referencia = $_POST['codigoFactura'];
		$metodo_pago = $_POST['metodoPago'];
		$estado = $_POST['transaccionAprobada'];
		$respuesta = $_POST['numeroTransaccion'];
		$moneda = "COP";
		$medio_pago = $_POST['metodoPago'];
	
		if(!$id){
			return false;
			exit();
		}
	
		if($estado=="1"){				
	
			$id_venta = $this->modelo_compras->registrar_venta_pago_online($id,'TUCOMPRA',$fecha);
				
			$embarque = $this->modelo_logistico->setPedidoOnline($id_venta);
			
			$this->modelo_compras->registrar_pago_online
			($id_venta,$id,$identificado_transacion,$fecha,$referencia,
					$metodo_pago,$estado,$respuesta,$moneda,$medio_pago);
				
				
			$contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProceso($id_pago);
				
			$contenidoCarrito=json_decode($contenido_carrito_proceso[0]->contenido,true);
			$carrito=json_decode($contenido_carrito_proceso[0]->carrito,true);
				
			$this->registrarFacturaDatosDefaultAfiliado($id,$id_venta);
			$this->registrarFacturaMercanciaPagoOnline ( $contenidoCarrito,$carrito ,$id_venta);
			$this->pagarComisionVenta($id_venta,$id);
			
			return "OK";
		}
	
	}
	
	function RegistrarVentaPayuLatam(){
	
		$id = $_POST['extra1'];
		$id_pago = $_POST['extra2'];
		$identificado_transacion = $_POST['transaction_id'];
		$fecha=$_POST['transaction_date'];
		$referencia = $_POST['reference_sale'];
		$metodo_pago = $_POST['payment_method_id'];
		$estado = $_POST['state_pol'];
		$respuesta = $_POST['response_code_pol'];
		$moneda = $_POST['currency'];
		$medio_pago = $_POST['payment_method_name'];

		
		if($estado==4){
			

			$id_venta = $this->modelo_compras->registrar_venta_pago_online($id,'PAYULATAM',$fecha);
			
			$embarque = $this->modelo_logistico->setPedidoOnline($id_venta);
			
			$this->modelo_compras->registrar_pago_online
			($id_venta,$id,$identificado_transacion,$fecha,$referencia,
					$metodo_pago,$estado,$respuesta,$moneda,$medio_pago);
			
			
			$contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProceso($id_pago);
			
			$contenidoCarrito=json_decode($contenido_carrito_proceso[0]->contenido,true);
			$carrito=json_decode($contenido_carrito_proceso[0]->carrito,true);
			
			$this->registrarFacturaDatosDefaultAfiliado($id,$id_venta);
			$this->registrarFacturaMercanciaPagoOnline ( $contenidoCarrito,$carrito ,$id_venta);
			$this->pagarComisionVenta($id_venta,$id);
		}

	}
	
	function RegistrarVentaPayPal(){
	
		$id = $_POST['custom'];
		$id_pago = $_POST['invoice'];
		$identificado_transacion = $_POST['txn_id'];

		$referencia = $_POST['payer_id'];
		$metodo_pago = $_POST['payment_type'];
		$estado = $_POST['payment_status'];
		$respuesta = $_POST['txn_type'];
		$moneda = $_POST['mc_currency'];
		$medio_pago = $_POST['payment_type'];

	
		if($estado=='Completed'){
				
			$fecha = date("Y-m-d");
			$id_venta = $this->modelo_compras->registrar_venta_pago_online($id,'PAYPAL',$fecha);
			
			$embarque = $this->modelo_logistico->setPedidoOnline($id_venta);
			
			$this->modelo_compras->registrar_pago_online
			($id_venta,$id,$identificado_transacion,$fecha,$referencia,
					$metodo_pago,$estado,$respuesta,$moneda,$medio_pago);
				
				
			$contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProceso($id_pago);
				
			$contenidoCarrito=json_decode($contenido_carrito_proceso[0]->contenido,true);
			$carrito=json_decode($contenido_carrito_proceso[0]->carrito,true);
				
			$this->registrarFacturaDatosDefaultAfiliado($id,$id_venta);
			$this->registrarFacturaMercanciaPagoOnline ( $contenidoCarrito,$carrito ,$id_venta);
			$this->pagarComisionVenta($id_venta,$id);
		}
	
	}
	
	function RespuestaTucompra(){ //WOWCONEXION
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		
		$a = array(
			$_POST['campoExtra1'],
			$_POST['campoExtra2'],
			$_POST['firmaTuCompra'],
			date("Y-m-d"),
			$_POST['codigoFactura'],
			$_POST['metodoPago'],
			$_POST['transaccionAprobada'],
			$_POST['numeroTransaccion'], 
			"COP",
			$_POST['metodoPago']				
		);		
		
		if(!$a){
			return false;
			exit();
		}
		
		if($a[6]=="1"){
			
			//var_dump($a);exit();	
			$this->cart->destroy();
			$id=$this->tank_auth->get_user_id();
			$usuario=$this->general->get_username($id);
			$style=$this->general->get_style($id);
				
			$this->template->set("style",$style);
			$this->template->set("usuario",$usuario);
				
			$this->template->set_theme('desktop');
			$this->template->set_layout('website/main');
			$this->template->set_partial('header', 'website/ov/header');
			$this->template->set_partial('footer', 'website/ov/footer');
			$this->template->build('website/ov/compra_reporte/transaccionExitosa');
			
			//return "OK";
			
			return true;
		}
	
		redirect('/');
	}
	
	function RespuestaPayuLatam(){
		
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		if($_GET['transactionState']==4){
			
			$this->cart->destroy();
			$id=$this->tank_auth->get_user_id();
			$usuario=$this->general->get_username($id);
			$style=$this->general->get_style($id);
			
			$this->template->set("style",$style);
			$this->template->set("usuario",$usuario);
			
			$this->template->set_theme('desktop');
			$this->template->set_layout('website/main');
			$this->template->set_partial('header', 'website/ov/header');
			$this->template->set_partial('footer', 'website/ov/footer');
			$this->template->build('website/ov/compra_reporte/transaccionExitosa');
			return true;
		}
	
		redirect('/');
	}
	
	function RespuestaPayPal(){
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		
		$payment_status = $_POST['payment_status'];
		
		if ($payment_status=="Completed") {
			$this->cart->destroy();
			$id=$this->tank_auth->get_user_id();
			$usuario=$this->general->get_username($id);
			$style=$this->general->get_style($id);
				
			$this->template->set("style",$style);
			$this->template->set("usuario",$usuario);
				
			$this->template->set_theme('desktop');
			$this->template->set_layout('website/main');
			$this->template->set_partial('header', 'website/ov/header');
			$this->template->set_partial('footer', 'website/ov/footer');
			$this->template->build('website/ov/compra_reporte/transaccionExitosa');
			return true;
		}
			redirect('/');
	}
	

	function Compropago(){ //EVOLUCIONINTELIGENTE
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$key = $this->modelo_pagosonline->cliente_compropago();
		
		$v1=$key[0];
		$v2=$key[1];
		$v3=$key[2];

		$link = getcwd()."/CompropagoSdk/exec/listProviders.php";
        $providers = false;
		require_once $link;

		$passProviders = $providers;
 		
 		$this->template->set("providers",$passProviders);
 		$this->template->build('website/ov/compra_reporte/compropago/proveedores');
	
	}

	function CompropagoRegistrar(){ //EVOLUCIONINTELIGENTE
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$id = $this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$email = $this->general->get_email($id);

		$contenidoCarrito=$this->get_content_carrito ();
		$carritoCompras=$this->cart->contents();
		
		$totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);

		$fecha = date("Y-m-d");

		$customer = $usuario[0]->nombre." ".$usuario[0]->apellido;

		$id_venta = $this->modelo_compras->registrar_venta_pago_online_des($id,'COMPROPAGO',$fecha);
			
		$orders = array();

		foreach ($contenidoCarrito["compras"] as $value) {
			$texto = "(".$value['cantidad'].") ".$value['nombre'];			

			$costo = " = $ ".$value['costos'][0]->costo;
			$impuesto = intval($value['costos'][0]->costoImpuesto > 0) ? 
							" ".$value['costos'][0]->iva." ".$value['costos'][0]->tipoImpuesto : "";

			$valor = intval($costo>=0) ? $costo.$impuesto : "";

			$texto.=$valor;

			array_push($orders, $texto);
		} 

		$orders = implode(" + ", $orders); 
			
		$key = $this->modelo_pagosonline->cliente_compropago();
		$compropago = $this->modelo_pagosonline->val_compropago();		

		$payment_type = $_POST["prov"];

		$v1=$key[0];
		$v2=$key[1];
		$v3=$key[2]; 		

		$order_info = array(
		    'order_id' => $id_venta,
		    'order_name' => $orders,
		    'order_price' => $totalCarrito,
		    'customer_name' => $customer,
		    'customer_email' => $email[0]->email,
		    'payment_type' => $payment_type,
		    'currency' => $compropago[0]->moneda,
		    'expiration_time' => null
        );
 

		$link = getcwd()."/CompropagoSdk/exec/newCharge.php";
        $neworder = false;
		require_once $link;

		$Registro = $neworder;

		if(!$Registro||$Registro->status != "pending"){
			echo "FAIL";
			$this->db->query("delete from venta where id_venta = ".$id_venta);
			exit();
		}
		//else{ 
			$id_registro = $Registro->id;
			$url_recibo = "https://www.compropago.com/comprobante/?confirmation_id=".$id_registro;
		//	$this->template->set("url_recibo",$url_recibo);
 		//	$this->template->build('website/ov/compra_reporte/compropago/Recibo');
		//}
		
		$contenidoCarrito["id"] = $id_registro;

		$id_pago_proceso = $this->modelo_compras->registrar_pago_online_proceso($id,json_encode($contenidoCarrito),json_encode($carritoCompras));
		
		$this->cart->destroy();
		
		echo "
		<legend>Please, wait for me to load the receipt</legend>
		<div class='compropagoDivFrame' id='compropagodContainer' style='width: 100%;'>
				    <iframe style='width: 100%;'
				        id='compropagodFrame'
				        src='".$url_recibo."'
				        frameborder='0'
				        scrolling='yes'></iframe>
				</div>
				<script type='text/javascript'>
				    function resizeIframe() {
				        var container=document.getElementById('compropagodContainer');
				        var iframe=document.getElementById('compropagodFrame');
				        if(iframe && container){
				            var ratio=585/811;
				            var width=container.offsetWidth;
				            var height=(width/ratio);
				            if(height>937){ height=937;}
				            iframe.style.width=width + 'px';
				            iframe.style.height=height + 'px';
				        }
				    }
				    window.onload = function(event) {
				        resizeIframe();
				    };
				    window.onresize = function(event) {
				        resizeIframe();
				    };
				</script>";

		//exit();

		//} ESTE ES EL IF (ELSE)
	}

	function CompropagoRespuesta(){ //EVOLUCIONINTELIGENTE
	
		$in = "SELECT id_venta FROM venta WHERE id_metodo_pago = 'COMPROPAGO' and id_estatus = 'DES'";
		$contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProcesoIN($in);
		$key = $this->modelo_pagosonline->cliente_compropago();	

		$preorders = array();

		foreach ($contenido_carrito_proceso as  $value) {
			$contenidoCarrito=json_decode($value->contenido,true);
			$datos = array($value->id,$contenidoCarrito['id']);
			array_push($preorders, $datos);
		} 

		$id_venta= 0;

		$v1=$key[0];
		$v2=$key[1];
		$v3=$key[2]; 

		$link = getcwd()."/CompropagoSdk/exec/Response.php";

		$estatus = false;
		require_once $link;
 
		$fp2 = fopen(getcwd()."/CompropagoSdk/exec/log.log", "a"); 
		fputs($fp2, ":".$estatus."]");
		fclose($fp2);

 		switch ($estatus) {
 			case 'ACT':
 				$contenido_carrito_proceso=$this->modelo_compras->getContenidoCarritoPagoOnlineProceso($id_venta);
 				$contenidoCarrito=json_decode($contenido_carrito_proceso[0]->contenido,true);
				$carrito=json_decode($contenido_carrito_proceso[0]->carrito,true);
					
				$this->registrarFacturaDatosDefaultAfiliado($contenido_carrito_proceso[0]->id_usuario,$id_venta);
				$this->registrarFacturaMercanciaPagoOnline ($contenidoCarrito,$carrito ,$id_venta);
				$this->db->query("UPDATE venta SET id_estatus = 'ACT' WHERE id_venta = ".$id_venta);
				$embarque = $this->modelo_logistico->setPedidoOnline($id_venta);
				$this->pagarComisionVenta($id_venta,$contenido_carrito_proceso[0]->id_usuario);
 				break;

 			case 'DES':
 				$this->db->query("UPDATE venta SET id_estatus = 'DES' WHERE id_venta = ".$id_venta);
 				break;

 			case 'DELETE':
 				$this->db->query("delete from venta where id_venta = ".$id_venta);
 				break;
 			
 			default:
 				# code...
 				break;
 		}
		//redirect('/');
	}

	function pagarVentaTucompra(){ //WOWCONEXION
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$actual_link = "http://$_SERVER[HTTP_HOST]";
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$email = $this->general->get_email($id);
	
	
		$contenidoCarrito=$this->get_content_carrito ();
		$carritoCompras=$this->cart->contents();
	
		$id_pago_proceso = $this->modelo_compras->registrar_pago_online_proceso($id,json_encode($contenidoCarrito),json_encode($carritoCompras));
	
		$descripcion="";
		foreach ($contenidoCarrito["compras"] as $mercancia){
			$descripcion.=" ".$mercancia["nombre"];
		}
	
		$totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);
	
	
		$tucompra  = $this->modelo_pagosonline->val_tucompra();
	
		$time = time();
		$codigoFactura = $id_pago_proceso;
		$codigoAutorizacion = md5("NetSoft".$time.$id_pago_proceso);
		
		//$firma = md5($tucompra[0]->apykey."~".$tucompra[0]->id_comercio."~NetSoft".$time."~".$totalCarrito."~".$tucompra[0]->moneda);
		
		$firma = md5($tucompra[0]->llave.";".$codigoFactura.";".$totalCarrito."".$tucompra[0]->moneda.";".$codigoAutorizacion);
		$id_transacion = $firma;
	
		$link="https://demo2.tucompra.net/tc/app/inputs/compra.jsp";
	
		if($tucompra[0]->test!=1)
			$link="https://gateway.tucompra.com.co/tc/app/inputs/compra.jsp";
	
			echo'
			<h2 class="semi-bold">Are you sure you make the payment?</h2>
			<form method="post" action="'.$link.'">'
	//		  .'<input name="merchantId"    type="hidden"  value="'.$tucompra[0]->id_comercio.'">'
			  .'<input name="usuario"     type="hidden"  value="'.$tucompra[0]->cuenta.'" >'
			  .'<input name="descripcionFactura"   type="hidden"  value="'.$descripcion.'"  >'
			  .'<input name="factura" type="hidden"  value="NetSoft'.$time.$id_pago_proceso.'" >'
			  .'<input name="valor"         type="hidden"  value="'.$totalCarrito.'"   >'
			  .'<input name="nombreComprador"  type="hidden"  value="'.$usuario[0]->nombre." ".$usuario[0]->apellido.'"  >'
			  .'<input name="documentoComprador" type="hidden"  value="'.$id.'" >'
			  .'<input name="tipoMoneda"      type="hidden"  value="'.$tucompra[0]->moneda.'" >'
			  .'<input name="signature"     type="hidden"  value="'.$id_transacion.'"  >'
	//		  .'<input name="test"          type="hidden"  value="'.$tucompra[0]->test.'" >'
			  .'<input name="campoExtra1" type="hidden" value="'.$id.'" >'
			  .'<input name="campoExtra2" type="hidden" value="'.$id_pago_proceso.'" >'
			  .'<input name="correoComprador" type="hidden"  value="'.$email[0]->email.'" >'
			  .'<input name="responseUrl"    type="hidden"  value="'.$actual_link.'/ov/compras/RespuestaTucompra" >'
			  .'<input name="confirmationUrl"  type="hidden"  value="'.$actual_link.'/ov/compras/RegistrarVentaTucompra" >'
			  .'<input name="Submit" type="submit"  value="Pagar" class="btn btn-success">
			</form>';
	
	}
	
	function pagarVentaPayuLatam(){
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$actual_link = "http://$_SERVER[HTTP_HOST]";

		$id = $this->tank_auth->get_user_id();
		$email = $this->general->get_email($id);
		
		
		$contenidoCarrito=$this->get_content_carrito ();
		$carritoCompras=$this->cart->contents();
		
		$id_pago_proceso = $this->modelo_compras->registrar_pago_online_proceso($id,json_encode($contenidoCarrito),json_encode($carritoCompras));
		
		$descripcion="";
		foreach ($contenidoCarrito["compras"] as $mercancia){
			$descripcion.=" ".$mercancia["nombre"];
		}
		
		$totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);
		
		
		$payulatam  = $this->modelo_pagosonline->val_payulatam();
		
		$time = time();
		$firma = md5($payulatam[0]->apykey."~".$payulatam[0]->id_comercio."~NetSoft".$time."~".$totalCarrito."~".$payulatam[0]->moneda);
		$id_transacion = $firma; 
		
		$link="https://stg.gateway.payulatam.com/ppp-web-gateway/";
		
		if($payulatam[0]->test!=1)
			$link="https://gateway.payulatam.com/ppp-web-gateway/";
		
		echo' 
			<h2 class="semi-bold">Are you sure you make the payment?</h2>
			<form method="post" action="'.$link.'">
			  <input name="merchantId"    type="hidden"  value="'.$payulatam[0]->id_comercio.'">
			  <input name="accountId"     type="hidden"  value="'.$payulatam[0]->id_cuenta.'" >
			  <input name="description"   type="hidden"  value="'.$descripcion.'"  >
			  <input name="referenceCode" type="hidden"  value="NetSoft'.$time.'" >
			  <input name="amount"        type="hidden"  value="'.$totalCarrito.'"   >
			  <input name="tax"           type="hidden"  value="0"  >
			  <input name="taxReturnBase" type="hidden"  value="0" >
			  <input name="currency"      type="hidden"  value="'.$payulatam[0]->moneda.'" >
			  <input name="signature"     type="hidden"  value="'.$id_transacion.'"  >
			  <input name="test"          type="hidden"  value="'.$payulatam[0]->test.'" >
			  <input name="extra1" type="hidden" value="'.$id.'" > 
			  <input name="extra2" type="hidden" value="'.$id_pago_proceso.'" >
			  <input name="buyerEmail"    type="hidden"  value="'.$email[0]->email.'" >
			  <input name="responseUrl"    type="hidden"  value="'.$actual_link.'/ov/compras/RespuestaPayuLatam" >
			  <input name="confirmationUrl"  type="hidden"  value="'.$actual_link.'/ov/compras/RegistrarVentaPayuLatam" >
			  <input name="Submit" type="submit"  value="Pagar" class="btn btn-success">
			</form>';

	}
	
	
	function pagarVentaPayPal(){
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
	
		$actual_link = "http://$_SERVER[HTTP_HOST]";
	
		$id = $this->tank_auth->get_user_id();
		$email = $this->general->get_email($id);
	
	
		$contenidoCarrito=$this->get_content_carrito ();
		$carritoCompras=$this->cart->contents();
	
		$id_pago_proceso = $this->modelo_compras->registrar_pago_online_proceso($id,json_encode($contenidoCarrito),json_encode($carritoCompras));
	
		$descripcion="";
		foreach ($contenidoCarrito["compras"] as $mercancia){
			$descripcion.=" ".$mercancia["nombre"];
		}
	
		$totalCarrito=$this->get_valor_total_contenido_carrito($contenidoCarrito);
		
		$paypal  = $this->modelo_pagosonline->val_paypal();
		
		$link="http://www.sandbox.paypal.com/webscr";
		
		if($paypal[0]->test!=1)
			$link="https://www.paypal.com/cgi-bin/webscr";

		echo '<h2 class="semi-bold">Are you sure you make the payment?</h2>
			  <form action="'.$link.'" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="custom" value="'.$id.'">
				<input type="hidden" name="business" value="'.$paypal[0]->email.'">
				<input type="hidden" name="item_name" value="'.$descripcion.'">
				<input type="hidden" name="currency_code" value="'.$paypal[0]->moneda.'">
				<input type="hidden" name="amount" value="'.$totalCarrito.'">
				<input type="hidden" name="return" value="'.$actual_link.'/ov/compras/RespuestaPayPal">
				<input type="hidden" name="cancel_return" value="'.$actual_link.'/ov/compras/">
				<input type="hidden" name="invoice" id="invoice" value="'.$id_pago_proceso.'" >
				<input type="hidden" name="notify_url" id="notify_url" value="'.$actual_link.'/ov/compras/RegistrarVentaPayPal"/>
				<input name="Submit" type="submit"  value="Pagar" class="btn btn-success">
			  </form>';
		
	
	}
	/**
	 * @param contenidoCarrito
	 */private function registrarFacturaMercancia($contenidoCarrito,$id_venta) {
		$contador=0;
		
		foreach ($this->cart->contents() as $items)
		{
			$costoImpuesto=0;
			$nombreImpuestos="";
			$precioUnidad=0;
			$cantidad=$items['qty'];
			$id_mercancia=$contenidoCarrito['compras'][$contador]['costos'][0]->id;	
			$precioUnidad=$items['price'];#TODO: $contenidoCarrito['compras'][$contador]['costos'][0]->costo;
			
			foreach ($contenidoCarrito['compras'][$contador]['costos'] as $impuesto){
				$costoImpuesto+=$impuesto->costoImpuesto;
				$nombreImpuestos.="".$impuesto->nombreImpuesto."\n";
			}
				
			if($contenidoCarrito['compras'][$contador]['costos'][0]->iva!='MAS'){
				$precioUnidad-=$costoImpuesto;
			}

			$this->modelo_compras->registrar_venta_mercancia($id_mercancia,$id_venta,$cantidad,$precioUnidad,$costoImpuesto,$nombreImpuestos);
			$contador++;
		}
	}

	private function registrarFacturaMercanciaPagoOnline($contenidoCarrito,$carrito,$id_venta) {
		$contador=0;
	
		foreach ($carrito as $items)
		{

			$costoImpuesto=0;
			$nombreImpuestos="";
			$precioUnidad=0;
			$cantidad=$items['qty'];
			$id_mercancia=$contenidoCarrito['compras'][$contador]['costos'][0]['id'];
			$precioUnidad=$items['price'];#TODO: $contenidoCarrito['compras'][$contador]['costos'][0]['costo'];
				
			foreach ($contenidoCarrito['compras'][$contador]['costos'] as $impuesto){
				$costoImpuesto+=$impuesto['costoImpuesto'];
				$nombreImpuestos.="".$impuesto['nombreImpuesto']."\n";
			}
	
			if($contenidoCarrito['compras'][$contador]['costos'][0]['iva']!='MAS'){
				$precioUnidad-=$costoImpuesto;
			}
	
			$this->modelo_compras->registrar_venta_mercancia($id_mercancia,$id_venta,$cantidad,$precioUnidad,$costoImpuesto,$nombreImpuestos);
			$contador++;
		}
	}

    private function get_valor_total_contenido_carrito($contenidoCarrito, $fee = false,$exception = false)
    {
        $contador = 0;
        $total = 0;

        foreach ($this->cart->contents() as $items) {

            $costoImpuesto = 0;
            $precioUnidad = 0;
            $cantidad = $items['qty'];

            $precioUnidad = $items['price'];#TODO: $contenidoCarrito['compras'][$contador]['costos'][0]->costo;

            $itemCartData = $contenidoCarrito['compras'][$contador];
            foreach ($itemCartData['costos'] as $impuesto) {
                $costoImpuesto += $impuesto->costoImpuesto;
            }

            if ($itemCartData['costos'][0]->iva != 'MAS') {
                $precioUnidad -= $costoImpuesto;
            }

            $precioTotal = $precioUnidad * $cantidad;
            $impuestoTotal = $costoImpuesto * $cantidad;
            $totalItem = $precioTotal + $impuestoTotal;

            if ($fee) {
                $where = "estatus = 'ACT'";
                $totalItem = $this->setBlockchainFee($totalItem, $where);
            }

            if($itemCartData['categoria'] == $exception)
                $totalItem = 0;

            $total += $totalItem;
            $contador++;
        }
        return $total;
    }
	
	private function registrarFacturaDatosDefaultAfiliado($id,$id_venta) {

		$datos_afiliado = $this->model_perfil_red->datos_perfil($id);
		$direccion = $this->general->get_pais($id);
		$telefonos = $this->model_perfil_red->telefonos($id);
		$tel="";
		foreach ($telefonos as $telefono){
			$tel.="-Numero ".$telefono->tipo."[".$telefono->numero."]\n";
		}
			  
		$this->modelo_compras->registrar_factura_datos_usuario
			  						($id_venta,$datos_afiliado[0]->nombre,$datos_afiliado[0]->apellido,$datos_afiliado[0]->keyword,
			  						 $direccion[0]->codigo_postal,$direccion[0]->pais,$direccion[0]->estado,$direccion[0]->municipio,
			  						 $direccion[0]->colonia,$direccion[0]->calle,$datos_afiliado[0]->email,"",$tel);
	}


	function SelecioneBanco(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		if(!$this->cart->contents()){
			echo "<script>window.location='/ov/dashboard';</script>";
			echo "The purchase can not be registered";
			return 0;
		}
		$id = $this->tank_auth->get_user_id();
	
		$data['bancos'] = $this->modelo_compras->BancosPagoUsuario($id);
	
		$this->template->set_theme('desktop');
		$this->template->build('website/ov/compra_reporte/bancos',$data);
	}
	
	function billetera()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);

		$estatus=$this->modelo_compras->get_estatus($id);

		$estatus=$estatus[0]->estatus;

		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		$this->template->set("estatus",$estatus);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/billetera');
	}
	function crea_pswd()
	{
		$id=$this->tank_auth->get_user_id();
		if($_POST['password']==$_POST['confirm_password'])
		{
			$this->modelo_compras->crea_pswd($id);
			echo "Your password has been created successfully";
		}
		else
		echo "Error. Your password contains errors, please verify it.";
	}
	
	function preOrdenEstadisticas($id,$red){

			$datos = $this->modelo_compras->traer_afiliados_estadisticas($id,$red);
			
			foreach ($datos as $dato){
				if ($dato!=NULL){
					array_push($this->afiliadosEstadisticas, $dato);
					$this->preOrdenEstadisticas($dato->id_afiliado,$red);
				}
			}

	}
	
	function estadistica()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		
		$redes = $this->model_perfil_red->ConsultarRedAfiliado($id);
		
		foreach ($redes as $red){
			$this->preOrdenEstadisticas($id,$red->id_red);
		}
		
		$this->template->set("afiliadosRed",count($this->afiliadosEstadisticas));
		
		$cantidad_hombres = 0;
		$cantidad_mujeres = 0;
		$cantidad_total_sexo = 0;
		$porcentaje_de_hombres = 0;
		$porcentaje_de_mujeres = 0;
		$porcentaje_total = 0;
		
		foreach ($this->afiliadosEstadisticas as $afiliado){
			if ($afiliado->id_sexo==1){
				$cantidad_hombres = $cantidad_hombres + 1;
			}
			else $cantidad_mujeres = $cantidad_mujeres + 1;
		}
		$cantidad_total_sexo = $cantidad_hombres+$cantidad_mujeres;

		if($cantidad_total_sexo==0)
			$porcentaje_total=0;
		else
			$porcentaje_total = 100/$cantidad_total_sexo;
		
		$porcentaje_de_hombres = round($porcentaje_total*$cantidad_hombres,1, PHP_ROUND_HALF_UP);
		$porcentaje_de_mujeres = round($porcentaje_total*$cantidad_mujeres,1, PHP_ROUND_HALF_UP);
		
		$this->template->set("porcentaje_de_hombres",$porcentaje_de_hombres);
		$this->template->set("porcentaje_de_mujeres",$porcentaje_de_mujeres);
		
		$cantidad_edad_18_20 = 0;
		$cantidad_edad_21_23 = 0;
		$cantidad_edad_24_26 = 0;
		$cantidad_edad_27_29 = 0;
		$cantidad_edad_30_32 = 0;
		$cantidad_edad_33_35 = 0;
		$cantidad_edad_36_38 = 0;
		$cantidad_edad_39_41 = 0;
		$cantidad_edad_42_43 = 0;
		$cantidad_edad_44_46 = 0;
		$cantidad_edad_47_49 = 0;
		$cantidad_edad_50_52 = 0;
		$cantidad_edad_53_55 = 0;
		$cantidad_edad_55_mas = 0;
		
				
		foreach ($this->afiliadosEstadisticas as $afiliado){
			if ($afiliado->edad >= 18 && $afiliado->edad <= 20){
				$cantidad_edad_18_20 = $cantidad_edad_18_20 + 1;
			} 
			else if ($afiliado->edad >= 21 && $afiliado->edad <= 23){
				$cantidad_edad_21_23++;
			}
			else if ($afiliado->edad >= 24 && $afiliado->edad <= 26){
				$cantidad_edad_24_26++;
			}
			else if ($afiliado->edad >= 27 && $afiliado->edad <= 29){
				$cantidad_edad_27_29++;
			}
			else if ($afiliado->edad >= 30 && $afiliado->edad <= 32){
				$cantidad_edad_30_32++;
			}
			else if ($afiliado->edad >= 33 && $afiliado->edad <= 35){
				$cantidad_edad_33_35++;
			}
			else if ($afiliado->edad >= 36 && $afiliado->edad <= 38){
				$cantidad_edad_36_38++;
			}
			else if ($afiliado->edad >= 39 && $afiliado->edad <= 41){
				$cantidad_edad_39_41++;
			}
			else if ($afiliado->edad >= 42 && $afiliado->edad <= 43){
				$cantidad_edad_42_43++;
			}
			else if ($afiliado->edad >= 44 && $afiliado->edad <= 46){
				$cantidad_edad_44_46++;
			}
			else if ($afiliado->edad >= 47 && $afiliado->edad <= 49){
				$cantidad_edad_47_49++;
			}
			else if ($afiliado->edad >= 50 && $afiliado->edad <= 52){
				$cantidad_edad_50_52++;
			}	
			else if ($afiliado->edad >= 53 && $afiliado->edad <= 55){
				$cantidad_edad_53_55++;
			}
			else $cantidad_edad_55_mas++;
		}
		
		$this->template->set("cantidad_edad_18_20",$cantidad_edad_18_20);
		$this->template->set("cantidad_edad_21_23",$cantidad_edad_21_23);
		$this->template->set("cantidad_edad_24_26",$cantidad_edad_24_26);
		$this->template->set("cantidad_edad_27_29",$cantidad_edad_27_29);
		$this->template->set("cantidad_edad_30_32",$cantidad_edad_30_32);
		$this->template->set("cantidad_edad_33_35",$cantidad_edad_33_35);
		$this->template->set("cantidad_edad_36_38",$cantidad_edad_36_38);
		$this->template->set("cantidad_edad_39_41",$cantidad_edad_39_41);
		$this->template->set("cantidad_edad_42_43",$cantidad_edad_42_43);
		$this->template->set("cantidad_edad_44_46",$cantidad_edad_44_46);
		$this->template->set("cantidad_edad_47_49",$cantidad_edad_47_49);
		$this->template->set("cantidad_edad_50_52",$cantidad_edad_50_52);
		$this->template->set("cantidad_edad_53_55",$cantidad_edad_53_55);
		$this->template->set("cantidad_edad_55_mas",$cantidad_edad_55_mas);
	
		$cantidad_solteros = 0;//1
		$cantidad_casados = 0;//2
		$cantidad_union_libre = 0;//5
		$cantidad_divorciados = 0;//3
		$cantidad_viudos = 0;//4
		$cantidad_total_estado_civil = 0;
		$porcentaje_solteros = 0;
		$porcentaje_casados = 0;
		$porcentaje_union_libre = 0;
		$porcentaje_divorciados = 0;
		$porcentaje_viudos = 0;
		$porcentaje_total_estado_civil = 0;
		
		foreach ($this->afiliadosEstadisticas as $afiliado){
			if ($afiliado->id_edo_civil==1){
				$cantidad_solteros++;
			}
			else if ($afiliado->id_edo_civil==2){
				$cantidad_casados++;
			}
			else if ($afiliado->id_edo_civil==3){
				$cantidad_divorciados++;
			}
			else if ($afiliado->id_edo_civil==4){
				$cantidad_viudos++;
			}
			else if ($afiliado->id_edo_civil==5){
				$cantidad_union_libre++;
			}
		}
		$cantidad_total_estado_civil = $cantidad_solteros +	$cantidad_casados + $cantidad_union_libre + $cantidad_divorciados + $cantidad_viudos;
		
		if($cantidad_total_estado_civil==0)
			$porcentaje_total=0;
		else
			$porcentaje_total_estado_civil = 100/$cantidad_total_estado_civil;
		
		$porcentaje_solteros = round($porcentaje_total_estado_civil*$cantidad_solteros,1, PHP_ROUND_HALF_UP);
		$porcentaje_casados = round($porcentaje_total_estado_civil*$cantidad_casados,1, PHP_ROUND_HALF_UP);
		$porcentaje_union_libre = round($porcentaje_total_estado_civil*$cantidad_union_libre,1, PHP_ROUND_HALF_UP);
		$porcentaje_divorciados = round($porcentaje_total_estado_civil*$cantidad_divorciados,1, PHP_ROUND_HALF_UP);
		$porcentaje_viudos = round($porcentaje_total_estado_civil*$cantidad_viudos,1, PHP_ROUND_HALF_UP);
		
		$this->template->set("porcentaje_solteros",$porcentaje_solteros);
		$this->template->set("porcentaje_casados",$porcentaje_casados);
		$this->template->set("porcentaje_union_libre",$porcentaje_union_libre);
		$this->template->set("porcentaje_divorciados",$porcentaje_divorciados);
		$this->template->set("porcentaje_viudos",$porcentaje_viudos);
		
		$cantidad_estudiantes = 0;//1
		$cantidad_amas_de_casa = 0;//2
		$cantidad_empleados = 0;//3
		$cantidad_negocio_propio = 0;//4
		
		foreach ($this->afiliadosEstadisticas as $afiliado){
			if ($afiliado->id_ocupacion==1){
				$cantidad_estudiantes++;
			}
			else if ($afiliado->id_ocupacion==2){
				$cantidad_amas_de_casa++;
			}
			else if ($afiliado->id_ocupacion==3){
				$cantidad_empleados++;
			}
			else if ($afiliado->id_ocupacion==4){
				$cantidad_negocio_propio++;
			}
		}
		
		$this->template->set("cantidad_estudiantes",$cantidad_estudiantes);
		$this->template->set("cantidad_amas_de_casa",$cantidad_amas_de_casa);
		$this->template->set("cantidad_empleados",$cantidad_empleados);
		$this->template->set("cantidad_negocio_propio",$cantidad_negocio_propio);
		
		$cantidad_tiempo_completo = 0;
		$cantidad_medio_tiempo = 0;
		$cantidad_total_tiempo_dedicado = 0;
		$porcentaje_tiempo_completo = 0;
		$porcentaje_medio_tiempo = 0;
		$porcentaje_total_tiempo_dedicado = 0;
		
		foreach ($this->afiliadosEstadisticas as $afiliado){
			if ($afiliado->id_tiempo_dedicado==1){
				$cantidad_tiempo_completo++;
			}
			else if ($afiliado->id_tiempo_dedicado==2){
				$cantidad_medio_tiempo++;
			}
		}
		$cantidad_total_tiempo_dedicado = $cantidad_tiempo_completo + $cantidad_medio_tiempo;

		if($porcentaje_total_tiempo_dedicado==0)
			$porcentaje_total_tiempo_dedicado=0;
		else
			$porcentaje_total_tiempo_dedicado = 100/$cantidad_total_sexo;

		
		$porcentaje_tiempo_completo = round($porcentaje_total_tiempo_dedicado*$cantidad_tiempo_completo,1, PHP_ROUND_HALF_UP);
		$porcentaje_medio_tiempo = round($porcentaje_total_tiempo_dedicado*$cantidad_medio_tiempo,1, PHP_ROUND_HALF_UP);
		
		$this->template->set("porcentaje_tiempo_completo",$porcentaje_tiempo_completo);
		$this->template->set("porcentaje_medio_tiempo",$porcentaje_medio_tiempo);
		
		
		$q=$this->db->query("SELECT id, frontal ,profundidad ,nombre FROM tipo_red");
		$redes=$q->result();
		
		$patas=array();
		
		foreach ($redes as $red){

			$patas=$this->getCantidadDeAfiliadosPorPatas($patas,$id, $red);
		}

		$this->template->set("patas",$patas);
		
		$total_puntos_red=0;$total_puntos_red_mes=0;$total_ventas_red=0;$total_ventas_red_mes=0;
		foreach ($patas as $pata){
			$total_puntos_red=$total_puntos_red+$pata["total_puntos"];
			$total_puntos_red_mes=$total_puntos_red_mes+$pata["total_puntos_mes"];
			
			$total_ventas_red=$total_ventas_red+$pata["total_ventas_red"];
			$total_ventas_red_mes=$total_ventas_red_mes+$pata["total_ventas_mes"];
		}
		
		$remanentes=array();
		
		foreach ($redes as $red){
			
			for($i=1; $i<=$red->frontal ;$i++){
				$q=$this->db->query("SELECT total FROM comisionPuntosRemanentes where id_usuario=".$id." and id_pata=".$i." order by id desc limit 0,1");
				$totalPata=$q->result();
				$totalRemanente=0;

				if($totalPata!=NULL)
					$totalRemanente=$totalPata[0]->total;
				 
				$remanente = array(
						'id_red' => $red->id,
						'nombre_red' => $red->nombre,
						'id_pata' => $i,
						'total'   => $totalRemanente
				
				);
				
				array_push($remanentes, $remanente);

			}
		}

		$this->template->set("remanentes",$remanentes);
		
		$this->template->set("total_puntos_red",$total_puntos_red);
		$this->template->set("total_puntos_red_mes",$total_puntos_red_mes);
		$this->template->set("total_ventas_red",$total_ventas_red);
		$this->template->set("total_ventas_red_mes",$total_ventas_red_mes);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/estadisticas');	
	}
	
	
	private function getCantidadDeAfiliadosPorPatas($patas,$id_afiliado,$red){
		$frontalidad=$red->frontal;
		$usuario=new $this->afiliado;
		
		if($red->profundidad==0)
			$profundidad=0;
		else
			$profundidad=($red->profundidad-1);
		 $usuario->getAfiliadosDebajoDe($id_afiliado,1,"RED",1,1);
		if($frontalidad==0)
			$frontalidad =  sizeof($usuario->getIdAfiliadosRed());
		
		for ($i=1;$i<=$frontalidad;$i++){
		
			// Afiliados Totales
			$posicionEnRed=$i-1;
			$usuario=new $this->afiliado;
			$usuario->setIdAfiliadosRed(array());
			$id_hijo=$usuario->getAfiliadoDirectoPorPosicion($id_afiliado,$red->id,$posicionEnRed);
			
			if($id_hijo==null)
				$total_afiliados=0;
			else{
				$usuario->getAfiliadosDebajoDe($id_hijo,$red->id,"RED",$profundidad,$profundidad);
				$total_afiliados=(count($usuario->getIdAfiliadosRed())+1);
			}
			
			//Puntos Totales
			$usuario=new $this->afiliado;
			$puntosHijo=$usuario->getPuntosTotalesPersonalesIntervalosDeTiempo($id_hijo,$red->id,"0","0","2016-01-01","2026-01-01");
			$puntosRedHijo=$usuario->getVentasTodaLaRedEquilibrada($id_hijo,$red->id,0,$profundidad,"2016-01-01","2026-01-01",$profundidad,"0","0","PUNTOS");
			$puntosTotales=$puntosHijo+$puntosRedHijo;
				
			$calculador=new $this->calculador_bono;
		
				
			$inicioMes=$calculador->getInicioMes(date('Y-m-d'));
			$finMes=$calculador->getFinMes(date('Y-m-d'));
			//Puntos Mes
			$usuario=new $this->afiliado;
			$puntosHijoMes=$usuario->getPuntosTotalesPersonalesIntervalosDeTiempo($id_hijo,$red->id,"0","0",$inicioMes,$finMes);
			$puntosRedHijoMes=$usuario->getVentasTodaLaRedEquilibrada($id_hijo,$red->id,0,$profundidad,$inicioMes,$finMes,$profundidad,"0","0","PUNTOS");
			$puntosTotalesMes=$puntosHijoMes+$puntosRedHijoMes;
		
			//ventas Totales
			$usuario=new $this->afiliado;
			$ventasHijo=$usuario->getValorTotalDelasComprasPersonalesIntervalosDeTiempo($id_hijo,$red->id,"0","0","2016-01-01","2026-01-01");
			$ventasRedHijo=$usuario->getVentasTodaLaRedEquilibrada($id_hijo,$red->id,0,$profundidad,"2016-01-01","2026-01-01",$profundidad,"0","0","COSTO");
			$ventasTotales=$ventasHijo+$ventasRedHijo;
				
			$calculador=new $this->calculador_bono;
		
				
			$inicioMes=$calculador->getInicioMes(date('Y-m-d'));
			$finMes=$calculador->getFinMes(date('Y-m-d'));
			//ventas Mes
			$usuario=new $this->afiliado;
			$ventasHijoMes=$usuario->getValorTotalDelasComprasPersonalesIntervalosDeTiempo($id_hijo,$red->id,"0","0",$inicioMes,$finMes);
				
			$ventasRedHijoMes=$usuario->getVentasTodaLaRedEquilibrada($id_hijo,$red->id,0,$profundidad,$inicioMes,$finMes,$profundidad,"0","0","COSTO");
			$ventasTotalesMes=$ventasHijoMes+$ventasRedHijoMes;
		
			$pata = array(
					'id_red' => $red->id,
					'nombre_red' => $red->nombre,
					'id_pata' => $i,
					'total_afiliados'   => $total_afiliados,
					'total_puntos'   => $puntosTotales ,
					'total_puntos_mes'   => $puntosTotalesMes,
					'total_ventas_red'   => $ventasTotales ,
					'total_ventas_mes'   => $ventasTotalesMes
						
			);
		
			array_push($patas, $pata);
		}

		return $patas;
	}
		
	function reportes()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/reportes');
	}
	
	function tickets()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// 		logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);

		$style=$this->general->get_style($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/tickets/listar');
	}

	function carrito_menu()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		if(isset($_GET['transactionState'])){
			if($_GET['transactionState'] == '4'){
				$exito = "The transaction has been successfully completed.";
				$this->session->set_flashdata('Success', $exito);
			}elseif($_GET['transactionState'] == '5'){
				$error = "The transaction has been rejected (Declined).";
				$this->session->set_flashdata('error', $error);
			}else{
				$error = "The transaction expired.";
				$this->session->set_flashdata('error', $error);
			}
			$extra1 = explode("-", $_GET['extra1']);
			$id_mercancia = $extra1[0];
			$producto_continua = array();
			foreach ($this->cart->contents() as $producto){
					
				if($producto['id'] != $id_mercancia){
					$add_cart = array(
							'id'      => $producto['id'],
							'qty'     => $producto['qty'],
							'price'   => $producto['price'],
							'name'    => $producto['name'],
							'options' => $producto['options']
					);
					$producto_continua[] = $add_cart;
				}
			}
			$this->cart->destroy();
			$this->cart->insert($producto_continua);
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
		$afiliados=$this->modelo_compras->get_afiliados($id);
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		$data['afiliados']=$afiliados;
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/compra_reporte/menu_carro',$data);
	}
	
	function preOrden($id){
			
		$datos = $this->modelo_compras->traer_afiliados($id);
		
		foreach ($datos as $dato){
			if ($dato!=NULL){
				array_push($this->afiliados, $dato);
				$this->preOrden($dato->id_afiliado);
			}
			
		}
	}
	
	function preOrdenRed($id,$id_red,$frontalidad,$profundidad){

		$datos = $this->modelo_compras->traer_afiliados_red_frontalidad_profundidad($id,$id_red,$frontalidad);

		foreach ($datos as $dato){
			
			if (($dato!=NULL)&&($profundidad>0)){
				array_push($this->afiliados, $dato);
				$this->preOrdenRed($dato->id_afiliado,$id_red,$frontalidad,$profundidad-1);
			}			
		}
		$profundidad++;
	}
	
	function preOrdenRedProfundidadInfinita($id,$id_red,$frontalidad){
	
		$datos = $this->modelo_compras->traer_afiliados_red_frontalidad_profundidad($id,$id_red,$frontalidad);
	
		foreach ($datos as $dato){
			if (($dato!=NULL)){
				array_push($this->afiliados, $dato);
				$this->preOrdenRedProfundidadInfinita($dato->id_afiliado,$id_red,$frontalidad);
			}
		}
	}
	
	function reportes_tipo (){

        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 8;
        switch ($tipo){
			case 1 	: $this->reporte_afiliados(); break;
			case 2 	: $this->reporte_compras_usr();  
						$this->reporte_compras_usr_well(); break;
			case 3 	: $this->reporte_compras(); 
						$this->reporte_compras_red_well();break;
			case 4	: $this->reporte_compras_personales_cedi(); break;
			case 5	: $this->ReportePagosBanco(); break;
			case 6	: $this->reporte_afiliados_todos(); break;
			case 7 	: $this->reporte_compras_afiliados_todos(); break;
			case 8 	: $this->reporte_compras_personales(); break;
			case 9 	: $this->reporte_directos(); break;
			case 10 	: $this->reporte_afiliados_activos(); break;
			case 11 	: $this->reporte_afiliados_inactivos(); break;
			case 12 	: $this->reporte_bonos_afiliados_todos(); break;
		}
		
	}
	function reporte_directos() {
		//echo "dentro de reporte directos";
		$id=$this->tank_auth->get_user_id();
		$afiliados = $this->model_perfil_red->get_directos_by_id($id);
		$image=$this->model_perfil_red->get_images_users();
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
						<th>ID</th>
		                <th data-class='expand'>Image</th>
		                <th data-hide='phone'>User</th>
			            <th data-hide='phone,tablet'>Name</th>
			            <th data-hide='phone,tablet'>Surname</th>
				        <th data-hide='phone,tablet'>Email</th>
				        <th data-hide='phone'>Network</th>
				</thead>
				<tbody>";
		foreach ($afiliados as $afiliado)
		{
		 			$afiliados_imagen="/template/img/empresario.jpg";
				       foreach ($image as $img) {
				       	   if($img->id_user==$afiliado->id){
								$cadena=explode(".", $img->img);
								if($cadena[0]=="user")
								{
									$afiliados_imagen=$img->url;
								}
				       	   }
						}
		
			echo "<tr>
					<td class='sorting_1'>".$afiliado->id."</td>
					<td><img style='width: 10rem; height: 10rem;' src='".$afiliados_imagen."'></img></td>
					<td>".$afiliado->username."</td>
					<td>".$afiliado->nombre."</td>
					<td>".$afiliado->apellido."</td>
					<td>".$afiliado->email."</td>
					<td>".$afiliado->redes."</td>
				</tr>";
		}		
			
		echo "</tbody>
			</table><tr class='odd' role='row'>";
	}
	function reporte_afiliados_todos()
	{
		$id=$this->tank_auth->get_user_id();
		$redesUsuario=$this->model_tipo_red->cantidadRedesUsuario($id);
			
		foreach ($redesUsuario as $redUsuario){
			$red = $this->model_tipo_red->ObtenerFrontalesRed($redUsuario->id );
	
			if($red){
			
				if($red[0]->profundidad==0)
					$this->preOrdenRedProfundidadInfinita($id,$redUsuario->id,$red[0]->frontal);
				else
					$this->preOrdenRed($id,$redUsuario->id,$red[0]->frontal,$red[0]->profundidad);
			}
		}
		
		echo 
			"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th>ID</th>
					<th>Name</th>
					<th data-hide='phone'>Email</th>
				</thead>
				<tbody>";
			foreach ($this->afiliados as $afiliado)
			{
			$contador = 0;
				
					echo "<tr>
					<td class='sorting_1'>".$afiliado->id_afiliado."</td>
					<td>".$afiliado->nombre."</td>
					<td>".$afiliado->email."</td>
				</tr>";
			}
				
			
			echo "</tbody>
			</table><tr class='odd' role='row'>";
		
		
	}
	
	function reporte_afiliados_todos_excel()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		
		$this->preOrden($id);
		
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_afiliados_todos.xls");
		for($i = 0;$i < count($this->afiliados);$i++)
		{
			$telefonos = array();
			$telefonos_usuario = "";
			$telefonos = $this->modelo_compras->traer_telefonos($this->afiliados[$i]->id_afiliado);
			$contador = 0;
			
			foreach ($telefonos as $key){
				$contador = $contador+1;
				if ($key->numero!=""){
					if ($contador==1){
						$telefonos_usuario = $key->numero;
					}
					else $telefonos_usuario = $telefonos_usuario.", ".$key->numero;
				}
				else ;
			}
			
			if ($telefonos_usuario==""){
				$telefonos_usuario = " --- ";
			}
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), $this->afiliados[$i]->id_afiliado);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $this->afiliados[$i]->nombre);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $this->afiliados[$i]->email);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $telefonos_usuario);
		}
	
		$filename='Consecutivo_de_mi_red.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		//$objWriter->save(getcwd()."/media/reportes/".$filename);
		$objWriter->save('php://output');
	}
	
	function reporte_compras_afiliados_todos()
	{
		$id=$this->tank_auth->get_user_id();

		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];
		
		$id=$this->tank_auth->get_user_id();
		$redesUsuario=$this->model_tipo_red->cantidadRedesUsuario($id);
			
		foreach ($redesUsuario as $redUsuario){
			$red = $this->model_tipo_red->ObtenerFrontalesRed($redUsuario->id );
		
			if($red){
					
				if($red[0]->profundidad==0)
					$this->preOrdenRedProfundidadInfinita($id,$redUsuario->id,$red[0]->frontal);
				else
					$this->preOrdenRed($id,$redUsuario->id,$red[0]->frontal,$red[0]->profundidad);
			}
		}
		
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th>ID</th>
					<th>Name</th>
					<th data-hide='phone'>Email</th>
					<th data-hide='phone,tablet'>Purchases</th>
					<th data-hide='phone,tablet'>Taxes</th>
					<th data-hide='phone,tablet'>Total</th>
				</thead>
				<tbody>";
		foreach ($this->afiliados as $afiliado)
		{

			$total_venta = 0;
			$total_costo = 0;
			$total_impuesto = 0;
			
			$ventas = $this->model_servicio->listar_todos_por_venta_y_fecha_usuario($inicio,$fin,$afiliado->id_afiliado);
                        $cedi = $this->model_servicio->listar_cedi_personales($inicio,$fin,$afiliado->id_afiliado);
                        
			foreach($ventas as $venta)
			{
					
				$total_costo = $total_costo + ($venta->costo-$venta->impuestos);
				$total_impuesto = $total_impuesto + $venta->impuestos;
				$total_venta = $total_venta  + $venta->costo;	
			}
			
                         foreach ($cedi as $venta ){
                
                                $total_costo +=$venta->costo ;
                                $total_impuesto += $venta->impuestos;
                                $total_venta += ($venta->costo + $venta->impuestos);               

                            }
                        
			echo "<tr>
					<td class='sorting_1'>".$afiliado->id_afiliado."</td>
					<td>".$afiliado->nombre."</td>
					<td>".$afiliado->email."</td>
					<td>$ ".number_format($total_costo, 2, '.', '')."</td>
					<td>$ ".number_format($total_impuesto, 2, '.', '')."</td>
					<td>$ ".number_format($total_venta, 2, '.', '')."</td>
				</tr>";
		}
	
			
		echo "</tbody>
			</table><tr class='odd' role='row'>";
	
	}
	
	function reporte_compras_afiliados_todos_excel()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		
		$inicio = $_GET['inicio'];
		$fin = $_GET['fin'];
		$this->preOrden($id);
	
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_compras_afiliados_todos.xls");
		for($i = 0;$i < count($this->afiliados);$i++)
		{
		$telefonos = array();
		$telefonos_usuario = "";
		$telefonos = $this->modelo_compras->traer_telefonos($this->afiliados[$i]->id_afiliado);
		$contador = 0;
			
		foreach ($telefonos as $key){
		$contador = $contador+1;
		if ($key->numero!=""){
		if ($contador==1){
			$telefonos_usuario = $key->numero;
			}
			else $telefonos_usuario = $telefonos_usuario.", ".$key->numero;
		}
		else ;
		}
			
		if ($telefonos_usuario==""){
		$telefonos_usuario = " --- ";
		}
		
		$compras = 0;
		$compras = $this->modelo_compras->traer_compras($this->afiliados[$i]->id_afiliado, $inicio, $fin);
			
		$compras_impresion = "$ ".$compras[0]->compras;
			
		if ($compras[0]->compras==NULL){
			$compras_impresion = "$ 0.00";
		}
		
		$impuestos = 0;
		$impuestos = $this->modelo_compras->traer_impuestos($this->afiliados[$i]->id_afiliado, $inicio, $fin);
		
		$impuestos_impresion = "$ ".$impuestos[0]->impuestos;
		
		if ($impuestos[0]->impuestos==NULL){
			$impuestos_impresion = "$ 0.00";
		}
		
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), $this->afiliados[$i]->id_afiliado);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $this->afiliados[$i]->nombre);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $this->afiliados[$i]->email);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $telefonos_usuario);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i+8), $compras_impresion);
	}
	
	$filename='Compras_de_mi_red_desde_'.$inicio.'_hasta_'.$fin.'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
	
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//force user to download the Excel file without writing it to server's HD
			//$objWriter->save(getcwd()."/media/reportes/".$filename);
			$objWriter->save('php://output');
	}
	
	function reporte_compras_personales()
	{
		$total_venta = 0;
		$total_costo = 0;
		$total_impuesto = 0;
		$total_comision = 0;
		
		$id=$this->tank_auth->get_user_id();
		
		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];
		
		$ventas = $this->model_servicio->listar_todos_por_venta_y_fecha_usuario($inicio,$fin,$id);
		
		
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID Sale</th>
					<th data-hide='phone,tablet'>Date</th>
					<th data-hide='phone,tablet'>Name</th>
					<th data-hide='phone,tablet'>Surname</th>
					<th data-hide='phone,tablet'>Subtotal</th>
					<th data-hide='phone,tablet'>Taxes</th>
					<th data-hide='phone,tablet'>Total Sale</th>
					<th data-hide='phone,tablet'>Invoice</th>
				</thead>
				<tbody>";
		
		if ($inicio!=""){
			foreach($ventas as $venta)
			{
				echo "<tr>
			<td class='sorting_1'>".$venta->id_venta."</td>
			<td>".$venta->fecha."</td>
			<td>".$venta->name."</td>
			<td>".$venta->lastname."</td>
			<td> $	".number_format(($venta->costo-$venta->impuestos), 2, '.', '')."</td>
			<td> $	".number_format($venta->impuestos, 2, '.', '')."</td>
			<td> $	".number_format($venta->costo, 2, '.', '')."</td>
			<td>				<a title='Invoice' style='cursor: pointer;' class='txt-color-blue' onclick='factura(".$venta->id_venta.");'>
				<i class='fa fa-eye fa-3x'></i>
				</a>
					<a title='Print' style='cursor: pointer;' class='txt-color-green' onclick='imprimir(".$venta->id_venta.");'>
				<i class='fa fa-file-pdf-o fa-3x'></i>
				</a>
				</td>
			</tr>";
					
				$total_costo = $total_costo + ($venta->costo-$venta->impuestos);
				$total_impuesto = $total_impuesto + $venta->impuestos;
				$total_venta = $total_venta  + $venta->costo;
				$total_comision = $total_comision + $venta->comision;
					
			}
		
			echo "<tr>
			<td class='sorting_1'></td> <td></td> <td></td>
			<td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>";
				
			echo "<tr>
			<td class='sorting_1'><b>TOTALES</b></td> <td></td> <td></td> <td></td>
			<td><b> $	".number_format($total_costo, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_impuesto, 2, '.', '')."</b></td>
			<td><b> $	".number_format($total_venta, 2, '.', '')."</b></td>
			<td></td>
			</tr>";
		}
		echo "</tbody>
		</table><tr class='odd' role='row'>";

	}
        
        function reporte_compras_personales_cedi()
	{
		$total_venta = 0;
		$total_costo = 0;
		$total_impuesto = 0;
		$total_comision = 0;
		
		$id=$this->tank_auth->get_user_id();
		
		$inicio = $_POST['inicio'] ? $_POST['inicio'] : date('Y-m').'-01';
		$fin = $_POST['fin'] ? $_POST['fin'] : date('Y-m-d');
		
		$ventas = $this->model_servicio->listar_cedi_personales($inicio,$fin,$id);
		
		
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID Sale</th>
					<th data-hide='phone,tablet'>Date</th>
					<th data-hide='phone,tablet'>User</th>
					<th data-hide='phone,tablet'>Full name</th>
					<th data-hide='phone,tablet'>Subtotal</th>
					<th data-hide='phone,tablet'>Taxes</th>
					<th data-hide='phone,tablet'>Total Sale</th>
					<th data-hide='phone,tablet'>Points</th>
                                        <th >Actions</th>
				</thead>
				<tbody>";
		
			foreach($ventas as $venta)
			{
			echo "<tr>
                                    <td class='sorting_1'>".$venta->id_venta."</td>
                                    <td>".$venta->fecha."</td>
                                    <td>".$venta->username."</td>
                                    <td>".$venta->name." ".$venta->lastname."</td>
                                    <td> $ ".number_format(($venta->costo), 2)."</td>
                                    <td> $ ".number_format($venta->impuestos, 2)."</td>
                                    <td> $ ".number_format($venta->costo+$venta->impuestos, 2)."</td>
                                    <td>".$venta->puntos."</td>
                                    <td>	
                                        <a title='Invoice' style='cursor: pointer;' class='txt-color-blue' onclick='factura_cedi(".$venta->id_venta.");'>
                                            <i class='fa fa-eye fa-3x'></i>
                                        </a>
                                        <a title='Print' style='cursor: pointer;' class='txt-color-green' onclick='imprimir_cedi(".$venta->id_venta.");'>
                                            <i class='fa fa-file-pdf-o fa-3x'></i>
                                        </a>
                                    </td>
                                </tr>";
					
				$total_costo += $venta->costo;
				$total_impuesto += $venta->impuestos;
				$total_venta += ($venta->costo+$venta->impuestos);
				$total_comision += $venta->puntos;
					
			}
		
			echo "<tr>
			<td class='sorting_1'></td> <td></td> <td></td> <td></td> <td></td>
			<td></td> <td></td> <td></td> <td></td>
                        </tr>";
				
			echo "<tr>
			<td class='sorting_1'><b>TOTALS</b></td> <td></td> <td></td> <td></td>
			<td><b> $ ".number_format($total_costo, 2)."</b></td>
			<td><b> $ ".number_format($total_impuesto, 2)."</b></td>
			<td><b> $ ".number_format($total_venta, 2)."</b></td>
                        <td><b> ".$total_comision."</b></td> <td></td>
			</tr>";
		
		echo "</tbody>
		</table><tr class='odd' role='row'>";
                
	}
	
	function reporte_compras_personales_excel()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
		$inicio = $_GET['inicio'];
		$fin = $_GET['fin'];
		
		$productos = $this->modelo_compras->traer_mis_compras_productos($id, $inicio, $fin);
		$servicios = $this->modelo_compras->traer_mis_compras_servicios($id, $inicio, $fin);
		$combinados = $this->modelo_compras->traer_mis_compras_combinados($id, $inicio, $fin);
		$costo_total = 0;

		$contador_filas = 0;
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_compras_personales.xls");
		for($i = 0;$i < count($productos);$i++)
		{
				$contador_filas = $contador_filas+1;
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($contador_filas+8), $productos[$i]->red);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($contador_filas+8), $productos[$i]->nombre);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($contador_filas+8), $productos[$i]->costo_unitario);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($contador_filas+8), $productos[$i]->cantidad);
				$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($contador_filas+8), $productos[$i]->costo);
				$costo_total = $costo_total + $productos[$i]->costo;
		}
		for($i = 0;$i < count($servicios);$i++)
		{
			$contador_filas = $contador_filas+1;
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($contador_filas+8), $servicios[$i]->red);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($contador_filas+8), $servicios[$i]->nombre);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($contador_filas+8), $servicios[$i]->costo_unitario);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($contador_filas+8), $servicios[$i]->cantidad);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($contador_filas+8), $servicios[$i]->costo);
		$costo_total = $costo_total + $servicios[$i]->costo;
		}
		for($i = 0;$i < count($combinados);$i++)
		{
			$contador_filas = $contador_filas+1;
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($contador_filas+8), $combinados[$i]->red);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($contador_filas+8), $combinados[$i]->nombre);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($contador_filas+8), $combinados[$i]->costo_unitario);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($contador_filas+8), $combinados[$i]->cantidad);
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($contador_filas+8), $combinados[$i]->costo);
		$costo_total = $costo_total + $combinados[$i]->costo;
		}
		$contador_filas = $contador_filas+1;
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($contador_filas+8), "TOTAL");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($contador_filas+8), "");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($contador_filas+8), "");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($contador_filas+8), "");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($contador_filas+8), $costo_total);
		
		$filename='mis_compras_desde_'.$inicio.'_hasta_'.$fin.'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		//$objWriter->save(getcwd()."/media/reportes/".$filename);
		$objWriter->save('php://output');
	}
	function reporte_afiliados()
	{
		$id=$this->tank_auth->get_user_id();
		$afiliados=$this->modelo_compras->reporte_afiliados($id);
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID</th>
					<th data-hide='phone,tablet'>Network</th>
					<th >Registration date</th>
					<th >User</th>
					<th >Name</th>
					<th >Surname</th>
					<th data-hide='phone,tablet'>Email</th>
					<th data-hide='phone,tablet'>Gender</th>
					<th data-hide='phone,tablet'>Civil status</th>
				</thead>
				<tbody>";
		for($i=0;$i<sizeof($afiliados);$i++)
		{
		echo "<tr>
		<td class='sorting_1'>".$afiliados[$i]->id."</td>
		<td >".$afiliados[$i]->nombreRed."</td>
		<td>".$afiliados[$i]->creacion."</td>
		<td>".$afiliados[$i]->usuario."</td>
		<td>".$afiliados[$i]->nombre."</td>
		<td>".$afiliados[$i]->apellido."</td>
		<td>".$afiliados[$i]->email."</td>
		<td>".$afiliados[$i]->sexo."</td>
		<td>".$afiliados[$i]->edo_civil."</td>
		</tr>";
			}
	
		
			echo "</tbody>
		</table><tr class='odd' role='row'>";
	
	
	}
	
	function reporte_afiliados_activos()
	{
		$id=$this->tank_auth->get_user_id();
		
		if (!$this->tank_auth->is_logged_in())
		{												
		redirect('/auth');
		}

		$afiliados=$this->modelo_compras->reporte_afiliados_activos($id,date('Y-m-d'));
		
		echo 
			"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th>ID</th>
					<th>User</th>
					<th>Name</th>
					<th>Surname</th>
					<th>Email</th>
					<th>Activity</th>
				</thead>
				<tbody>";
			foreach($afiliados as $afiliado)
			{
					echo "<tr>
					<td class='sorting_1'>".$afiliado[0]->id."</td>
					<td>".$afiliado[0]->usuario."</td>
					<td>".$afiliado[0]->nombre."</td>
					<td>".$afiliado[0]->apellido."</td>
					<td>".$afiliado[0]->email."</td>
					<td><div class='widget-body'>
						<h2 class='alert alert-success'><i class='fa fa-thumbs-o-up'></i></h2>
						</div></td>
				</tr>";
			}
				
			
			echo "</tbody>
			</table><tr class='odd' role='row'>";
		
		
		
	}
	
	function reporte_afiliados_inactivos()
	{
		$id=$this->tank_auth->get_user_id();
		
		if (!$this->tank_auth->is_logged_in())
		{												
		redirect('/auth');
		}

		$afiliados=$this->modelo_compras->reporte_afiliados_inactivos($id,date('Y-m-d'));
		
		echo 
			"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th>ID</th>
					<th>User</th>
					<th>Name</th>
					<th>Surname</th>
					<th>Email</th>
					<th>Activity</th>
				</thead>
				<tbody>";
			foreach($afiliados as $afiliado)
			{
					echo "<tr>
					<td class='sorting_1'>".$afiliado[0]->id."</td>
					<td>".$afiliado[0]->usuario."</td>
					<td>".$afiliado[0]->nombre."</td>
					<td>".$afiliado[0]->apellido."</td>
					<td>".$afiliado[0]->email."</td>
					<td><div class='widget-body'>
						<h2 class='alert alert-danger'><i class='fa fa-thumbs-o-down'></i></h2>
						</div></td>
				</tr>";
			}
				
			
			echo "</tbody>
			</table><tr class='odd' role='row'>";
		
		
		
		
	}
	
	function reporte_afiliados_excel()
	{
		//load our new PHPExcel library
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte-af.xls");
		$id=$this->tank_auth->get_user_id();
		$afiliados=$this->modelo_compras->reporte_afiliados($id);
		for($i=0;$i<sizeof($afiliados);$i++)
		{
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), ($i+1));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $afiliados[$i]->creacion);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $afiliados[$i]->usuario);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $afiliados[$i]->nombre);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i+8), $afiliados[$i]->apellido);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i+8), $afiliados[$i]->nacimiento);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i+8), $afiliados[$i]->sexo);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(7, ($i+8), $afiliados[$i]->edo_civil);
		}
		
		$filename='afiliados_nuevos.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		             
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}
	function reporte_compras_usr()
	{
		$id=$this->tank_auth->get_user_id();
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$fecha_ini=str_replace('.', '-', $data['inicio']);
		$fecha_fin=str_replace('.', '-', $data['fin']);
		$ano_ini=substr($fecha_ini, 6);
		$mes_ini=substr($fecha_ini, 3,2);
		$dia_ini=substr($fecha_ini, 0,2);
		$ano_fin=substr($fecha_fin, 6);
		$mes_fin=substr($fecha_fin, 3,2);
		$dia_fin=substr($fecha_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini;
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin;
		$ventas=$this->modelo_compras->get_compras_usr($inicio,$fin,$id);
			echo 
			"<table id='datatable_fixed_column2' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID</th>
					<th data-hide='phone'>Date</th>
					<th>Cost</th>
					<th>Estatus</th>
				</thead>
				<tbody>";
				
			for($i=0;$i<sizeof($ventas);$i++)
			{
					echo "<tr>
					<td class='sorting_1'>".($i+1)."</td>
					<td>".$ventas[$i]->fecha."</td>
					<td>".$ventas[$i]->costo."</td>
					<td>".$ventas[$i]->descripcion."</td>
				</tr>";
			}
			echo "</tbody>
			</table><tr class='odd' role='row'>";
		
		
	}
	function reporte_compras_usr_well()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$fecha_ini=str_replace('.', '-', $data['inicio']);
		$fecha_fin=str_replace('.', '-', $data['fin']);
		$ano_ini=substr($fecha_ini, 6);
		$mes_ini=substr($fecha_ini, 3,2);
		$dia_ini=substr($fecha_ini, 0,2);
		$ano_fin=substr($fecha_fin, 6);
		$mes_fin=substr($fecha_fin, 3,2);
		$dia_fin=substr($fecha_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini;
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin;
		echo '<div class="row">
				<form class="smart-form" id="reporte-form" method="post">
					
					<div class="row" >
						<section class="col col-lg-6 col-md-6 hidden-sm hidden-xs">
							
						</section>
						<section class="col col-lg-3 col-md-3 col-sm-6 col-xs-12">
							
							<label class="input">
								<a id="imprimir-2" href="reporte_compras_usr_excel?inicio='.$inicio.'&fin='.$fin.'" class="btn btn-primary col-xs-12 col-lg-12 col-md-12 col-sm-12"><i class="fa fa-print"></i>&nbsp;Crear excel</a>
							</label>
						</section>
						<section class="col col-lg-3 col-md-3 col-sm-6 col-xs-12">
							
							<label class="input">
								<a id="imprimir-2" onclick="window.print()" class="btn btn-success col-xs-12 col-lg-12 col-md-12 col-sm-12"><i class="fa fa-print"></i>&nbsp;Imprimir</a>
							</label>
						</section>
						
					</div>
				</form>
			</div>';
	}
	function reporte_compras_red_well()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$fecha_ini=str_replace('.', '-', $data['inicio']);
		$fecha_fin=str_replace('.', '-', $data['fin']);
		$ano_ini=substr($fecha_ini, 6);
		$mes_ini=substr($fecha_ini, 3,2);
		$dia_ini=substr($fecha_ini, 0,2);
		$ano_fin=substr($fecha_fin, 6);
		$mes_fin=substr($fecha_fin, 3,2);
		$dia_fin=substr($fecha_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini;
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin;
		echo '<div class="row">
				<form class="smart-form" id="reporte-form" method="post">
					
					<div class="row" >
						<section class="col col-lg-6 col-md-6 hidden-sm hidden-xs">
							
						</section>
						<section class="col col-lg-3 col-md-3 col-sm-6 col-xs-12">
							
							<label class="input">
								<a id="imprimir-2" href="reporte_compras_red_excel?inicio='.$inicio.'&fin='.$fin.'" class="btn btn-primary col-xs-12 col-lg-12 col-md-12 col-sm-12"><i class="fa fa-print"></i>&nbsp;Crear excel</a>
							</label>
						</section>
						<section class="col col-lg-3 col-md-3 col-sm-6 col-xs-12">
							
							<label class="input">
								<a id="imprimir-2" onclick="window.print()" class="btn btn-success col-xs-12 col-lg-12 col-md-12 col-sm-12"><i class="fa fa-print"></i>&nbsp;Imprimir</a>
							</label>
						</section>
						
					</div>
				</form>
			</div>';
	}
	function reporte_compras_usr_excel()
	{
		$id=$this->tank_auth->get_user_id();
		//load our new PHPExcel library
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_usr.xls");
		$ventas=$this->modelo_compras->get_compras_usr($_GET['inicio'],$_GET['fin'],$id);
		for($i=0;$i<sizeof($ventas);$i++)
		{
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), ($i+1));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $ventas[$i]->fecha);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $ventas[$i]->costo);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $ventas[$i]->descripcion);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i+8), $ventas[$i]->username);
		}
		
		$filename='compras_usuario.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		             
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');	
	}
	function reporte_compras()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$fecha_ini=str_replace('.', '-', $data['inicio']);
		$fecha_fin=str_replace('.', '-', $data['fin']);
		$ano_ini=substr($fecha_ini, 6);
		$mes_ini=substr($fecha_ini, 3,2);
		$dia_ini=substr($fecha_ini, 0,2);
		$ano_fin=substr($fecha_fin, 6);
		$mes_fin=substr($fecha_fin, 3,2);
		$dia_fin=substr($fecha_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini;
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin;
		$id=$this->tank_auth->get_user_id();
		$red=$this->modelo_compras->get_red($id);
		$ventas=$this->modelo_compras->get_compras($inicio,$fin,$red[0]->id_red);
			echo 
			"<table id='datatable_fixed_column3' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID</th>
					<th data-hide='phone,tablet'>Date</th>
					<th data-hide='phone'>Cost</th>
					<th>Estatus</th>
					<th>User</th>
				</thead>
				<tbody>";
				
			for($i=0;$i<sizeof($ventas);$i++)
			{
					echo "<tr>
					<td class='sorting_1'>".($i+1)."</td>
					<td>".$ventas[$i]->fecha."</td>
					<td>".$ventas[$i]->costo."</td>
					<td>".$ventas[$i]->descripcion."</td>
					<td>".$ventas[$i]->username."</td>
					
				</tr>";
			}
			echo "</tbody>
			</table><tr class='odd' role='row'>";
		
		
	}
	function reporte_compras_red_excel()
	{
		//load our new PHPExcel library
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_red.xls");
		$id=$this->tank_auth->get_user_id();
		$red=$this->modelo_compras->get_red($id);
		$ventas=$this->modelo_compras->get_compras($_GET['inicio'],$_GET['fin'],$red[0]->id_red);
		for($i=0;$i<sizeof($ventas);$i++)
		{
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), ($i+1));
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $ventas[$i]->fecha);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $ventas[$i]->costo);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $ventas[$i]->descripcion);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i+8), $ventas[$i]->username);
		}
		
		$filename='compras_red.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		             
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');	
	}
	
	function ReportePagosBanco(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];
		$id=$this->tank_auth->get_user_id();
		$cobros = $this->modelo_historial_consignacion->ConsultarPagosBanco($id, $inicio, $fin);
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>
					<th >ID</th>
					<th data-class='expand'>Date</th>
					<th>Bank</th>
					<th data-hide='phone'>N° Account</th>
					<th data-hide='phone'>KEY</th>
					<th data-hide='phone'>SWIFT</th>
					<th data-hide='phone'>ABA/IBAN/OTHER</th>
					<th data-hide='phone'>Postal address</th>
					<th data-hide='phone,tablet'>Amount</th>
					<th data-hide='phone,tablet'>State</th>
				</thead>
				<tbody>";
		for($i=0;$i < sizeof($cobros);$i++)
		{
		echo "<tr>
			<td class='sorting_1'>".$cobros[$i]->id."</td>
			<td>".$cobros[$i]->fecha."</td>
			<td>".$cobros[$i]->banco."</td>
			<td>".$cobros[$i]->cuenta."</td>
			<td>".$cobros[$i]->clave."</td>
			<td>".$cobros[$i]->swift."</td>
			<td>".$cobros[$i]->otro."</td>
			<td>".$cobros[$i]->dir_postal."</td>
			<td>$ ".number_format($cobros[$i]->valor,2)."</td>";
		if($cobros[$i]->estado=='ACT')
			echo "<td>Paid</td>";
		else
			echo "<td>Pending</td>";
		echo "</tr>";
			
		}
		
		
		echo "</tbody> </table> <tr class='odd' role='row'>";
	}
	
	function reporte_pagos_banco_excel()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$inicio = $_GET['inicio'];
		$fin = $_GET['fin'];
		$cobros = $this->modelo_historial_consignacion->ConsultarPagosBanco($id,$inicio,$fin);
	
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte-pagos_banco.xls");
	
		$total = 0;
		$ultima_fila = 0;
		for($i = 0;$i < sizeof($cobros);$i++)
		{
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($i+8), $cobros[$i]->id);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, ($i+8), $cobros[$i]->fecha);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, ($i+8), $cobros[$i]->banco);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, ($i+8), $cobros[$i]->cuenta);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, ($i+8), $cobros[$i]->clave);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(5, ($i+8), $cobros[$i]->estado);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, ($i+8), '$ '.number_format($cobros[$i]->valor,2));
			$total = $total + $cobros[$i]->valor;
			$ultima_fila = $i+8;
				
		}
	
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(0, ($ultima_fila+1), "Total");
		$this->excel->getActiveSheet()->setCellValueByColumnAndRow(6, ($ultima_fila+1), "$ ".number_format($total,2));
	
		$filename='Compras por consignacion banco.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
				//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
				//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
				//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
	}
	
	function reporte_bonos_afiliados_todos()
	{
		$id=$this->tank_auth->get_user_id();
	
		$inicio = $_POST['inicio'];
		$fin = $_POST['fin'];
	
		$redesUsuario=$this->model_tipo_red->cantidadRedesUsuario($id);
			
		foreach ($redesUsuario as $redUsuario){
			$red = $this->model_tipo_red->ObtenerFrontalesRed($redUsuario->id );
		
			if($red){
					
				if($red[0]->profundidad==0)
					$this->preOrdenRedProfundidadInfinita($id,$redUsuario->id,$red[0]->frontal);
					else
						$this->preOrdenRed($id,$redUsuario->id,$red[0]->frontal,$red[0]->profundidad);
			}
		}
		
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='100%'>
				<thead id='tablacabeza'>"
					."<th>ID historial</th>"
					."<th>User</th>"
					."<th data-hide='phone'>Full Name</th>"
					."<th data-hide='phone,tablet'>Date</th>"
					."<th data-hide='phone,tablet'>Voucher</th>"
					."<th data-hide='phone,tablet'>Value</th>"
				."</thead>
				<tbody>";
		
		$total = 0;				
		
		foreach ($this->afiliados as $afiliado){
		
			$bonos = $this->model_bonos->getBonosPagadosRed($afiliado->id_afiliado,$inicio,$fin);
						
			foreach ($bonos as $bono)
			{					
					$total += $bono->valor;	
					
				echo "<tr>"
						."<td class='sorting_1'>".$bono->id."</td>"
						."<td>".$bono->usuario."</td>"
						."<td>".$bono->afiliado."</td>"
						."<td> ".$bono->fecha."</td>"
						."<td> ".$bono->bono."</td>"
						."<td>$ ".$bono->valor
						//."|".$total
						."</td>"
					."</tr>";
			}
		}

		echo "<tr>
			<td></td> <td></td> <td></td> <td></td>
			<td class='sorting_1'><b>TOTAL:</b></td>
			<td><b> $	".number_format($total, 2)."</b></td>
			</tr>";
		
		echo "</tbody>
			</table><tr class='odd' role='row'>";
	
	}
	
	function muestra_mercancia()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$id=$data['id'];
		$tipoMercancia=$data['tipo'];
		
		$this->printDetalleMercancia($tipoMercancia, $id);

	}

	function printDetalleMercancia($id_tipo_mercancia,$id_mercancia){
		$imagenes=$this->modelo_compras->get_imagenes($id_mercancia);
		
		if($id_tipo_mercancia==1)
			$detalles=$this->modelo_compras->detalles_productos($id_mercancia);
		else if($id_tipo_mercancia==2)
			$detalles=$this->modelo_compras->detalles_servicios($id_mercancia);
		else if($id_tipo_mercancia==3)
			$detalles=$this->modelo_compras->detalles_combinados($id_mercancia);
		else if($id_tipo_mercancia==4)
			$detalles=$this->modelo_compras->detalles_paquete($id_mercancia);
		else if($id_tipo_mercancia==5)
			$detalles=$this->modelo_compras->detalles_membresia($id_mercancia);
		


            if(!$detalles){
                echo "ITEM IS NOT FOUND, TRY AGAIN.";
                return false;
            }

            $img_item = $imagenes[0]->url;

            if(!file_exists(getcwd().$img_item))
                $img_item = "/template/img/favicon/favicon.png";

            $costo_div = "";
            $categoria = isset($detalles[0]->id_red) ? $detalles[0]->id_red : $detalles[0]->id_grupo;
            $isRedimible= $categoria == 2;

            $concepto = isset($detalles[0]->concepto) ? $detalles[0]->concepto : false;
            $isVariable = $categoria == 3;

            if(!$isVariable)
                $costo_div = ' <span>$ '.$detalles[0]->costo.'</span> 
            <span class="old-price">$ '.$detalles[0]->costo_publico.'</span>';

            $variable = "";
            if($isVariable)
                $variable = "Enter amount in USD
                <br/><hr/>
                <i class='icon-prepend fa fa-dollar'></i>
                <input id='costo_variable' onkeyup='validar_variable()'
                    name='costo_unidad' type='number' min='5' 
                    placeholder='at least 5 USD' class='input' />                         
                <script>validar_variable();</script>";

            echo '<div class="product">
          <a data-placement="left" data-original-title="Add to Wishlist" data-toggle="tooltip" class="add-fav tooltipHere">
          <i class="glyphicon glyphicon-heart"></i>
          </a>
            <div class="image"> <a href="product-details.html">
				<img class="img-responsive" alt="img" src="'.$img_item.'" style="width: 15rem ! important; height: 10rem ! important;">
				</a>
            </div>
            <div class="description">
              <h4><a >'.$detalles[0]->nombre.'</a></h4>
              <div class="grid-description">
                <p>'.$detalles[0]->descripcion.'. </p>
              </div>
              <div class="">
                '.$variable.'
                </div>
               </div>
            <div class="price"> 
           '.$costo_div.'
            </div><br>
            <br>
          </div>';

        }
	function add_carrito()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$id_mercancia=$data['id'];
		$id_tipo_mercancia=$data['tipo'];

		$this->printDetalleMercancia($id_tipo_mercancia,$id_mercancia);
		
		echo "<form id='comprar'  method='post' action=''>";
		if($id_tipo_mercancia==1){
			$limites=$this->modelo_compras->get_limite_compras($id_tipo_mercancia,$id_mercancia);
			$min=($limites[0]->min_venta>$limites[0]->existencia) ? $limites[0]->existencia :$limites[0]->min_venta;
			$max=($limites[0]->max_venta>$limites[0]->existencia) ? $limites[0]->existencia :$limites[0]->max_venta;
			echo "	<div class='row'>
						<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
							<p class='font-md'><strong>Unidades</strong></p><br>
							<input type='number' id='cantidad' name='cantidad' min='".$min."' max='".$max."' value='".$min."' ><br><br>
						</div>
					</div>";
		}

		echo "<div class='row'><br>
                <a id='agregar_item' class='btn btn-success'
                 onclick='comprar(".$id_mercancia.",".$id_tipo_mercancia.")'>
                    <i class='fa fa-shopping-cart'></i> ADD
                 </a>
             </div>
			</form>";

	}
	function add_merc()
	{
		
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}

        $id=$this->tank_auth->get_user_id();
		
		$data= $_GET["info"];
		$data = json_decode($data,true);
		
		$id_tipo_mercancia=$data['tipo'];
		$id_mercancia=$data['id'];
		
		if($id_tipo_mercancia==1)
			$detalles=$this->modelo_compras->detalles_productos($id_mercancia);
		else if($id_tipo_mercancia==2)
			$detalles=$this->modelo_compras->detalles_servicios($id_mercancia);
		else if($id_tipo_mercancia==3)
			$detalles=$this->modelo_compras->detalles_combinados($id_mercancia);
		else if($id_tipo_mercancia==4)
			$detalles=$this->modelo_compras->detalles_paquete($id_mercancia);
		else if($id_tipo_mercancia==5)
			$detalles=$this->modelo_compras->detalles_membresia($id_mercancia);


		if(!isset($data['qty']))
			$cantidad=1;
		else 
			$cantidad=$data['qty'];
		
		$costo=$detalles[0]->costo;

        $options = array('prom_id' => 0, 'time' => time());
        if (isset($data['costo_unidad'])){
            $costo=$data['costo_unidad'];
            $options['puntos'] = $costo;
        }

        $categoria = 1;
        if (isset($detalles[0]->id_red))
            $categoria = $detalles[0]->id_red;
        if (isset($detalles[0]->id_grupo))
            $categoria = $detalles[0]->id_grupo;

        $isPSR = $categoria == 2;
        if($isPSR)
            $this->setVipAdd($id);

        $add_cart = array(
            'id'      => $id_mercancia,
            'qty'     => $cantidad,
            'price'   => $costo,
            'name'    => $id_tipo_mercancia,
            'options' => $options
        );
	
		$this->cart->insert($add_cart);
		
	}
	
	function ver_carrito()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();

		
		if($this->cart->contents())
		{
			echo '<div class="row" id="contenido_carro">
					<div class="col-lg-12 col-md-12 col-sm-12">
				      <div class="row userInfo">
				        <div class="col-xs-12 col-sm-12">
				          <div class="cartContent w100">
				            <table class="cartTable table-responsive" style="width:100%">
				              <tbody>
				              
				                <tr class="CartProduct cartTableHeader">
				                  <td style="width:15%" > Product </td>
				                  <td style="width:40%" >Details</td>
				                  <td style="width:20%" >Quantity</td>
				                  <td style="width:15%" >Total</td>
								  <td style="width:10%" class="delete">&nbsp;</td>
				                </tr>';
							   $compras=$this->get_content_carrito();
							   $contador=0;
				               foreach ($this->cart->contents() as $items) 
								{
									
									echo '<tr class="CartProduct">
											<td  class="CartProductThumb">
												<div> 
													<a href="#"><img src="'.$compras["compras"][$contador]["imagen"].'" alt="img"></a> 
												</div>
											</td>
											<td >
												<div class="CartDescription">
							                      <h4>'.$compras["compras"][$contador]["nombre"].'</h4>
							                   
							                      <span>$'.($items['price']).'</span>
							                    </div>
							                </td>
							                <td >'.$items['qty'].'</td>
							                <td class="price">$ '.($items['qty']*$items['price']).'</td>
							                <td class="delete"><a title="Delete" onclick="quitar_producto(\''.$items['rowid'].'\')"><i class="txt-color-red fa fa-trash-o fa-3x "></i></a></td>
											
										</tr>';
									$contador++;
								}

				               echo ' </tbody>
						            </table>
						          </div>
						          <!--cartContent-->
						          
						        </div>
						      </div>
						      <!--/row end--> 
						      
						    </div>
						   </div>';
				
			}						
		else
		{
			echo 'THERE ARE NO PRODUCTS IN THE SHOPPING CART';	
		}

	}
	function show_productos()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		$descuento_por_nivel_actual=$this->modelo_compras->get_descuento_por_nivel_actual($id);
		$calcular_descuento="0.".(100-$descuento_por_nivel_actual[0]->porcentage_venta);
		$prod=$this->modelo_compras->get_productos();
		for($i=0;$i<sizeof($prod);$i++)
		{
			$imagen=$this->modelo_compras->get_img($prod[$i]->id);
			if(isset($imagen[0]))
			{
				$prod[$i]->img=$imagen[0]->url;
			}
			else 
			{
				$prod[$i]->img="";
			}
		}
		//$prom=$this->modelo_compras->get_promocion();
		$grupos=$this->modelo_compras->get_grupo_prod();
		echo '<div class="row">
				<div class="well" style="background-color:transparent;border:none;">
					<article>
						<section class="pull-right">
							<label class="select">
								<select class="input-sm" id="grupo_prod" onchange="show_grupo_prod()">
									<option value="0">Seleccione un grupo</option>';
									for($k=0;$k<sizeof($grupos);$k++)
									{
										echo '	<option value="'.$grupos[$k]->id_grupo.'">'.$grupos[$k]->descripcion.'</option>';
									}
									
								echo '</select>
							</label>
						</section>
					</article>
				</div>
			</div>';
		for($productos=0;$productos<sizeof($prod);$productos++)
		{

				echo '	<div class="item col-lg-3 col-md-3 col-sm-4 col-xs-6">
				    	<div class="product">
					    	<a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist"  data-placement="left">
					        	<i class="glyphicon glyphicon-heart"></i>
					        </a>
				          
				          		<div class="image"> <a onclick="detalles('.$prod[$productos]->id.',1)"><img src="'.$prod[$productos]->img.'" alt="img" class="img-responsive"></a>
				              		<div class="promotion">   </div>
				            	</div>
				            	<div class="description">
				              		<h4><a onclick="detalles('.$prod[$productos]->id.',1)">'.$prod[$productos]->nombre.'</a></h4>
				              		<p>'.$prod[$productos]->grupo.' </br></br>
				              		'.$prod[$productos]->descripcion.'. </p>
				              		
				              		
				              	</div>
				            	<div class="price"> <span>$ '.($prod[$productos]->costo).'</span></div>
				            	<div class="action-control"> <a class="btn btn-primary" onclick="compra_prev('.$prod[$productos]->id.',1,0)"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Añadir al carrito </span> </a> </div>
				       </div>
			       </div>
			';

							
		}
	}

	function show_todos()
	{
		$id = 2;
		if(!isset($_GET['id_afiliado'])){
			$id = $this->tank_auth->get_user_id();
		}
		else{
			$id = $_GET['id_afiliado'];
		}
	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$pais = $this->general->get_pais($id);
		$paisUsuario=$this->general->issetVar($pais,"pais","AAA");
	
		$productos=1;
		$servicios=2;
		$combinados=3;
		
		$redesUsuario=$this->model_tipo_red->cantidadRedesUsuario($id);
			
			foreach ($redesUsuario as $red){
		
			$categorias=$this->model_mercancia->CategoriasMercanciaIdRed($red->id);
			
				foreach ($categorias as $categoria){
					
				$this->showMercanciaPorCategoria($productos, $categoria->id_grupo, $paisUsuario);
				$this->showMercanciaPorCategoria($servicios, $categoria->id_grupo, $paisUsuario);
				$this->showMercanciaPorCategoria($combinados, $categoria->id_grupo, $paisUsuario);
				
				}
			}
		}
	
	function show_todos_tipo_mercancia(){
			$id = 2;
			if(!isset($_GET['id_afiliado'])){
				$id = $this->tank_auth->get_user_id();
			}
			else{
				$id = $_GET['id_afiliado'];
			}
		
			if (!$this->tank_auth->is_logged_in())
			{																		// logged in
				redirect('/auth');
			}
		
			$pais = $this->general->get_pais($id);
			$paisUsuario=$this->general->issetVar($pais,"pais","AAA");
		
			$tipoMercancia = $_GET['tipoMercancia'];
		
			$redesUsuario=$this->model_tipo_red->cantidadRedesUsuario($id);
			
			foreach ($redesUsuario as $red){
				$categorias=$this->model_mercancia->CategoriasMercanciaIdRed($red->id);
			
				foreach ($categorias as $categoria){
					$this->showMercanciaPorCategoria($tipoMercancia, $categoria->id_grupo, $paisUsuario);
				}
			}
			
	}
		
	function show_todos_categoria()
	{
		$id = 2;
		if(!isset($_GET['id_afiliado'])){
			$id = $this->tank_auth->get_user_id();
		}
		else{
			$id = $_GET['id_afiliado'];
		}
		
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$pais = $this->general->get_pais($id);
		$paisUsuario=$pais[0]->pais;
		$idCategoriaRed = $_GET['id'];
		
		$productos=1;
		$servicios=2;
		$combinados=3;
		$paquete=4;
		$membresia=5;

		$tipoItems = array($productos,$servicios,$combinados,$paquete,$membresia);
			
		for($i=0;$i<sizeof($tipoItems);$i++){
			$this->showMercanciaPorCategoria($tipoItems[$i], $idCategoriaRed, $paisUsuario);
		}
		
	}
	
	function getMercanciaPorTipoDeRed($id_tipo_mercancia,$id_tipo_red,$paisUsuario){
		$mercancia=array();
		
		if($id_tipo_mercancia==1)
			$mercancia=$this->modelo_compras->get_productos_red($id_tipo_red,$paisUsuario);
		else if($id_tipo_mercancia==2)
			$mercancia=$this->modelo_compras->get_servicios_red($id_tipo_red,$paisUsuario);
		else if($id_tipo_mercancia==3)
			$mercancia=$this->modelo_compras->get_combinados_red($id_tipo_red,$paisUsuario);
		else if($id_tipo_mercancia==4)
			$mercancia=$this->modelo_compras->get_paquetes_inscripcion_red($id_tipo_red,$paisUsuario);
		else if($id_tipo_mercancia==5)
			$mercancia=$this->modelo_compras->get_membresias_red($id_tipo_red,$paisUsuario);
		
		
		for($i=0;$i<sizeof($mercancia);$i++)
		{
			$imagen=$this->modelo_compras->get_img($mercancia[$i]->id);
			if(isset($imagen[0]))
			{
				$mercancia[$i]->img=$imagen[0]->url;
			}
			else
			{
				$mercancia[$i]->img="";
			}
		}
		return $mercancia;
	}
	
	function printMercanciaPorTipoDeRed($mercancia,$tipoMercancia,$categoria){
		
		for($i=0;$i<sizeof($mercancia);$i++)
		{
			$id_tipo_mercancia = isset($mercancia[$i]->id_tipo_mercancia) ? $mercancia[$i]->id_tipo_mercancia : 0;
			$boton = 'compra_prev('.$mercancia[$i]->id.','.$tipoMercancia.',0)' ;
			$btn = 'success';
			$rows = ($mercancia[$i]->descripcion!='') ? 9.8 : 1;
			$inventario =  '';
			
			if($id_tipo_mercancia == 1){
				$existencia = intval($mercancia[$i]->existencia);			
				$minimo = intval($mercancia[$i]->inventario);
				$agotado = (($existencia-$minimo)>0) ? false : true;
				$color = ($existencia < $mercancia[$i]->max_venta) ? 'orange' : 'green';
				$inventario = 'Existencias: <b style="color: '.$color.'">'.$existencia.'</b>' ;
				if($agotado){ 
						$inventario = '<b style="color: red">Producto Agotado</b>';
						$boton = 'javascript:void(0)' ;
						$btn = 'default' ;	
				}
			}

            $puntos_comisionables = '<span style="font-size: 1.5rem;">
                       (Puntos  ' . $mercancia[$i]->puntos_comisionables . ')
                        </span>';
            if ($mercancia[$i]->puntos_comisionables == '0')
                $puntos_comisionables = '';

            $img_item = $mercancia[$i]->img;

            if(!file_exists(getcwd().$img_item))
                $img_item = "/template/img/favicon/favicon.png";

            $costo = "";
            $isRedimible= $categoria == 2;

            $concepto = isset($mercancia[$i]->concepto) ? $mercancia[$i]->concepto : false;
            $isVariable = $categoria == 3;

            $nombre_boton = "Pay";
            if($categoria == 3)
            	$nombre_boton = "Deposit";

            
            if( !$isVariable)
                $costo = "<span>$ ". $mercancia[$i]->costo."</span>";

            if($isVariable)
                $puntos_comisionables = "<span style='font-size: 1.5rem;'>
                        Specify the value
                        </span>";

            $imprimir ='	<div class="item col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<div class="producto">
					<a class="" data-toggle="tooltip" data-original-title="Add to Wishlist"  data-placement="left">
						<i class=""></i>
					</a>
					<div class="image"> <a onclick="detalles('.$mercancia[$i]->id.','.$tipoMercancia.')">
							<img src="'.$img_item.'" alt="img" class="img-responsive"></a>
					<div class="promotion">   </div>
					</div>
					<div class="description" style="overflow-y: scroll;height: 10em">
					<h4><a  onclick="detalles('.$mercancia[$i]->id.','.$tipoMercancia.')">'.$mercancia[$i]->nombre.'</a></h4>
     				<section style="margin-bottom: 1rem;" class="smart-form">
					<label class="textarea textarea state-disabled"> 										
						<textarea rows="4" class="custom-scroll" disabled="disabled" style="color: rgb(0, 0, 0); border: medium none; overflow: hidden; height: '.$rows.'rem;">'.$mercancia[$i]->descripcion.'
						</textarea> 
					</label>
					</section>
					</div>
					<div class="price">
					<span>'.$inventario.'</span>
					</div>
					<hr/>
					<div class="price">'.$puntos_comisionables.'<br>
					'.$costo.'
					</div>
					<br>
					<div class=""> 
						<a style="font-size: 1.7rem;" class="btn btn-'.$btn.'" onclick="'.$boton.'"> 
						<span class="add2cart">
						<i class="glyphicon glyphicon-shopping-cart"> 
						</i> '.$nombre_boton.' </span> </a> </div>
				 	</div>
				</div>
				';
				echo $imprimir;

		}
	}
	
	function printContentCartButton(){

		$compras=$this->get_content_carrito ();
		
		if($this->cart->contents())
		{
		
			$cantidad=0;
			foreach ($this->cart->contents() as $items)
			{
				$total=$items['qty']*$items['price'];

				$img_item = $compras['compras'][$cantidad]['imagen'];
				
				if(!file_exists(getcwd().$img_item))
					$img_item = "/template/img/favicon/favicon.png";

				echo '<tr class="miniCartProduct">
									<td style="width:20%" class="miniCartProductThumb"><div> <a href=""> <img src="'.$img_item.'" alt="img"> </a> </div></td>
									<td style="width:40%"><div class="miniCartDescription">
				                        <h4> <a href=""> '.$compras['compras'][$cantidad]['nombre'].'</a> </h4>
				                        <span> '.$items['price'].' </span>
				                      </div></td>
				                    <td  style="width:10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
				                    <td  style="width:15%" class="miniCartSubtotal"><div class="price"><span>$ '.$total.'</span></div></td>
				                    <td  style="width:5%" class="delete"><a onclick="quitar_producto(\''.$items['rowid'].'\')"> <i class="txt-color-red fa fa-trash-o fa-2x "></i> </a></td>
								</tr>';
				$cantidad++;
			}
			
			
		echo '<script>$(".cartRespons").html("Cart ('.$this->cart->total_items().')");
					  $(".subtotal").html("Subtotal: '.$this->cart->total().'");
			  </script>';
		}
		
	}

	function showMercanciaPorCategoria($tipoMercancia,$idCategoriaRed,$paisUsuario){
		$mercancia=$this->getMercanciaPorTipoDeRed($tipoMercancia,$idCategoriaRed,$paisUsuario);
		$this->printMercanciaPorTipoDeRed($mercancia,$tipoMercancia,$idCategoriaRed);
	}

	function completar_compra()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$tipo_venta=$data["id"];
		$id_user=$this->tank_auth->get_user_id();
		
		
		switch($tipo_venta)
		{
			case 1: //credito o debito
				
				$fecha=$data['ano']."-".$data['mes']."-10";
				$fecha=date("Y-m-t", strtotime($fecha));
				if($data['salvar']=='1')
				{
					$status='ACT'; 
				}
				else
				{	 
					$status='DES';
				}
				if($data['comprador']!='0')
				{
					$id_user=$data['comprador'];
				}
				$this->db->query("insert into tarjeta (tipo_tarjeta,id_usuario,id_banco,cuenta,fecha_vencimiento,titular,
						codigo_seguridad,estatus) values (".$data['tipo'].",".$id_user.",".$data['banco'].",'".$data['numero']."',
						'".$fecha."','".$data['titular']."','".$data['codigo']."','".$status."')");
				$this->db->query("insert into venta (id_user,id_estatus,costo,id_metodo_pago) values (".$id_user.",2,".$this->cart->total().",1)");
				$venta=$this->db->insert_id();
				$puntos=0;
				foreach ($this->cart->contents() as $items) 
				{
					$this->db->query("insert into cross_venta_mercancia values (".$items['id'].",".$venta.",".$items['qty'].",".$items['options']['prom_id'].")");
					$puntos_q=$this->modelo_compras->get_puntos_comisionables($items['id']);
					$puntos=$puntos+($puntos_q[0]->puntos_comisionables*$items['qty']);
					$this->modelo_compras->update_inventario($items['id'],$items['qty']);
					$this->modelo_compras->salida_por_venta($items['id'],$items['qty'],$id_user,$venta);
				}
				$this->modelo_compras->insert_comision($venta,$puntos);
				$this->cart->destroy();
				break;
			case 5://compra programada
				$fecha=$data['ano']."-".$data['mes']."-10";
				$fecha=date("Y-m-t", strtotime($fecha));
				if($data['salvar']=='1')
				{
					$status='ACT'; 
				}
				else
				{	 
					$status='DES';
				}
				if($data['comprador']!='0')
				{
					$id_user=$data['comprador'];
				}
				$this->db->query("insert into tarjeta (tipo_tarjeta,id_usuario,id_banco,cuenta,fecha_vencimiento,titular,
						codigo_seguridad,estatus) values (".$data['tipo'].",".$id_user.",".$data['banco'].",'".$data['numero']."',
						'".$fecha."','".$data['titular']."','".$data['codigo']."','".$status."')");
				$this->db->query("insert into autocompra (fecha,id_usuario) values ('".$data['fecha']."',".$id_user.")");
				$autocompra=$this->db->insert_id();
				foreach ($this->cart->contents() as $items) 
				{
					$this->db->query("insert into cross_autocompra_mercancia values (".$autocompra.",".$items['id'].",".$items['qty'].")");
					
				}
				$this->cart->destroy();
				break;
			default:  
				break;
		}
		
	}

	function quitar_producto()
	{
		$id=$_POST['id'];
        $message = "Sure you want to remove this item ?";

		$content = $this->cart->contents();

        if(!$content){
            echo 'THERE ARE NOT PRODUCTS IN THE SHOPPING CART';
            return false;
        }

        $id_item = 0; $isPSR = false; $isVIP = false;

        foreach ($content as $item) :
            $item_id = $item['id'];
            $rowid = $item["rowid"];

            if($rowid == $id):
                log_message('DEV',"carrito quit PSR : $item_id");
                $id_item = $item_id;
            endif;

            $where = "AND i.id = $item_id";
            $mercancia = $this->playerbonos->getMercancia($where);

            if($mercancia && !$isVIP):
                $categoria = $mercancia[0]->categoria;
                $isVIP = $categoria == 1 ? $rowid : false;
                log_message('DEV',"carrito quit VIP : $item_id");
            endif;
        endforeach;

		$where = "AND i.id = $id_item";
		$mercancia = $this->playerbonos->getMercancia($where);
		if($mercancia):
            $categoria = $mercancia[0]->categoria;
            $isPSR = $categoria == 2;
        endif;

        $isPSR &= strlen($isVIP)>0;
        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : false;

		if(!$confirm):
            if($isPSR)
		        $message .=  "<br/><hr/>".
                    "Remember that you must become VIP for getting PSR.";
            log_message('DEV',"confirm quit PSR : $isPSR | VIP : $isVIP ");
		    echo $message;
		    return false;
        elseif ($isPSR ):
            $data = array(
                'rowid' => $isVIP,
                'qty'   => 0
            );
            $this->cart->update($data);
        endif;

		$data = array(
           'rowid' => $id,
           'qty'   => 0
        );
		$this->cart->update($data);

		echo "ITEM REMOVED SUCCESSFULLY";
	}

	function actualizar_nav()
	{
		
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		$descuento_por_nivel_actual=$this->modelo_compras->get_descuento_por_nivel_actual($id);
		if ($descuento_por_nivel_actual!=null){
			$calcular_descuento=(100-$descuento_por_nivel_actual[0]->porcentage_venta)/100;
		}else{
			$calcular_descuento=1;
		}
		
		
		echo ' <div class="navbar-header">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only"> Toggle navigation </span> <span class="icon-bar"> </span> <span class="icon-bar"> </span> <span class="icon-bar"> </span> </button>
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-cart"> <i class="fa fa-shopping-cart colorWhite"> </i> <span class="cartRespons colorWhite"> Cart ('.$this->cart->total_items().') </span> </button>
			      <a class="navbar-brand titulo_carrito" href="/ov/dashboard"> Dashboard &nbsp;</a>  
			      
			      <!-- this part for mobile -->
			      <div class="search-box pull-right hidden-lg hidden-md hidden-sm">
			        <div class="input-group">
			          <button class="btn btn-nobg getFullSearch" type="button"> <i class="fa fa-search"> </i> </button>
			        </div>
			        <!-- /input-group --> 
			        
			      </div>
			    </div>';
		echo '<div class="cartMenu  hidden-lg col-xs-12 hidden-md hidden-sm ">
        <div class="w100 miniCartTable scroll-pane">
          <table  >
            <tbody>';
            	 
                  	if($this->cart->contents())
					{ 
						foreach ($this->cart->contents() as $items) 
						{
							$total=$items['qty']*$items['price'];	
							$imgn=$this->modelo_compras->get_img($items['id']);
							if(isset($imgn[0]->url))
							{
								$imagen=$imgn[0]->url;
							}
							else
							{
								$imagen="";
							}
							switch($items['name'])
							{
								case 1:
									$detalles=$this->modelo_compras->detalles_productos($items['id']);
									break;
								case 2:
									$detalles=$this->modelo_compras->detalles_servicios($items['id']);
									break;
								case 3:
									$detalles=$this->modelo_compras->comb_espec($items['id']);
									break;
								case 4:
									$detalles=$this->modelo_compras->comb_paquete($items['id']);
									break;
								case 5:
									$detalles=$this->modelo_compras->detalles_prom_serv($items['id']);
									break;
								case 6:
									$detalles=$this->modelo_compras->detalles_prom_comb($items['id']);
									break;
							}
							echo '<tr class="miniCartProduct"> 
									<td style="width:20%" class="miniCartProductThumb"><div> <a href="#"> <img src="'.$imgn[0]->url.'" alt="img"> </a> </div></td>
									<td style="width:40%"><div class="miniCartDescription">
				                        <h4> <a href="product-details.html"> '.$detalles[0]->nombre.'</a> </h4>
				                        <div class="price"> <span> '.($items['price']).' </span> </div>
				                      </div></td>
				                    <td  style="width:10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
				                    <td  style="width:15%" class="miniCartSubtotal"><span>'.$total.'</span></td>
				                    <td  style="width:5%" class="delete"><a onclick="quitar_producto(\''.$items['rowid'].'\')"> x </a></td>
								</tr>'; 
						} 
					}            
         echo   '</tbody>
          </table>
        </div>
        <!--/.miniCartTable-->
        
        <div class="miniCartFooter  miniCartFooterInMobile text-right">
          <h3 class="text-right subtotal"> Subtotal: $'.$this->cart->total().' </h3>
          <a class="btn btn-sm btn-danger" onclick="ver_cart()"> <i class="fa fa-shopping-cart"> </i>SHOW CART</a> <a class="btn btn-sm btn-primary" onclick="a_comprar()"> ADD! </a> </div>
        <!--/.miniCartFooter--> 
        
      </div>';
		echo '</div>
    <!--/.navbar-cart-->
    
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"> <a onclick="show_todos()"> Todos </a> </li>
        <li class="dropdown megamenu-fullwidth"> <a data-toggle="dropdown" class="dropdown-toggle" onclick="show_prod()"> Productos </a></li>
        
        <!-- change width of megamenu = use class > megamenu-fullwidth, megamenu-60width, megamenu-40width -->
        <li class="dropdown megamenu-80width "> <a data-toggle="dropdown" class="dropdown-toggle" onclick="show_serv()"> Servicios </a></li>
        <li class="dropdown megamenu-fullwidth"> <a data-toggle="dropdown" class="dropdown-toggle" onclick="show_comb()"> Combinados </a></li>
        <li class="dropdown megamenu-fullwidth"> <a data-toggle="dropdown" class="dropdown-toggle" onclick="show_prom()"> Promociones </a></li>
      </ul>
      
      <!--- this part will be hidden for mobile version -->
      <div class="nav navbar-nav navbar-right hidden-xs" >
        <div class="dropdown  cartMenu "> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
        	<i class="fa fa-shopping-cart"> </i> 
        	<span class="cartRespons"> Cart ('.$this->cart->total_items().') 
        	</span> <b class="caret"> </b> </a>
          	<div class="dropdown-menu col-lg-4 col-xs-12 col-md-4 ">
            	<div class="w100 miniCartTable scroll-pane">
	              	<table>
	                	<tbody>';
	                  
	                 	foreach ($this->cart->contents() as $items) 
						{
							$total=$items['qty']*$items['price'];	
							$imgn=$this->modelo_compras->get_img($items['id']);
							if(isset($imgn[0]->url))
							{
								$imagen=$imgn[0]->url;
							}
							else
							{
								$imagen="";
							}
							switch($items['name'])
							{
								case 1:
									$detalles=$this->modelo_compras->detalles_productos($items['id']);
									break;
								case 2:
									$detalles=$this->modelo_compras->detalles_servicios($items['id']);
									break;
								case 3:
									$detalles=$this->modelo_compras->comb_espec($items['id']);
									break;
								case 4:
									$detalles=$this->modelo_compras->comb_paquete($items['id']);
									break;
								case 5:
									$detalles=$this->modelo_compras->detalles_prom_serv($items['id']);
									break;
								case 6:
									$detalles=$this->modelo_compras->detalles_prom_comb($items['id']);
									break;
							}
							echo '<tr class="miniCartProduct"> 
									<td style="width:20%" class="miniCartProductThumb"><div> <a href="#"> <img src="'.$imgn[0]->url.'" alt="img"> </a> </div></td>
									<td style="width:40%"><div class="miniCartDescription">
				                        <h4> <a href="product-details.html"> '.$detalles[0]->nombre.'</a> </h4>
				                        <div class="price"> <span> '.$items['price'].' </span> </div>
				                      </div></td>
				                    <td  style="width:10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
				                    <td  style="width:15%" class="miniCartSubtotal"><span>'.$total.'</span></td>
				                    <td  style="width:5%" class="delete"><a onclick="quitar_producto(\''.$items['rowid'].'\')"> x </a></td>
								</tr>'; 
						} 
	                  
	                echo '</tbody>
	              </table>
            	</div>
            <!--/.miniCartTable-->
            
	            <div class="miniCartFooter text-right">
	              <h3 class="text-right subtotal"> Subtotal: $ '.$this->cart->total().' </h3>
	              <a class="btn btn-sm btn-danger" onclick="ver_cart()"> <i class="fa fa-shopping-cart"> </i>SHOW CART</a> <a class="btn btn-sm btn-primary" onclick="a_comprar()"> ADD! </a> </div>
	            <!--/.miniCartFooter--> 
            
          		</div>
          <!--/.dropdown-menu--> 
        	</div> 
        <!--/.cartMenu--> 
        
        <div class="search-box">
          <div class="input-group"> 
            <button class="btn btn-nobg getFullSearch" type="button"> <i class="fa fa-search"> </i> </button>
          </div>
          <!-- /input-group --> 
          
        </div>
        <!--/.search-box --> ';
	}
	function select_af()
	{
		$user=$this->tank_auth->get_user_id();
		$this->afiliados = array();
		$this->preOrden($user);
		$afiliados = $this->modelo_compras->get_afiliados($user);
		echo '<div class="row">
	              <div class="col-lg-12">
	                <div class="row" style="text-align:center;">
						
						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							
							<label class="select" id="afiliados">
								<select id="afiliado_id">
									<option value="0">Escoge a tu afiliado</option>';
										foreach($this->afiliados as $afiliado)
										{
											echo '<option value="'.$afiliado->id_afiliado.'">'.$afiliado->nombre.'</option>';
										}								
									
								echo '</select> <i></i> </label>
						</section>
					</div> <br>
					<a class="btn btn-success btn-lg" href="javascript:void(0);" onclick="enviar_carro()"><i class="fa fa-shopping-cart"></i>Ir al Deposito</a>
				</div>
			</div>';
	}
	function hacer_compra()
	{
		$this->modelo_compras->hacer_compra();
		redirect('/ov/dashboard');
	}
	
	function verificar_carro()
	{
		$prod=sizeof($this->cart->contents());
		echo ($prod>0) ? 'si' : 'no';

	}
	
	function actualizar_comprador(){
		      
		if ($_POST['dni_comprador']==""){
			$error = "You must write your dni.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		
		else if ($_POST['nombre_comprador']==""){
			$error = "You must write your name.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		
		else if ($_POST['apellido_comprador']==""){
			$error = "You must write your surname.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['pais_comprador']=="-"){
			$error = "You must select your country.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['estado_comprador']==""){
			$error = "You must write the state where you are.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['municipio_comprador']==""){
			$error = "You must write the municipality where you are.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['colonia_comprador']==""){
			$error = "You must write the colony where you are.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['direccion_comprador']==""){
			$error = "You must write the address where you are.";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		}
		else if ($_POST['telefono_comprador']==""){
			$error = "You must write your phone number.s";
			$this->session->set_flashdata('error', $error);
			redirect('/ov/compras/carrito_publico?usernameAfiliado='.$_POST['usernameAfiliado']);
		} else 
		 {
			$this->model_comprador->actualizar_comprador($_POST['dni_comprador'],$_POST['nombre_comprador'], $_POST['apellido_comprador'], $_POST['pais_comprador'], $_POST['estado_comprador'], $_POST['municipio_comprador'], $_POST['colonia_comprador'] , $_POST['direccion_comprador'], $_POST['email_comprador'], $_POST['telefono_comprador']);
			//$this->comprar_web_personal($_POST['usernameAfiliado'], $_POST['dni_comprador']);
			redirect("/ov/compras/comprar_web_personal?username=".$_POST['usernameAfiliado']."&dni=".$_POST['dni_comprador']);
		}
	}
	
	function GuardarVenta(){
		
		$this->load->model('model_users');
		if(!isset($_POST['id_mercancia'])){
			echo "The purchase can not be registered";
			return 0;
		}
		
		$productos = $this->cart->contents();
		$id = $_POST['id_usuario'];
		$datos_perfil = $this->modelo_compras->get_direccion_comprador($id);
		
		$id_mercancia = $_POST['id_mercancia'];
		$cantidad = $_POST['cantidad'];
		
		if($this->modelo_compras->ComprovarCompraMercancia($id, $id_mercancia)){
			$producto_continua = array();
		
			foreach ($productos as $producto){
				if($producto['id'] == $id_mercancia){
					$this->cart->destroy();
				}else{
					$add_cart = array(
							'id'      => $producto['id'],
							'qty'     => $producto['qty'],
							'price'   => $producto['price'],
							'name'    => $producto['name'],
							'options' => $producto['options']
					);
					$producto_continua[] = $add_cart;
				}
			}
			echo "0";
			$this->cart->insert($producto_continua);
			return 0;
		}
		$direcion_envio = $datos_perfil[0]->calle." ".$datos_perfil[0]->colonia." ".$datos_perfil[0]->municipio." ".$datos_perfil[0]->estado;
		$telefono = $this->modelo_compras->get_telefonos_comprador($id);
		$email = $datos_perfil[0]->email;
		$time = time().$id_mercancia;
		
		$costo = $cantidad * $this->modelo_compras->CostoMercancia($id_mercancia);
		$firma = md5("consignacion~".$time."~".$costo."~USD");
		$id_transacion = $id_mercancia.$cantidad.$costo.time();
		$impuestos = $this->modelo_compras->ImpuestoMercancia($id_mercancia, $costo);
		$fecha = date("Y-m-d");
		
		$venta = $this->modelo_compras->registrar_ventaConsignacion($id, $costo , $id_transacion, $firma, $fecha, $impuestos);
		
		$envio=$this->modelo_compras->registrar_envio($venta, $id, $direcion_envio , $telefono, $email);
		
		$this->modelo_compras->registrar_factura($venta, $id, $direcion_envio , $telefono, $email);
		
		$puntos = $this->modelo_compras->registrar_venta_mercancia($id_mercancia, $venta, $cantidad);
		$total = $this->modelo_compras->registrar_impuestos($id_mercancia);
		$this->modelo_compras->registrar_movimiento($id, $id_mercancia, $cantidad, $costo+$impuestos, $total, $venta, $puntos);
		$producto_continua = array();
		
		foreach ($productos as $producto){
			if($producto['id'] == $id_mercancia){
				$this->cart->destroy();
			}else{
				$add_cart = array(
						'id'      => $producto['id'],
						'qty'     => $producto['qty'],
						'price'   => $producto['price'],
						'name'    => $producto['name'],
						'options' => $producto['options']
				);
				$producto_continua[] = $add_cart;
			}
		}
		$this->cart->insert($producto_continua);
		
		echo $venta;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////

	function EnviarPayuLatam(){
		
		$this->template->set_theme('desktop');
		
		$this->template->build('website/ov/compra_reporte/prueba');
	}

	function registrarVenta(){
		
		$estado = $_POST['state_pol'];
		$productos = $this->cart->contents();
		$referencia = $_POST['reference_sale'];
		$id_venta = $_POST['extra1'];
		$id_usuario = $_POST['extra2'];
		$metodo_pago = $_POST['payment_method_id'];
		$respuesta = $_POST['response_code_pol'];
		$fecha = $_POST['transaction_date'];
		$moneda = $_POST['currency'];
		$email = $_POST['email_buyer'];
		$direcion_envio = $_POST['shipping_address'];
		$telefono = $_POST['phone'];
		$identificado_transacion = $_POST['transaction_id'];
		$medio_pago = $_POST['payment_method_name'];
	
		$id_transaccion = $_POST['transaction_id'];
		$firma = $_POST['sign'];
	
		
		//Con la venta consultar el id_mercancia, costo, costo_publico
		
		$mercancia = $this->modelo_compras->consultarMercancia($id_venta);
		
		//$impuestos = $this->modelo_compras->ImpuestoMercancia($id_mercancia, $costo);
	
		if($estado == 4){
				
			$this->modelo_compras->actualizarVenta($id_venta,"2",$metodo_pago, $id_transaccion ,$firma );
			
			$valor_total_venta = $mercancia[0]->cantidad * $mercancia[0]->costo;
			$id_categoria_mercancia = $this->modelo_compras->ObtenerCategoriaMercancia($mercancia[0]->id);
			$costo_comision = $this->modelo_compras->ValorComision($id_categoria_mercancia);
				
			$id_red = $this->modelo_compras->ConsultarIdRedMercancia($id_categoria_mercancia);
			$capacidad_red = $this->model_tipo_red->CapacidadRed($id_red);
			$id_afiliado = $this->model_perfil_red->ConsultarIdPadre( $id_usuario, $id_red);
				
			$this->CalcularComision2($id_afiliado, $id_venta, $id_categoria_mercancia ,$costo_comision, $capacidad_red, 1, $valor_total_venta);
			return "Registro Corecto";
		}
	}

	
	function CalcularComision2($id_afiliado,$id_venta, $id_categoria_mercancia,$valor_comision, $capacidad_red ,$contador, $costo_mercancia){

		$this->DarComision($id_venta, $id_afiliado, $valor_comision, $capacidad_red, $id_categoria_mercancia);

	}
	
	function Encontrar_a_padre_niveltres($user,$id_red){
	
		$id_afiliado = $this->model_perfil_red->ConsultarIdPadre($user, $id_red);
		$nivel_de_padre = $this->model_perfil_red->Consultar_nivel_red($id_afiliado[0]->debajo_de);
		
		if($nivel_de_padre[0]->nivel_en_red=='3'){
			return $nivel_de_padre[0]->user_id;
		}
		else{
			return $this->Encontrar_a_padre_niveltres($id_afiliado[0]->debajo_de,$id_red);
		}

	}
	
	function DarComision($id_venta, $id_afiliado, $costo_comision, $porcentaje_comision, $id_categoria_mercancia){
		$this->modelo_compras->CalcularComisionVenta ( $id_venta, $id_afiliado[0]->debajo_de, $porcentaje_comision, $costo_comision, $id_categoria_mercancia);
	} 

	function SelecioneBancoWebPersonal(){
		
		if(!isset($_POST['id_mercancia'])){
			echo "The purchase can not be registered";
			return 0;
		}
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		if($_GET['usr'] != 0){
			$id = $_GET['usr'];
		}else{
			$id = $this->tank_auth->get_user_id();
		}
		
		$data['bancos'] = $this->modelo_compras->BancosPagoComprador($_POST['dni']);
		$data['id_mercancia'] = $_POST['id_mercancia'];
		$data['cantidad'] = $_POST['cantidad'];
		$data['dni'] = $_POST['dni'];
		$data['id_afiliado'] = $_POST['id_afiliado'];
	
		$this->template->set_theme('desktop');
		$this->template->build('website/ov/compra_reporte/bancosWebPersonal',$data);
	}
	
	
	function Cambiar_estado_enviar(){
		
		$id_venta=$_POST['id'];
		$consultar_ventas_web_p=$this->model_web_personal_reporte->Actualizar_estado_a_envio($id_venta);
		
	}
	
	function DatosEnvio(){
		
		if (!$this->tank_auth->is_logged_in())																	// logged in
			redirect('/auth');
		
		redirect("/buy");
	}
	
	function buscarProveedores(){
		$proveedores = $this->modelo_compras->buscarProveedorTarifaCiudad($_POST['id_ciudad']);
		
		echo json_encode($proveedores);
	}
	
	function guardarEnvio(){
		if (!$this->tank_auth->is_logged_in())																	// logged in
			redirect('/auth');
		
		$id=$this->tank_auth->get_user_id();
		$tarifa = $this->modelo_compras->consultarTarifa($_POST['tarifa']);
		
		$datos = array(
				'id_user' => $id,
				'id_proveedor' => $tarifa[0]->id_proveedor,
				'id_tarifa' => $_POST['tarifa'],
				'costo' => $tarifa[0]->tarifa,
				'nombre' => $_POST['nombre'],
				'apellido' => $_POST['apellido'],
				'id_pais' => $_POST['pais'],
				'estado' => $_POST['estado'],
				'municipio' => $_POST['municipio'],
				'colonia' => $_POST['colonia'],
				'calle' => $_POST['direccion'],
				'cp' => $_POST['codigo'],
				'email' => $_POST['email'],
				'telefono' => $_POST['telefono'],
		);
		$this->modelo_compras->guardarDatosEnvio($datos);
		
		redirect("/buy");
	}

	function datos_comprador_web_personal(){
		$this->template->build('website/ov/compra_reporte/datos_comprador_web_personal');
	}


    public function pagarComisionVenta($id_venta, $id_afiliado)
    {
        log_message('DEV',"dentro de pagarcomision");
        $MATRICIAL = 'MAT';
        $UNILEVEL = 'UNI';

        $mercancias = $this->modelo_compras->consultarMercanciaTotalVenta($id_venta);

        foreach ($mercancias as $mercancia) {

            $id_mercancia = $mercancia->id;
            log_message('DEV',"dentro de mercancia :: $id_mercancia");
            $id_red_item = $this->modelo_compras->ObtenerCategoriaMercancia($id_mercancia);
            $tipo_plan = $this->modelo_compras->obtenerPlanDeCompensacion($id_red_item);

            $tipo_plan = $tipo_plan[0]->plan;
            $isMatricial = $tipo_plan == $MATRICIAL;
            $isUnilevel = $tipo_plan == $UNILEVEL;
            $categoria = $mercancia->categoria;

            log_message('DEV',"categoria ($categoria) tipo -> $id_red_item");

            $isPSR = $categoria == 2;

            $where = "AND i.categoria = 2";
            $fechafin = date('Y-m-d H:i:s');
            $fechainicio = $this->playerbonos->getPeriodoFecha("UNI","INI",$fechafin);
            $isCompraPSR = $this->playerbonos->getVentaMercancia($id_afiliado,$fechainicio,$fechafin,false,false,$where);
            $isAvailable = $isMatricial || $isUnilevel;
            $isFirstPSR = $isCompraPSR ? (sizeof($isCompraPSR) <= 1) : false;

            log_message('DEV',"iscomprapsr :: [[ $isFirstPSR ]]");

            if ($isAvailable && $isPSR ) {
                $id_red = 2;
                $costoVenta = $mercancia->costo_unidad_total;
                if($isFirstPSR):
                    log_message('DEV',"is first PSR ($id_afiliado)");
                    $this->calcularComisionAfiliado($id_venta, $id_red, $costoVenta, $id_afiliado);
                endif;
                $this->setAutoTicket($id_afiliado,$id_mercancia);
                $this->newPasivo($id_afiliado,$id_venta);
            }

            $isDeposit = $categoria == 3;
            if($isDeposit)
                $this->setDepositoCompra($id_afiliado, $mercancia);

            $isItemVIP = $categoria == 1; $red_vip = 2;
            $isVIP = $this->playerbonos->isAfiliadoenRed($id_afiliado, $red_vip);
            if ($isItemVIP && !$isVIP):
                $this->playerbonos->setVIPUser($id_afiliado);
                log_message('DEV', "NEW VIP USER ($id_afiliado)");
            endif;

        }
    }

    public function newPasivo ($id_usuario,$id_venta, $fechaFinal = false, $amount = 0) {

	     if(!$fechaFinal)
            $fechaFinal = $this->playerbonos->getAnyTime("now", "180 day", true);

            $query = "INSERT INTO comision_pasivo
                            (user_id,enddate,amount,reference)  
                            VALUES
                            ($id_usuario,'$fechaFinal',$amount,$id_venta)";
            $this->db->query( $query);

    }
	
	public function calcularComisionAfiliado($id_venta, $id_red, $costoVenta, $id_afiliado){

        log_message('DEV',"comisionando :: $id_afiliado : $id_venta - $costoVenta");

		$valores = $this->modelo_compras->ValorComision($id_red);
		$capacidad_red = $this->model_tipo_red->CapacidadRed($id_red);
		$profundidadRed=$capacidad_red[0]->profundidad;
        $red_free = 1;
		for($i=0;$i<$profundidadRed;$i++){


            $afiliado_padre = $this->model_perfil_red->ConsultarIdPadre($id_afiliado, $red_free);
				
			if(!$afiliado_padre||$afiliado_padre[0]->debajo_de==1)
				return false;
				
			$id_padre=$afiliado_padre[0]->debajo_de;

			$isAvailable = true;
            if($id_red != $red_free):
                $isAvailable = $this->playerbonos->isActivedPSR($id_padre);
                $isAvailable &= $this->isActivedPasivo($id_padre);
            endif;

			if(!$isAvailable):
                $id_afiliado=$id_padre;
			    continue;
			endif;

			$valorComision =  0;

			if(isset($valores[$i]))
			    $valorComision = $valores[$i]->valor;

			$valor_comision=(($valorComision*$costoVenta)/100);

			log_message('DEV',"comision ($id_afiliado) :: $id_venta|$id_red|$id_padre|$valor_comision");
				
			$this->modelo_compras->set_comision_afiliado($id_venta,$id_red,$id_padre,$valor_comision);
				
			$id_afiliado=$id_padre;
		}
	
	}

    public function updateCarritoProceso($id_proceso, $dato,$type = "code")
    {
        $q = $this->getCarritoProceso($id_proceso);

        $dato_carrito = json_decode($q[0]->carrito);
        foreach ($dato_carrito as $datos){
            $datos->$type = $dato;
        }
        $carritoDato = json_encode($dato_carrito);

        $this->db->query("UPDATE pago_online_proceso SET carrito = '$carritoDato' WHERE id = $id_proceso");
    }

    public function getCarritoProceso($id_proceso)
    {
        $q = $this->db->query("SELECT * FROM pago_online_proceso WHERE id = $id_proceso");
        $q = $q->result();
        return $q;
    }

    private function typeSERVER()
    {
        $config = false;
        require(APPPATH . "config/config.php");
        $ssl = $config["enable_hooks"];
        $typesec = ($ssl) ? "https" : "http";
        return $typesec;
    }

    private function setBlockchainQR($id, $address, $id_proceso,$total)
    {
        $link = "bitcoin:$address?label=Playerbitcoin&amount=$total";
        $qrview = "/template/php/openqr/";
        $qrdir = getcwd() . $qrview;
        $qrlib = $qrdir . "qrlib.php";
        $cache_dir = $qrdir . "cache/";
        $temp_dir = $qrdir . 'temp/';

        if (!is_dir($temp_dir))
            mkdir($temp_dir);

        $this->limpiarDir($temp_dir);
        #todo: $this->limpiarDir($cache_dir);

        include($qrlib);

        $qrfile = md5("$id|$address|$id_proceso");
        $filename = $temp_dir . "block_$qrfile.png";

        $level = "H";
        $size = 6;
        QRcode::png($link, $filename, $level, $size, 2);

        $filename = $qrview . "temp/block_$qrfile.png";
        return $filename;
    }

    private function limpiarDir($temp_dir)
    {
        $temps = is_dir($temp_dir) ? scandir($temp_dir) : false;
        if (!$temps)
            return false;
        foreach ($temps as $tmp){
            if ($tmp == '.' || $tmp == '..') continue;

            unlink($temp_dir . $tmp);
        }

        return array($temps, $tmp);
    }

    private function setDescuentoCompra($id_afiliado,$id_venta, $valor = 0)
    {
        log_message('DEV',"ID: $id_afiliado descontando $valor en $id_venta");
        if($valor<=0)
            return false;

        $tipo = "SUB";
        $descripcion = "ORDER # $id_venta";
        $this->modelo_billetera->add_sub_billetera($tipo, $id_afiliado, $valor, $descripcion);

        return true;
    }

    private function setDepositoCompra($id_afiliado, $mercancia)
    {
        $tipo = "ADD";
        $monto = $mercancia->costo_unidad;#$costoVenta;
        $descripcion = "NEW DEPOSIT FROM BTC: $monto $";
        log_message('DEV',"ID: $id_afiliado depositando $monto");
        $this->modelo_billetera->add_sub_billetera($tipo, $id_afiliado, $monto, $descripcion);

        return true;
    }

    private function setBlockchainFee($valor, $where = "")
    {
        if($where != "")$where = "WHERE $where";
        $query = "SELECT * FROM blockchain_fee $where";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return $valor;

        $tarifa = 0;
        foreach ($q as $key => $value){
            $minimo = $value->monto;

            if($valor < $minimo)
                break;

            $tarifa = $value->tarifa;
        }

        if($tarifa <= 0)
            return $valor;

        $factor = $tarifa / 100;
        $valor *= 1+ $factor;

        return $valor;

    }

    private function addConfirmationOnline($id_pago,$valor)
    {
        $query = "UPDATE pago_online_proceso 
                            SET confirmations = $valor
                            WHERE id = $id_pago";
        $this->db->query($query);
    }

    private function setAutoTicket($id_afiliado, $id_mercancia)
    {
        $query="SELECT * FROM mercancia m,items i 
                    WHERE 
                    i.id = m.id 
                    AND i.categoria = 2
                    AND i.id = $id_mercancia
                    GROUP BY m.id";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q){
            log_message('DEV',"PSR for autoticket not found :: $id_mercancia");
            return false;
        }

        $item = $q[0]->sku;

        $bono_psr = 2;
        $valores = $this->playerbonos->getBonoValorNiveles($bono_psr);

        if(!$valores){
            log_message('DEV',"PSR bono not found :: $bono_psr");
            return false;
        }

        $limite = sizeof($valores) - 1;
        $valor_tickets = $valores[$limite]->valor ;
        if( isset($valores[$item]) )
            $valor_tickets = $valores[$item]->valor;

        log_message('DEV',"auto tickets for PSR ($item) :: $valor_tickets");

        $tickets = array();
        for($i = 0;$i< $valor_tickets;$i++){
            $ticket = $this->playerbonos->getValueTicketAuto();
            array_push($tickets,$ticket);
        }

        date_default_timezone_set('UTC');
        $date_final = $this->playerbonos->getAnyTime('now', '30 days',true);
        $date_final.=" 21:00:00";

        #TODO: 1 de abril
        $date_init = '2019-04-01';#false;
        $date_final = $this->playerbonos->getAnyTime($date_init, '30 days',true);

        $this->playerbonos->newTickets($id_afiliado,$tickets,'DES',$date_final,$date_init);

    }

    private function setVipAdd($id)
    {
        $options = array('prom_id' => 0, 'time' => time());


        $isActived = $this->playerbonos->isActivedAfiliado($id);

        if ($isActived)
            return true;

        $content = $this->cart->contents();
        $id_vip = 1;
        $costo_vip = 50;
        $tipo_vip = 5;

        foreach ($content as $item) :
        log_message('DEV',"carrito VIP ? ".json_encode($item["name"]));
        if($item["name"] == $tipo_vip)
            return false;
        endforeach;

        $add_cart = array(
            'id' => $id_vip,
            'qty' => 1,
            'price' => $costo_vip,
            'name' => $tipo_vip,
            'options' => $options
        );

        $this->cart->insert($add_cart);

        return true;
    }

    private function getPSRuser($id_usuario)
    {
        $query = "SELECT * 
              FROM venta v, cross_venta_mercancia c, items i,mercancia m
              WHERE i.id = c.id_mercancia 
              AND m.id  = c.id_mercancia
              AND c.id_venta = v.id_venta
              AND i.categoria = 2 AND v.id_estatus = 'ACT'
              AND v.id_user = $id_usuario";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function isActivedPasivo($id)
    {
        $id_bono = 2;
        $comisiones = $this->modelo_billetera->get_total_comisiones_afiliado($id);
        $valores = $this->playerbonos->getBonoValorNiveles($id_bono);
        $percent = $valores[0]->valor;

        $fechaInicio=$this->playerbonos->getPeriodoFecha("DIA", "INI", '');
        $fechaFin=$this->playerbonos->getPeriodoFecha("DIA", "FIN", '');

        $itemsPSR = $this->getPSRuser($id);
        $where = "order by reference ASC";
        $pasivos = $this->getPasivos($id, $where);
        $fechaFinal = $this->playerbonos->getAnyTime($fechaFin, "180 day", true);

        foreach ($itemsPSR as $index => $psr) {

            $json = json_encode($psr);
            log_message ('DEV',"VENTA PSR :: $json ");

            $reference = $psr->id_venta;
            $valor = $psr->costo;
            $tope = $valor * 2;

            $where = "AND reference = $reference AND estatus = 'ACT'";
            $pasivo = $this->getPasivos($id, $where);

            $amount = $valor * $percent;
            $acumulado=$comisiones;

            if (!$pasivo):
                $this->setPasivoUser($id, $fechaInicio, $fechaFinal, $amount, $reference);
                $where = "AND reference = $reference AND estatus = 'ACT'";
                $pasivo = $this->getPasivos($id, $where);
                $acumulado += $pasivo[0]->amount;
                log_message ('DEV',"NEW PSR PASIVE $reference :: $valor \n");
            endif;

            $comisiones = $acumulado - $tope;
            if($acumulado>$tope):
                $acumulado = $tope;
            endif;

            if($comisiones<0)
                $comisiones =0;

            $id_pasivo = $pasivo[0]->id;
            $sumado = $acumulado ;

            if ($sumado >= $tope):
                $this->desactivarPasivo($id_pasivo);
                log_message ('DEV',"DESACTIVAR PSR :: $id_pasivo \n");
                return false;
                break;
            elseif ($comisiones <= 0) :
                log_message ('DEV',"REFERENCE $reference PSR ($id) :: $id_pasivo \n");
                break;
            endif;

        }

        return true;

    }

    private function desactivarPasivo($id_pasivo)
    {
        $query = "UPDATE comision_pasivo set
                            estatus = 'DES' 
                            WHERE id = $id_pasivo";
        $this->db->query($query);
    }

    private function setPasivoUser($id_usuario, $fechaInicio, $fechaFinal, $amount, $id_venta)
    {
        $query = "INSERT INTO comision_pasivo
                            (user_id,initdate,enddate,amount,reference)  
                            VALUES
                            ($id_usuario,'$fechaInicio','$fechaFinal',$amount,$id_venta)";

        $this->db->query($query);
        return $this->db->insert_id();
    }

    private function getPasivos($id_usuario, $where = "",$select = "*")
    {
        $query = "SELECT $select 
              FROM comision_pasivo
              WHERE user_id = $id_usuario
              $where";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

}
