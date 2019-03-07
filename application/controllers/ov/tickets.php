<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class tickets extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->load->library('cart');
		$this->lang->load('tank_auth');
		$this->load->model('ov/general');
		$this->load->model('ov/modelo_billetera');
		$this->load->model('ov/modelo_dashboard');
        $this->load->model('ov/modelo_compras');
		$this->load->model('bo/model_bonos');
		$this->load->model('model_tipo_red');
		$this->load->model('ov/model_perfil_red');
        $this->load->model('bo/bonos/clientes/playerbitcoin/playerbonos');

		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		
		$id=$this->tank_auth->get_user_id();
	/*	if($this->general->isAValidUser($id,"OV") == false)
		{
			redirect('/shoppingcart');
		}*/
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}

		$id              = $this->tank_auth->get_user_id();
		
		if($this->general->isActived($id)!=0){
			redirect('/shoppingcart');
		}


		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);

		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);

		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		#$this->template->build('website/ov/billetera/dashboard');
		$this->template->build('website/ov/tickets/index');
	}
	
	function index_estado()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id              = $this->tank_auth->get_user_id();
		
		if($this->general->isActived($id)!=0){
			redirect('/shoppingcart');
		}
	 
	
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
	
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		#$this->template->build('website/ov/billetera/dashboard');
		$this->template->build('website/ov/billetera/index_estado');
	}
	
	function historial_cuenta()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id              = $this->tank_auth->get_user_id();
		
		if($this->general->isActived($id)!=0){
			redirect('/shoppingcart');
		}
	
	
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
	
		$historial=$this->modelo_billetera->get_historial_cuenta($id);
		$ganancias=$this->modelo_billetera->get_monto($id);
		$ganancias=$ganancias[0]->monto;
		$años = $this->modelo_billetera->anosCobro($id);

	/*	foreach ($historial as $comision){
				if($comision->fecha == $mes->fecha){
					$mes->valor+=$comision->valor;
				}
		}*/
		$this->template->set("historial",$historial);
		
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		//$this->template->set("historial",$historial);
		$this->template->set("ganancias",$ganancias);
		$this->template->set("años",$años);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		#$this->template->build('website/ov/billetera/dashboard');
		$this->template->build('website/ov/billetera/historial_cuenta');
	}

	function listar()
	{
        if (!$this->tank_auth->is_logged_in()) {                                                                        // logged in
            redirect('/auth');
        }

        $id = $this->tank_auth->get_user_id();

        if ($this->general->isActived($id) != 0) {
            redirect('/shoppingcart');
        }
	
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);

        $tickets=$this->modelo_compras->get_tickets_id($id);
		$this->template->set("tickets",$tickets);

        $isVIP = $this->playerbonos->isActivedAfiliado($id);

        if($isVIP){
            $hour = (int) date("His");
            $isVIP = $hour <= 235959 ;
            log_message('DEV',"hour list : $hour");
        }

        $this->template->set("isVIP",$isVIP);

		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/tickets/listar');
	}

	function manual()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id              = $this->tank_auth->get_user_id();

        $isActived = $this->playerbonos->isActivedAfiliado($id);
        $hour = (int) date("His");
        $limit = $isActived ? 235959 : 205959;
        $isActived = $hour <= $limit;

        log_message('DEV',"hour :::: $hour");

        if($this->general->isActived($id)!=0){
			redirect('/shoppingcart');
		}elseif(!$isActived){
            redirect('/listTickets');
        }
	
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);

		$redes = $this->model_tipo_red->listarTodos();
		$redesUsuario = $this->model_tipo_red->RedesUsuario($id);

		$ganancias=array();
		$comision_directos = array();
		$bonos = array();

		foreach ($redesUsuario as $red){
			array_push($bonos,$this->model_bonos->ver_total_bonos_id_red($id,$red->id));
			array_push($ganancias,$this->modelo_billetera->get_comisiones($id,$red->id));
			array_push($comision_directos,$this->modelo_billetera->getComisionDirectos($id, $red->id));
		}

		$comision_todo= array(
				'directos' => $comision_directos,
				'ganancias' => $ganancias,
				'bonos' => $bonos,
				'redes' => $redesUsuario
		);

		$total_bonos = $this->model_bonos->ver_total_bonos_id($id);

		$comisiones = $this->modelo_billetera->get_total_comisiones_afiliado($id);
		$cobro=$this->modelo_billetera->get_cobros_total($id);
		$cobroPendientes=$this->modelo_billetera->get_cobros_pendientes_total_afiliado($id);
		$retenciones = $this->modelo_billetera->ValorRetencionesTotales($id);
		$pais            = $this->model_perfil_red->get_pais();
		$cuenta			 = $this->model_perfil_red->val_cuenta_banco($id);

		$transaction = $this->modelo_billetera->get_total_transacciones_id($id);
        $saldo =  $transaction["add"];
        $saldo -=   $transaction["sub"];
        $this->template->set("saldo_deposit",$saldo);

        $psr = $this->modelo_billetera->get_psr($id);
        $this->template->set("psr",$psr);

        $bitcoin = $this->playerbonos->getBitcoinValue();
        $this->template->set("bitcoin",$bitcoin);

        $where = "i.categoria = 4";
        $mercancia = $this->playerbonos->getMercancia($where);
        $tarifa = $mercancia[0]->costo;
        $this->template->set("tarifa",$tarifa);

        $this->template->set("style",$style);
		$this->template->set("pais",$pais);
		$this->template->set("cuenta",$cuenta);
		$this->template->set("comisiones",$comisiones);
		$this->template->set("usuario",$usuario);
		$this->template->set("ganancias",$ganancias);
		$this->template->set("transaction",$transaction);
		$this->template->set("redes",$redesUsuario);
		$this->template->set("bonos",$bonos);
		$this->template->set("id",$id);
		$this->template->set("total_bonos",$total_bonos);
		$this->template->set("comision_todo",$comision_todo);
		$this->template->set("comisiones_directos",$comision_directos);
		$this->template->set("cobro",$cobro);
		$this->template->set("cobroPendientes",$cobroPendientes);
		$this->template->set("retenciones",$retenciones);

		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/tickets/manual');
	}

    function bitcoin_val(){

        if (!$this->tank_auth->is_logged_in()) {
            log_message('DEV',"Login now");// logged in
            return false;
        }

        $bitcoin = $this->playerbonos->getBitcoinValue();

        echo $bitcoin;
    }

    function auto_val(){
        if (!$this->tank_auth->is_logged_in()) {
            log_message('DEV',"Login now");// logged in
            return false;
        }

        $bitcoin = $this->playerbonos->getValueTicketAuto();

        echo $bitcoin;
    }

    function add_ticket(){

        $id_data = isset($_POST['id']) ? $_POST['id']+1 : 1;
        $bitcoin = $this->playerbonos->getBitcoinValue();

        $value_bitcoin = round($bitcoin, 2);
        echo '  <div class="col col-md-12" id="ticket_div_'.$id_data.'">
                    <section id="ticket_sec_'.$id_data.'" class="col col-sm-5">
                        <label class="label"><b>Ticket '.$id_data.'</b></label>
                        <label class="input">
                            <i class="icon-prepend fa fa-money"></i>
                            <input name="cobro[]" type="number" min="0.01" step="0.01"
                                   class="from-control ticket" id="cobro_'.$id_data.'" 
                                   onkeyup="CalcularSaldo('.$id_data.')" value="'. $value_bitcoin .'"/>
                        </label>
                        <a style="cursor: pointer;" onclick="auto_val('.$id_data.')">
                                Automatic Value
                                <i class="fa fa-cogs"></i></a>
                    </section>
                    <section id="ticket_balance_'.$id_data.'"
                             class="padding-10 alert-success col col-sm-7 text-left">
                        <h1 id="setRange">Range between <br/>
                            <strong class="minRange"></strong> USD and
                            <strong class="maxRange"></strong> USD</h1>
                    </section>
                </div>';
    }

	function edit_ticket()
	{
        $id = isset($_POST['id']) ? $_POST['id'] : false;

        $error = "ticket not found, Try again!!";
        if(!$id){
            echo $error;
            return false;
        }

        $ticket = $this->general->getTicket($id);

        if(!$ticket){
            echo $error;
            return false;
        }

        $editor = $this->setEditorTicket($ticket);

        echo "<div class='smart-form col-md-6' style='float: none !important;'>
                <form id='ticket_set' role='form'>$editor</form>
                </div>";
    }

    function update_ticket(){

	    $datos = $_POST;

	    if(isset($datos['date_final'])){
            $timestamp = strtotime($datos['date_final']);
            $date = date('Y-m-d', $timestamp);
            $datos['date_final'] =  "$date 21:00:00";
        }

        $datos["estatus"] = 'ACT';

        $json = json_encode($datos);
	    log_message('DEV',"update ticket :: $json");

	    $this->db->where("id",$datos['id']);
	    unset($datos['id']);
	    $this->db->update("ticket",$datos);

        echo "Ticket updated";

    }

    function estatus_ticket(){

        $id = isset($_POST['id']) ? $_POST['id'] : false;

        $error = "ticket not found, Try again!!";
        if(!$id){
            echo $error;
            return false;
        }

        $ticket = $this->general->getTicket($id);

        if(!$ticket){
            echo $error;
            return false;
        }

        $estatus = $ticket->estatus == 'DES' ? "ACT" : "DES";

        $datos =  array("estatus" => $estatus);

        $this->db->where("id",$id);
        $this->db->update("ticket",$datos);

        echo ($estatus == 'ACT') ? "Ticket Enabled" : "Ticket Disabled";

    }

    function get_ticket()
    {
	    $id = isset($_POST['id']) ? $_POST['id'] : false;

	    if(!$id){
	        echo "ticket not found, Try again!!";
	        return false;
        }

        $ticket = $this->general->getTicket($id);

        if(!$ticket){
            echo "ticket not found, Try again!!";
            return false;
        }

        $list = "";

        $i = 1;
        foreach ($ticket as $key => $data){
            $label = str_replace("_"," ",$key);

            if($key == "amount")
                $data = "$ $data USD </div><div>
                    <input class='hide ticket' value='$data' />";

            $list .= "<li class='dd-item' data-id='$i' >
                    <div class='dd-handle'><b>$label</b>: $data</div>
                    </li>";
            $i++;
        }

        echo "<div class='dd' style='float: none !important;' id='nestable'>
                <ul id='dd-list'>$list</ul>
                </div>";

        echo "<script>
                $('#nestable').nestable({ group : 1 });
                </script>";

    }

    function newTicket()
    {	if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}

        $tickets = isset($_POST['cobro']) ? $_POST['cobro'] : false;
        if(!$tickets){
			echo "ERROR <br>Tickets not found.";
			exit();
		}
	
		
		$id=$this->tank_auth->get_user_id();

        $comisiones = $this->modelo_billetera->get_total_comisiones_afiliado($id);
        $retenciones = $this->modelo_billetera->ValorRetencionesTotalesAfiliado($id);
        $cobrosPagos = $this->modelo_billetera->get_cobros_total_afiliado($id);
        $cobroPendientes = $this->modelo_billetera->get_cobros_pendientes_total_afiliado($id);
        $total_transact = $this->modelo_billetera->get_total_transact_id($id);
        $total_bonos = $this->model_bonos->ver_total_bonos_id($id);

        /*	echo $comisiones."<br>";
         * 	echo $total_bonos."<br>";
            echo $retenciones."<br>";
            echo $cobrosPagos."<br>";
            echo $cobroPendientes."<br>";
    */
        $where = "i.categoria = 4";
        $mercancia = $this->playerbonos->getMercancia($where);
        $tarifa = $mercancia[0]->costo;
        $red_item = $mercancia[0]->id;

        $nTickets = sizeof($tickets);
        if (gettype($tickets) == "array")
            $tarifa = $nTickets * $tarifa;

        $cobros = $tarifa;
        $cobros+= $retenciones + $cobrosPagos + $cobroPendientes;

        $comisiones = $comisiones - $cobros;
        $total = $comisiones + $total_transact + $total_bonos;

        #TODO: ? $total
        #$total = $total_transact-$tarifa;

        if($total > 0){

            $this->playerbonos->newTickets($id,$tickets);

			echo "Congratulations<br> Tickets added successfully.";
		}else {
			echo "ERROR <br>Balance Insufficient.";
		}

	}
	
	function automatic()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id              = $this->tank_auth->get_user_id();

        $isActived = $this->playerbonos->isActivedAfiliado($id);
        $isActived |= (int) date("H") < 21 ;
        if($this->general->isActived($id)!=0){
            redirect('/shoppingcart');
        }elseif(!$isActived){
            redirect('/listTickets');
        }

        #TODO: create automatic ticket
        redirect('/ov/tickets/manual');
	
		$usuario=$this->general->get_username($id);
		$style=$this->general->get_style($id);
	
		$redes = $this->model_tipo_red->listarTodos();
		$redesUsuario = $this->model_tipo_red->RedesUsuario($id);
		
		$ganancias=array();
		$comision_directos = array();
		$bonos = array();		
		
		foreach ($redesUsuario as $red){
			//$array_bono = $this->model_bonos->ver_total_bonos_id($id,$red->id,'');
			//$array_ganancias = $this->modelo_billetera->get_comisiones($id,$red->id);
			//$array_comision = $this->modelo_billetera->getComisionDirectos($id, $red->id);

			array_push($bonos,$this->model_bonos->ver_total_bonos_id_red($id,$red->id));
			array_push($ganancias,$this->modelo_billetera->get_comisiones($id,$red->id));
			array_push($comision_directos,$this->modelo_billetera->getComisionDirectos($id, $red->id));
		}
		
		$comision_todo= array(
				'directos' => $comision_directos,
				'ganancias' => $ganancias,
				'bonos' => $bonos,
				'redes' => $redesUsuario
		);
		
		$comisiones = $this->modelo_billetera->get_total_comisiones_afiliado($id);
		$cobro=$this->modelo_billetera->get_cobros_total($id);
		$cobroPendientes=$this->modelo_billetera->get_cobros_pendientes_total_afiliado($id);
		$retenciones = $this->modelo_billetera->ValorRetencionesTotales($id);
		$total_bonos = $this->model_bonos->ver_total_bonos_id($id);
		
		$transaction = $this->modelo_billetera->get_total_transacciones_id($id);	
		
        $psr = $this->modelo_billetera->get_psr($id);
        $this->template->set("psr",$psr);

		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		$this->template->set("id",$id);
		$this->template->set("redes",$redesUsuario);
		$this->template->set("bonos",$bonos);
		$this->template->set("total_bonos",$total_bonos);
		$this->template->set("comisiones",$comisiones);
		$this->template->set("comision_todo",$comision_todo);
		$this->template->set("ganancias",$ganancias);
		$this->template->set("transaction",$transaction);
		$this->template->set("comisiones_directos",$comision_directos);
		$this->template->set("cobro",$cobro);
		$this->template->set("cobroPendientes",$cobroPendientes);
		$this->template->set("retenciones",$retenciones);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/billetera/estadoCuenta');
	}
	
	function estado_historial()
	{
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
	
		$id=$this->tank_auth->get_user_id();
	
	
		$usuario= $this->general->get_username($id);
		$style=$this->general->get_style($id);
	
		$redes = $this->model_tipo_red->listarTodos();
		$redesUsuario = $this->model_tipo_red->RedesUsuario($id);
		
		$ganancias=array();
		$comision_directos = array();
		$bonos = array();		
		
		foreach ($redesUsuario as $red){
			array_push($bonos,$this->model_bonos->ver_total_bonos_id_red_fecha($id,$red->id,$_GET['fecha']));
			array_push($ganancias,$this->modelo_billetera->get_comisiones_mes($id,$red->id,$_GET['fecha']));
			array_push($comision_directos, $this->modelo_billetera->getComisionDirectosMes($id, $red->id, $_GET['fecha']));
		}
		
		$comision_todo= array(
				'directos' => $comision_directos,
				'ganancias' => $ganancias,
				'bonos' => $bonos,
				'redes' => $redesUsuario
		);		

		$retenciones = $this->modelo_billetera->ValorRetenciones_historial($_GET['fecha'],$id);
		$cobro=$this->modelo_billetera->get_cobros_afiliado_mes($id,$_GET['fecha']);
		$cobroPendiente=$this->modelo_billetera->get_cobros_afiliado_mes_pendientes($id,$_GET['fecha']);

		$total_bonos = $this->model_bonos->ver_total_bonos_id_fecha($id,$_GET['fecha']);
		
		/*echo date("Y-m", strtotime($_GET['fecha'])) ;
		exit();*/
		
		$transaction = $this->modelo_billetera->get_total_transacciones_id_fecha($id,$_GET['fecha']);
		
		$this->template->set("style",$style);
		$this->template->set("usuario",$usuario);
		$this->template->set("id",$id);
		$this->template->set("redes",$redesUsuario);
		$this->template->set("bonos",$bonos);
		$this->template->set("total_bonos",$total_bonos);
		$this->template->set("comision_todo",$comision_todo);
		$this->template->set("ganancias",$ganancias);
		$this->template->set("retenciones",$retenciones);
		$this->template->set("transaction",$transaction);
		$this->template->set("cobro",$cobro);
		$this->template->set("fecha",$_GET['fecha']);
		$this->template->set("cobroPendientes",$cobroPendiente);
		$this->template->set("comisiones_directos",$comision_directos);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/ov/header');
		$this->template->set_partial('footer', 'website/ov/footer');
		#$this->template->build('website/ov/billetera/dashboard');
		$this->template->build('website/ov/billetera/estado');
	}
	
	function historial_transaccion(){
	
		
		$id=$_POST['id'];
	
		//echo "dentro de historial : ".$id;
		
		$transactions = $this->modelo_billetera->get_transacciones_id($id);
		
		echo
		"<table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='80%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID</th>
					<th data-hide='phone,tablet'>date</th>
					<th data-hide='phone,tablet'>Type</th>
					<th data-hide='phone,tablet'>Subject</th>
					<th data-hide='phone,tablet'>Value</th>
				</thead>
				<tbody>";
		
		
			foreach($transactions as $transaction)
			{
				$color = ($transaction->tipo=="plus") ? "green" : "red";
				echo "<tr>
			<td class='sorting_1'>".$transaction->id."</td>
			<td>".$transaction->fecha."</td>
			<td style='color: ".$color.";'><i class='fa fa-".$transaction->tipo."-circle fa-3x'></i></td>
			<td>".$transaction->descripcion."</td>
			<td> $	".number_format($transaction->monto, 2)."</td>			
			</tr>";
					
				
			}		
			
		
		echo "</tbody>
		</table><tr class='odd' role='row'>";
	
	}
	
	function ventas_comision(){
	
		$ventas = $this->ventas_comisiones();
		$bonos = $this->bonos_comisiones();
		
		echo "<legend><b>Purchases</b></legend></br>";
		echo $ventas;
		
		echo "<hr/>";
		
		echo "<legend><b>Calculated Commissions</b></legend></br>";
		echo $bonos;
	
	}
	
	function ventas_comisiones(){
	
	
		$id=$_POST['id'];
		$fecha =isset($_POST['fecha']) ? $_POST['fecha'] : null;
	
		//echo "dentro de historial : ".$id;
	
		$ventas = ($fecha)
		? $this->modelo_billetera->get_ventas_comision_fecha($id,$fecha)
		: $this->modelo_billetera->get_ventas_comision_id($id);
	
		$total = 0 ;
		$ventas_table = "";
	
		if(!$ventas){return "<div class=''>Purchases have not been found</div>";}
	
		$ventas_table .=
		"<div style='overflow-y: scroll; height: 100px;'><table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='80%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID</th>
					<th data-hide='phone,tablet'>User</th>
					<th data-hide='phone,tablet'>Network</th>
					<th data-hide='phone,tablet'>Items</th>
					<th data-hide='phone,tablet'>Total</th>
					<th data-hide='phone,tablet'>Commission</th>
				</thead>
				<tbody>";
	
	
		foreach($ventas as $venta)
		{
				
			$ventas_table .= "<tr>
			<td class='sorting_1'>".$venta->id_venta."</td>
			<td>".$venta->nombres."</td>
			<td>".$venta->red."</td>
			<td>".$venta->items."</td>
			<td>".number_format($venta->total, 2)."</td>
			<td> $	".number_format($venta->comision, 2)."</td>
			</tr>";
	
			$total += ($venta->comision);
	
		}
			
		$ventas_table .= "<tr>
			<td class='sorting_1'></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
	
		$ventas_table .= "<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class='sorting_1'><b>TOTAL:</b></td>
			<td><b> $	".number_format($total, 2)."</b></td>
			</tr>";
	
		$ventas_table .= "</tbody>
		</table><tr class='odd' role='row'></div>";
	
		return $ventas_table;
	
	}
	
	function bonos_comisiones(){
	
	
		$id=$_POST['id'];
		$fecha =isset($_POST['fecha']) ? $_POST['fecha'] : null;
	
		//echo "dentro de historial : ".$id;
	
		$bonos = ($fecha) 
		 	? $this->model_bonos->detalle_bono_fecha($id,$fecha) 
		 	: $this->model_bonos->detalle_bono_id($id);
		 
		##return var_dump($bonos); 	
		 	
		$total = 0 ;
		$bonos_table = "";
		
		if(!$bonos||$bonos[0]->valor==0){return "<div class=''>Calculated Commissions haven't been found.</div>";}
	
		$bonos_table .=
		"<div style='overflow-y: scroll; height: 100px;'><table id='datatable_fixed_column1' class='table table-striped table-bordered table-hover' width='80%'>
				<thead id='tablacabeza'>
					<th data-class='expand'>ID Report</th>
					<th data-hide='phone,tablet'>Name</th>
					<th data-hide='phone,tablet'>Description</th>
					<th data-hide='phone,tablet'>Day</th>
					<th data-hide='phone,tablet'>Month</th>
					<th data-hide='phone,tablet'>Year</th>
					<th data-hide='phone,tablet'>Date</th>
					<th data-hide='phone,tablet'>Value</th>
				</thead>
				<tbody>";
	
	
		foreach($bonos as $bono)
		{
			
			$bonos_table .= "<tr>
			<td class='sorting_1'>".$bono->id."</td>
			<td>".$bono->bono."</td>
			<td>".$bono->descripcion."</td>
			<td>".$bono->dia."</td>
			<td>".$bono->mes."</td>
			<td>".$bono->ano."</td>
			<td>".$bono->fecha."</td>
			<td> $	".number_format($bono->valor, 2)."</td>
			</tr>";
	
			$total += ($bono->valor);
	
		}
			
		$bonos_table .= "<tr>
			<td class='sorting_1'></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
	
		$bonos_table .= "<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class='sorting_1'><b>TOTAL:</b></td>
			<td><b> $	".number_format($total, 2)."</b></td>
			</tr>";
	
		$bonos_table .= "</tbody>
		</table><tr class='odd' role='row'></div>";
		
		return $bonos_table;
	
	}


    private function setEditorTicket($ticket)
    {
        $list = "";

        $i = 1;
        $available = "|amount|id";

        if($ticket->estatus == 'DES')
            $available .="|date_final";

        $date_input = "class='datepicker' type='text' id='date_$ticket->id'";
        $amount_input = "type='number' class='ticket' step='0.01' 
                            min='1000' onkeyup='balance()' onchange='balance()'";
        $format = array(
            "date_final" => $date_input,
            "amount" => $amount_input,
            "id" => "class='hide'"
        );
        foreach ($ticket as $key => $data) {

            if (stripos($available, "|$key") === false)
                continue;

            $options = "type='text'";
            if (isset($format[$key]))
                $options = $format[$key];

            if (stripos($key, "date_") !== false)
                $data = date('Y-m-d', strtotime($data));

            $label = str_replace("_", " ", $key);
            $list .= "<section class='$key" . "_section'  >
                        $label
                    <label class='input'>
                    <input name='$key' $options value='$data' required />
                    </label>
                    </section>";
            $i++;
        }
        return $list;
    }

}
