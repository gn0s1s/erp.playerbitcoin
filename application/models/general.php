<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require getcwd().'/TwoFactorAuth/lib/loader.php';
Loader::register('../lib','RobThree\\Auth');

use \RobThree\Auth\TwoFactorAuth;

class general extends CI_Model
{

	function __construct() {
		parent::__construct();
	
		$this->load->model('ov/model_perfil_red');
	}

    function changeSecret($id){

        $issuer = $this->config->item('website_name', 'tank_auth');
        $tfa = new TwoFactorAuth($issuer);
        $bits = 160;
        $secret = $tfa->createSecret($bits);
        // Though the default is an 80 bits secret (for backwards compatibility reasons) we recommend creating 160+ bits secrets (see RFC 4226 - Algorithm Requirements)
        $label = "authforID$id";
        $data = "secret_$id";
        $size = 200;
        $QRCodeImageAsDataUri = $tfa->getQRCodeImageAsDataUri($label, $secret, $size, $data);

        $this->db->query("update user_profiles set keyword = '$secret' where user_id = $id");

        echo '<div class="text-center">
<img width="300px" src="'.$QRCodeImageAsDataUri.'">
        <br/>
        <h3>Please, Scan this QR code for setup 2FA auth in your phone.</h3>        
</div>';

    }

    function valCodeSecret($id,$code = "0000"){
        $issuer = $this->config->item('website_name', 'tank_auth');
        $tfa = new TwoFactorAuth($issuer);

        $user = $this->model_perfil_red->get_user_id($id);

        if(!$user):
            return false;
        endif;

        $secret = $user[0]->keyword;

        $sizeof = strlen("$secret");
        $noSecret = $sizeof != 22;
        log_message('DEV',"secret :: $secret -> $sizeof : [[ $noSecret ]]");

        if($noSecret):
            echo "<p>Please, set up 2FA auth secret for Payment Security.".
                "<a href='/ov/networkProfile/profile'>Click Here</a></p>";
            return false;
        endif;

        log_message('DEV',"code :: $code");

        $verifyCode = $tfa->verifyCode($secret, $code);
        $isVerified = $verifyCode === true;

        return $isVerified;
    }

    function setToken()
    {
        $token = rand(100000, 999999);
        $token = rand($token/2, $token);
        $token = rand(0, $token/2);
        $start = "Z";
        $end = "A";
        $token2 = range($end, $start);
        $token = str_split($token);
        $max = sizeof($token2) - 1;
        foreach ($token as $k => $tk):
            $range = rand(0, $max);
            $range = rand($range/2, $range);
            $range = rand(0, $range/2);
            $char = $token2[$range];

            $token[$k] = $token[$k].$char;

            $start = $end;
            $end = $end == "Z" ? "A" : "Z";
            $token2 = range($start, $end);
        endforeach;
        $token = implode("", $token);

        if(sizeof($token)>10)
            $token = substr($token, 0, 10);

        return $token;
    }

	/* tickets */

    function delete_ticket($id)
    {
        $query = "DELETE FROM ticket WHERE id = $id";
        $this->db->query($query);
    }

    function getTicket($id){

        $query = "SELECT
                        *
                    FROM 
                        ticket
                    WHERE
                        id = $id";
        $q = $this->db->query($query);

        $result = $q->result();

        if($result)
            $result = $result[0];

        return $result;
    }

    function getGanadores(){

        $query = "SELECT
                            c.*,h.*,
                           concat(p.nombre,' ',p.apellido) nombres
                    FROM comision_bono c,
                         comision_bono_historial h,
                         user_profiles p,
                         users u
                    WHERE
                        c.id_bono_historial = h.id
                        AND p.user_id = u.id
                        AND u.id = c.id_usuario
                        AND c.id_bono = 1
                      AND c.valor > 0";
        $query.=" union SELECT 0,0,1,0,'?',1,1,'fake',
        date_format(now(),'%d'),date_format(now(),'%m'),date_format(now(),'%Y'),
        date_format(now(),'%Y-%m-%d'),fullname
        from fakewinners
        ";
        $q = $this->db->query($query);

        return $q->result();
    }

