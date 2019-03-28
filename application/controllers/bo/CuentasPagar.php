<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CuentasPagar extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('bo/modelo_dashboard');
		$this->load->model('bo/general');
		$this->load->model('bo/modelo_comercial');
		$this->load->model('ov/model_perfil_red');
		$this->load->model('model_cat_tipo_usuario');
		$this->load->model('model_cat_sexo');
		$this->load->model('model_cat_edo_civil');
		$this->load->model('model_cat_estudios');
		$this->load->model('model_cat_ocupacion');
		$this->load->model('model_cat_estatus_afiliado');
		$this->load->model('model_cat_tiempo_dedicado');
		$this->load->model('model_cat_usuario_fiscal');
		$this->load->model('model_users');
		$this->load->model('model_user_profiles');
		$this->load->model('model_coaplicante');
		$this->load->model('bo/model_admin');
		$this->load->model('bo/model_mercancia');
		$this->load->model('bo/model_bonos');
		$this->load->model('ov/modelo_compras');
		$this->load->model('modelo_cobros');
		$this->load->model('model_excel');
		$this->load->model('cemail');
        $this->load->model('ov/modelo_billetera');
        $this->load->model('bo/modelo_pagosonline');

        require getcwd()."/BlockchainSdk/exec/WalletService.php";
	}

	function index(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		if(!$this->general->isAValidUser($id,"comercial"))
		{
			redirect('/auth/logout');
		}

		$usuario=$this->general->get_username($id);
		
		$style=$this->modelo_dashboard->get_style(1);
		
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/Cuentas/index');
	}
	
	function historial(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		if(!$this->general->isAValidUser($id,"comercial"))
		{
			redirect('/auth/logout');
		}

		$usuario=$this->general->get_username($id);
		
		$cobros = $this->modelo_cobros->listarTodos($_GET['inicio'],$_GET['final']);
		$style=$this->modelo_dashboard->get_style(1);
		$años = $this->modelo_cobros->añosCobros();
	
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set("cobros",$cobros);
		$this->template->set("años",$años);
	
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/Cuentas/historial');
	}
	
	function PorPagar(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		if(!$this->general->isAValidUser($id,"administracion"))
		{
			redirect('/auth/logout');
		}

		$usuario=$this->general->get_username($id);
		
		$style=$this->modelo_dashboard->get_style(1);
	
		$this->template->set("usuario",$usuario);

        $cobro_maximo =$this->model_admin->val_settings("auto_payment_limit");
        $this->template->set("cobro", $cobro_maximo);

        $payment_fee =$this->model_admin->val_settings("payment_fee");
        $this->template->set("payment_fee", $payment_fee);

        $transfer_fee =$this->model_admin->val_settings("transfer_fee");
        $this->template->set("transfer_fee", $transfer_fee);

		$this->template->set("style",$style);
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/administracion/Cuentas/PorPagar');
	}

    function deleteCobro()
    {
        if (!$this->tank_auth->is_logged_in()) {                                                                        // logged in
            echo "LOGOUT... PLEASE LOGIN AGAIN";
            return false;
        }

        $id_cobro = isset($_POST['id']) ? $_POST['id'] : false;
        $error = "ERROR <br>Payment Request not found.";
        if (!$id_cobro) {
            echo $error;
            return false;
        }

        $cobro =$this->modelo_billetera->get_cobro_id($id_cobro);
        if (!$cobro) {
            echo $error;
            return false;
        }

        $this->modelo_cobros->delete_cobro($id_cobro);

        echo "PAYMENT ABORTED";

        return true;

    }

    function payBlockchain()
    {
        if (!$this->tank_auth->is_logged_in()) {                                                                        // logged in
            echo "LOGOUT... PLEASE LOGIN AGAIN";
            return false;
        }

        $id_cobro = isset($_POST['id']) ? $_POST['id'] : false;
        $error = "ERROR <br>Payment Request not found.";
        if (!$id_cobro) {
            echo $error;
            return false;
        }

        $cobro =$this->modelo_billetera->get_cobro_id($id_cobro);
        if (!$cobro) {
            echo $error;
            return false;
        }

        $valor_pagar = isset($cobro->monto) ? $cobro->monto : 0;
        if ($valor_pagar <= 0) {
            echo "ERROR <br>Invalid Withdrawal value.";
            exit();
        }

        $id = $cobro->id_user;

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

        $cobrar = 0;#TODO: $valor_pagar;
        $cobrar+= $retenciones + $cobrosPagos +  $cobroPendientes;
        $comisiones_neto = $comisiones - $cobrar;
        $total = $comisiones_neto + $total_transact + $total_bonos;
        if($total < 0):
            echo "ERROR <br>Balance Insufficient.";
            return false;
        endif;

        $address = isset($cobro->address) ? $cobro->address : $cobro->cuenta;

        $success = $this->modelo_pagosonline->newTransferWallet($id, $valor_pagar, $address);
        $msg = "PAYMENT VIA BLOCKCHAIN : ERROR ON SENDING $ $valor_pagar";
        if ($success):
            $this->modelo_cobros->CambiarEstadoCobro($id_cobro);
            $msg = "PAYMENT VIA BLOCKCHAIN : $success BTC SENT";
            $this->sendEmailTransaction($id,$address,$valor_pagar,$total,$success);
            #TODO: $this->modelo_billetera->add_sub_billetera("SUB",$id,$valor_pagar,$msg);
        endif;

        echo $msg;

        return true;

        #TODO: $this->setPagoPendienteBanco($id, $ctitular, $cbanco);

    }

    private function sendEmailTransaction($id, $reference, $valor, $balance = 0, $converted = false)
    {
        $usuario = $this->general->get_email($id);
        $email = "you@domain.com";
        if (isset($usuario[0]->email))
            $email = $usuario[0]->email;

        $usuario = $this->general->get_user($id);
        $username = $usuario ? $usuario[0]->username : "ID: $id";
        if(!$converted)
            $converted = $valor;

        $date = date('Y-m-d H:i:s');
        $amount_str = "$ $valor ($converted BTC)";
        $total_str = "$ $balance";

        $data = array(
            "address" => $reference,
            "username" => $username,
            "amount" => $amount_str,
            "balance" => $total_str,
            "fecha" => $date
        );
        $this->cemail->send_email(5, $email, $data);
    }

    function update_amount(){
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            echo "LOGOUT... PLEASE LOGIN AGAIN";
            return false;
        }
        $id=$this->tank_auth->get_user_id();

        $cobro = isset($_POST['cobro']) ? $_POST['cobro'] : 'auto_payment_limit';
        if($cobro)
            $this->modelo_cobros->updateSettings($cobro);

        $fee = isset($_POST['fee']) ? $_POST['fee'] : 'payment_fee';
        if($fee)
            $this->modelo_cobros->updateSettings($fee,'payment_fee');

        $transfer = isset($_POST['transfer']) ? $_POST['transfer'] : 'transfer_fee';
        if($transfer)
            $this->modelo_cobros->updateSettings($transfer,'transfer_fee');

        echo "OK : changed amount -> 1|$cobro 2|$fee 3|$transfer";

	}
	
	function reporte_cobros(){
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		
		$cobros = $this->modelo_cobros->ConsultarCobrosFecha($fecha_inicio, $fecha_fin);
		
		$this->template->set("cobros",$cobros);
		$this->template->set_theme('desktop');
		$this->template->build('website/bo/comercial/Cuentas/CobrosSinPagar');
	}
	
	function reporte_cobros_excel()
	{
		//load our new PHPExcel library
		
		$f0 = $_GET['inicio'];
		$f1 = $_GET['fin'];
		
		$cobros = $this->modelo_cobros->ConsultarCobrosFecha($f0, $f1);
		
		if(!$cobros){
			redirect('/bo/CuentasPagar/PorPagar');
		}
		
		$this->load->library('excel');
		$this->excel=PHPExcel_IOFactory::load(FCPATH."/application/third_party/templates/reporte_generico.xls");
		
		$contador_filas = 0;
		$total = 0;
		$ultima_fila = 0;
		$setColRowExcel = "setCellValueByColumnAndRow";
		$setSheet = "getActiveSheet";
		for($i = 0;$i < sizeof($cobros);$i++)
		{
			$contador_filas = $contador_filas+1;
            $iter = $i + 8;
            $cobro = $cobros[$i];
            $this->excel->$setSheet()->$setColRowExcel(0, $iter, $cobro->id_cobro);
			$this->excel->$setSheet()->$setColRowExcel(1, ($iter), $cobro->fecha);
			$this->excel->$setSheet()->$setColRowExcel(2, ($iter), $cobro->usuario);
			$this->excel->$setSheet()->$setColRowExcel(3, ($iter), $cobro->banco);
			$this->excel->$setSheet()->$setColRowExcel(4, ($iter), $cobro->cuenta);
			$this->excel->$setSheet()->$setColRowExcel(5, ($iter), $cobro->titular);
			$this->excel->$setSheet()->$setColRowExcel(6, ($iter), $cobro->address);
			$this->excel->$setSheet()->$setColRowExcel(7, ($iter), $cobro->pais);
			$this->excel->$setSheet()->$setColRowExcel(8, ($iter), $cobro->swift);
			$this->excel->$setSheet()->$setColRowExcel(9, ($iter), $cobro->otro);
			$this->excel->$setSheet()->$setColRowExcel(10, ($iter), $cobro->postal);
			$this->excel->$setSheet()->$setColRowExcel(11, ($iter), $cobro->metodo_pago);
			$this->excel->$setSheet()->$setColRowExcel(12, ($iter), $cobro->monto);
			$total = $total + $cobro->monto;
			$ultima_fila = $iter;
			$usuario = $this->modelo_cobros->CambiarEstadoCobro($cobro->id_cobro);
			$this->enviar_email($usuario[0]->email, $usuario);
		}
		
		$subtitulos	= array(
		    "ID","Voucher Date","User",
            "Bank","Account","Owner",
            "Wallet_Address","Country","Swift",
            "ABA_IBAN_OTRO","Zipcode",
            "Payment Method","Amount","Status"
        );
		
		$this->model_excel->setTemplateExcelReport ("Cuentas Por Pagar",$subtitulos,$contador_filas,$this->excel);
		
		
		$this->excel->$setSheet()->$setColRowExcel(11, ($ultima_fila+1), "Total");
		$this->excel->$setSheet()->$setColRowExcel(12, ($ultima_fila+1), $total);
		
		$date = new \Datetime('now');

		$filename='Payment_vouchers_'.$f0.'_to_'.$f1.'_'.$date->format('Y-m-d H:i:s').'.xls'; //save our workbook as this file name
	/*	header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		 */
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save(getcwd()."/media/reportes/".$filename);
	//	$objWriter->save('php://output');

		redirect('/bo/CuentasPagar/Archivos');
	}
	
	function Email(){
		
		$usuario = $this->modelo_cobros->CambiarEstadoCobro(15);
		
		$cobro['username'] = $usuario[0]->username;
		$cobro['nombre'] = $usuario[0]->nombre;
		$cobro['apellido'] = $usuario[0]->apellido;
		$cobro['id_cobro'] = $usuario[0]->id_cobro;
		$cobro['banco'] = $usuario[0]->banco;
		$cobro['cuenta'] = $usuario[0]->cuenta;
		$cobro['titular'] = $usuario[0]->titular;
		$cobro['clave'] = $usuario[0]->clabe;
		$cobro['monto'] = $usuario[0]->monto;
		$cobro['email'] = $usuario[0]->email;
		$cobro['fecha'] = $usuario[0]->fecha;
		$this->load->view('email/Cobros-html', $cobro);
	}
	
	function enviar_email($email, $usuario)
	{
		$cobro['username'] = $usuario[0]->username;
		$cobro['nombre'] = $usuario[0]->nombre;
		$cobro['apellido'] = $usuario[0]->apellido;
		$cobro['id_cobro'] = $usuario[0]->id_cobro;
		$cobro['banco'] = $usuario[0]->banco;
		$cobro['cuenta'] = $usuario[0]->cuenta;
		$cobro['titular'] = $usuario[0]->titular;
		$cobro['clave'] = $usuario[0]->clabe;
		$cobro['monto'] = $usuario[0]->monto;
		$cobro['email'] = $usuario[0]->email;
		$cobro['fecha'] = $usuario[0]->fecha;
		
		$this->cemail->send_email(4, $email, $cobro);
	}
	
	function Archivero(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
		redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		
		if(!$this->general->isAValidUser($id,"comercial"))
		{
			redirect('/auth/logout');
		}
		
		$usuario=$this->general->get_username($id);
		
		$style=$this->modelo_dashboard->get_style(1);
		
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/Archivero/index');
	}
	
	function Archivos(){
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
		
		$id=$this->tank_auth->get_user_id();
	
		if(!$this->general->isAValidUser($id,"comercial"))
		{
			redirect('/auth/logout');
		}
	
		$usuario = $this->general->get_username($id);
		$style = $this->modelo_dashboard->get_style(1);
	
		$archivos = array();
		$ruta = getcwd()."/media/reportes/";
		if(is_dir($ruta)){
			if($aux = opendir($ruta)){
				while (($archivo = readdir($aux)) != false){
					if(!is_dir($archivo) && $archivo != 'index.html'){
						$archi = explode(".", $archivo);
						$filename = explode("de", $archi[0]);
						array_push($archivos, array('nombre' => $filename[0], 'fecha' => $filename[1],'ruta' => "/media/reportes/".$archivo));
					}
				}
			}
		}
		
		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set("archivos",$archivos);
		
		$this->template->set_theme('desktop');
		$this->template->set_layout('website/main');
		$this->template->set_partial('header', 'website/bo/header');
		$this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/Archivero/archivos_pagos');
	}


}