    /* end tickets*/

    function imagenPerfil($id){
        $image  = $this->model_perfil_red->get_images($id);

        $img_perfil="/template/img/avatars/male.png";
        foreach ($image as $img)
        {
            $cadena=explode(".", $img->img);
            if($cadena[0]=="user")
            {
                $img_perfil=$img->url;
            }
        }
        return $img_perfil;
    }

    function changeTimezone($date = "now",$timezone = 'UTC', $format = 'Y-m-d H:i:s'){
        $tz = new DateTimeZone($timezone);

        $date = new DateTime($date);
        $date->setTimezone($tz);
        return $date->format($format);
    }
	
	function isAValidUser($id,$modulo){
	
		$q=$this->db->query('SELECT cu.id_tipo_usuario as tipoId
							FROM users u , user_profiles up ,cat_tipo_usuario cu
							where(u.id=up.user_id)
							and (up.id_tipo_usuario=cu.id_tipo_usuario)
							and(u.id='.$id.')');
		$tipo=$q->result();
	
		$idTipoUsuario=$tipo[0]->tipoId;

		$perfiles = array(
			
				"OV" => $this->IsActivedPago($id),
				"comercial" => ($idTipoUsuario==4) ? true : false,
				"soporte" => ($idTipoUsuario==3) ? true : false,
				"logistica" => ($idTipoUsuario==5) ? true : false,
				"oficina" => ($idTipoUsuario==6) ? true : false,
				"administracion" => ($idTipoUsuario==7) ? true : false,
				"cedi" => ($idTipoUsuario==8) ? true : false,
				"almacen" => ($idTipoUsuario==9) ? true : false,
				
		);
		
		return ($idTipoUsuario==1) ? true :$perfiles[$modulo];
		
	}
	function get_status($id)
	{
		$q=$this->db->query('select id_estatus from user_profiles where user_id = '.$id);
		return $q->result();
	}

	function IsActivedPago($id){
		$q = $this->db->query('select estatus from user_profiles where user_id = '.$id);
		$estado = $q->result();
		
		if($estado[0]->estatus == 'ACT'){
			return true;
		}else{
			return false;
		}	
	}

    function setImage($id)
    {
        $image = $this->model_perfil_red->get_images($id);
        $img_perfil = "/template/img/avatars/male.png";
        foreach ($image as $img):
            $img_url = $img->url;
            $exists = file_exists(getcwd() . $img_url);
            if ($exists):
                $img_perfil = $img->url;
            endif;
        endforeach;
        return $img_perfil;
    }

    function setImageUser($id)
    {
        $image = $this->model_perfil_red->get_images($id);
        $img_perfil = "/template/img/avatars/male.png";
        foreach ($image as $img):
            $img_user = $img->img;
            $cadena = explode(".", $img_user);
            $isUserNamed =  $cadena[0] == "user";
            $img_url = $img->url;
            $exists = file_exists(getcwd() . $img_url);
            if ($isUserNamed && $exists):
                $img_perfil = $img->url;
            endif;
        endforeach;
        return $img_perfil;
    }

    function get_tipo($id)
	{
		$q=$this->db->query('select id_tipo_usuario from user_profiles where user_id = '.$id);
		return $q->result();
	}
  	function get_password($id)
	{
		$q=$this->db->query('select password from users where id = '.$id);
		return $q->result();
	}
	function get_style($id)
	{
	  	$q=$this->db->query('select * from estilo_usuario where id_usuario = '.$id);
	 	return $q->result();
	}
	function get_username($id)
	{
		$q=$this->db->query('select * from user_profiles where user_id = '.$id);
		return $q->result();
	}
	function get_user($id)
	{
		$q=$this->db->query('select username from users where id = '.$id);
		return $q->result();
	}
	function get_last_id()
	{
		$q=$this->db->query("SELECT id from users order by id desc limit 1");
		return $q->result();
	}
	function dato_usuario($email)
	{
		$q=$this->db->query("
			SELECT profile.user_id, profile.nombre nombre, profile.apellido apellido,
			profile.fecha_nacimiento nacimiento, profile.id_estudio id_estudio,
			profile.id_ocupacion id_ocupacion,
			profile.id_tiempo_dedicado id_tiempo_dedicado,
			sexo.descripcion sexo,
			edo_civil.descripcion edo_civil,
			estilos.bg_color, estilos.btn_1_color, estilos.btn_2_color
			from user_profiles profile
			left join cat_sexo sexo
			on profile.id_sexo=sexo.id_sexo
			left join estilo_usuario estilos on
			profile.user_id=estilos.id_usuario
			left join cat_edo_civil edo_civil on
			profile.id_edo_civil=edo_civil.id_edo_civil
			left join users on profile.user_id=users.id
			where users.email=$email$");
		return $q->result();
	}
	function update_login($id)
	{
		if(isset($id)){
		$q=$this->db->query('select last_login from users where id = '.$id);
		$q=$q->result();

            $last_login = $q[0]->last_login;
            $this->db->query("update user_profiles set ultima_sesion=' $last_login ' where user_id=$id");
		}
	}

	function getRetenciones(){
		$q=$this->db->query('SELECT * FROM cat_retencion where estatus="ACT" and duracion !="UNI"');
		return $q=$q->result();
	}
	
	function getRetencionesMes(){
		$q=$this->db->query('SELECT * FROM cat_retenciones_historial where month(now())=mes and year(now())=ano and id_afiliado=0');
		return $q=$q->result();
	}
	
	function isBlocked(){
 
		if($this->isBlockedExpired())
			return false;

        $ip_address = $this->input->ip_address();
        $q=$this->db->query("SELECT blocked FROM users_attempts where ip = '$ip_address'");
		$blocked=$q->result();
		
		if(!isset($blocked[0]->blocked))
			return false;

		if($blocked[0]->blocked==1)
			return true;
		return false;
	}
	
	function isBlockedExpired(){
        $ip_address = $this->input->ip_address();
        $fecha = date('Y-m-d H:i:s');
        $q=$this->db->query("SELECT ip FROM users_attempts where ip = '$ip_address' and (attempts>=5) and ('$fecha') > (last_login + INTERVAL 30 MINUTE)");
		
		$intentos=$q->result();
		
		if(!isset($intentos[0]->ip))
			return false;
		
		if($intentos[0]->ip){
			$this->unlocked();
		}
		return false;
	}
	
	function addAttempts(){
        $ip_address = $this->input->ip_address();
        $q=$this->db->query("SELECT attempts , ip FROM users_attempts where ip = '$ip_address'");
		$intentos=$q->result();

        $fecha = date('Y-m-d H:i:s');
        if(!isset($intentos[0]->ip)){
			$datos = array(
					'ip' => $ip_address,
					'last_login'   => $fecha,
					'attempts'    => '1',
			);
			$this->db->insert('users_attempts',$datos);
			return "5";
		}else if($intentos[0]->attempts>=5){
			$this->locked(); 
			return "None";
		}
		else {
            $intentos = ($intentos[0]->attempts) + 1;
            $this->db->query("update users_attempts set attempts ='$intentos' , last_login ='$fecha' where ip = '$ip_address'");
			return "".(6-($intentos));
		}
	}
	
	function locked(){
        $ip_address = $this->input->ip_address();
        $this->db->query("update users_attempts set blocked ='1' where ip = '$ip_address'");
	}
	
	function unlocked(){
        $ip_address = $this->input->ip_address();
        $this->db->query("update users_attempts set blocked ='0',attempts ='1' where ip = '$ip_address'");
		return true;
	}
	
	function get_temp_invitacion($token)
	{
		$q=$this->db->query("select * from temp_invitacion where token = '$token'");
		$token = $q->result();
		return $token;
	}
	
	function get_temp_invitacion_ACT($token)
	{
		$q=$this->db->query("select * from temp_invitacion where token = '$token' and estatus = 'ACT'");
		$token = $q->result();
		return $token;
	}
	
	function get_temp_invitacion_ACT_id($token)
	{
		$q=$this->db->query("select * from temp_invitacion where id = '$token' and estatus = 'ACT'");
		$token = $q->result();
		return $token;
	}
	
	function new_invitacion($email,$red,$sponsor,$debajo_de,$lado){
		
		//$time = time();
		$token = md5(/*$time."~".*/$red."~".$email."~".$sponsor."~".$debajo_de."~".$lado);	
		
			$dato=array(
					"token" =>	$token,
					"email" =>	$email,
					"red" =>	$red,
					"sponsor" =>	$sponsor,
					"padre" =>	$debajo_de,
					"lado" =>	$lado,
			);
			$this->db->insert("temp_invitacion",$dato);
		
		return ($this->get_temp_invitacion($token)) ?  $token : false;
		
	}
	
	function checkespacio ($temp){
		
		$exist = $this->model_perfil_red->exist_mail($_POST['email']);
		
		if ($exist){
			return  true;
		}
		$ocupado = $this->model_perfil_red->ocupado($temp);
		($ocupado) ? $this->model_perfil_red->trash_token($temp[0]->id) : ''; 
		return ($ocupado) ? true : false;
	}

    function issetVar($var, $type = false, $novar = false) {

        $result = isset($var) ? $var : $novar;

        if(isset($var[0]))
            $var = $var[0];

        if ($type)
            $result = isset($var->$type) ? $var->$type : $novar;

        $noAttr = !isset($var->$type);
        $noType = ($novar === false);
        $json = json_encode($var);
        if ($noAttr && $noType)
            log_message('DEV', "issetVar T:($type) :: $json");

        return $result;
    }
	
        function hex2rgb($hex,$str = false) {
            $hex = str_replace("#", "", $hex);
            $isShort = (strlen($hex) == 3);
            
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            
            if(!$isShort){
               $r = hexdec(substr($hex,0,2));
               $g = hexdec(substr($hex,2,2));
               $b = hexdec(substr($hex,4,2));
            }
            
            $rgb = array($r, $g, $b);
            
            if($str)
                $rgb = implode(",", $rgb);
            //return implode(",", $rgb); // returns the rgb values separated by commas
            return $rgb; // returns an array with the rgb values
         }
         
         function rgb2hex($rgb) {
            
            $trash = "rgb|rgba|(|)|;"; 
            $trash = explode("|", $trash);
            $fix = (gettype($rgb) == "string");
            if($fix){
                foreach ($trash as $t)
                    $rgb= str_replace ($t, "", $rgb);
                    
                $rgb = explode(",", $rgb);                
            } 
             
            $hex = "#";
            $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
            $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
            $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

            return $hex; // returns the hex value including the number sign (#)
         }

        function getAnyTime($date, $time = '1 month', $add = true)
        {
            $fecha_sub = new DateTime($date);
            if ($add)
                date_add($fecha_sub, date_interval_create_from_date_string("$time"));
            else
                date_sub($fecha_sub, date_interval_create_from_date_string("$time"));

            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

        function getNextTime($date, $time = 'month')
        {
            $fecha_sub = new DateTime($date);
            date_add($fecha_sub, date_interval_create_from_date_string("1 $time"));
            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

        function getLastTime($date, $time = 'month')
        {
            $fecha_sub = new DateTime($date);
            date_sub($fecha_sub, date_interval_create_from_date_string("1 $time"));
            $date = date_format($fecha_sub, 'Y-m-d');

            return $date;
        }

    function setFechaformato($fecha=false,$formato=0)
    {
        $f = array('Y-m-d H:i:s','Y-m-d');

        if(!$fecha)
            $fecha = date($f[0]);

        $fecha = strtotime($fecha);

        if(isset($f[$formato]))
            return date($f[$formato],$fecha);

        try {
            return date($formato,$fecha);
        } catch (Exception $e) {
            log_message('DEV',"fail conversion date :: $formato");
            return date($f[1],$fecha);
        }
    }

}