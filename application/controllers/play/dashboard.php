<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->isViewBonos();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('ov/modelo_dashboard');
		$this->load->model('ov/general');
		$this->load->model('ov/modelo_compras');
		$this->load->model('modelo_premios');
		$this->load->model('model_tipo_red');
		$this->load->model('bo/model_admin');
		$this->load->model('bo/bonos/titulo');
		
		$this->load->model('web_personal');		
		
	}

	private $afiliados = array();
private $test = true;
	
	function index()
	{
        if (!$this->tank_auth->is_logged_in())
        {																		// logged in
            redirect('/auth');
        }

        $id=$this->tank_auth->get_user_id();
        $usuario=$this->general->get_username($id);

        if($this->test){
            $this->getPlayerDashboard($id);
            return;
        }
		
		$this->getAfiliadosRed($id);
		$numeroAfiliadosRed=count($this->afiliados);

		$id_sponsor=$this->modelo_dashboard->get_red($id);
		$ultima=$this->modelo_dashboard->get_ultima($id);
	    $telefono=$this->modelo_dashboard->get_user_phone($id);
	    $email=$this->modelo_dashboard->get_user_email($id);
	    $username=$this->modelo_dashboard->get_user_name($id);
	    $pais=$this->modelo_dashboard->get_user_country_code($id);
	    
	    $cuentasPorPagar=$this->modelo_dashboard->get_cuentas_por_pagar_banco($id);
	    $notifies = $this->model_admin->get_notify_activos();

	    $name_sponsor= ($id_sponsor) ? $this->general->get_username($id_sponsor[0]->id_usuario) : '';

		$image=$this->modelo_dashboard->get_images($id);
		$fondo="/template/img/portada.jpg";
		$user="/template/img/avatars/male.png";
		
		foreach ($image as $img) {
			$url=getcwd().$img->url;
			if(file_exists($url)){
				$user=$img->url;
			}
		}
		
		$style=$this->modelo_dashboard->get_style($id);
                
		$link_personal = $this->web_personal->val_web_personal($id);
                $this->template->set("link_personal",$link_personal);
                
		$actividad=$this->modelo_compras->is_afiliado_activo($id,date('Y-m-d'));

		$puntos_semana=$this->modelo_dashboard->get_puntos_personales_semana($id);
		$puntos_mes=$this->modelo_dashboard->get_puntos_personales_mes($id);
		$puntos_total=$this->modelo_dashboard->get_puntos_personales_total($id);
		
		$puntos_red_semana=$this->modelo_dashboard->get_puntos_red_semana($id);
		$puntos_red_mes=$this->modelo_dashboard->get_puntos_red_mes($id);
		$puntos_red_total=$this->modelo_dashboard->get_puntos_red_total($id);
		
		$ultimos_auspiciados=$this->modelo_dashboard->get_ultimos_auspiciados($id);
		
		$titulo=$this->titulo->getNombreTituloAlcanzadoAfiliado($id,date('Y-m-d'));
		
		$this->template->set("id",$id);
		$this->template->set("usuario",$usuario);
	    $this->template->set("telefono",$telefono);
	    $this->template->set("email",$email);
	    $this->template->set("username",$username);
	    $this->template->set("pais",$pais);
		$this->template->set("style",$style);
		$this->template->set("user",$user);
		$this->template->set("fondo",$fondo);
		$this->template->set("id_sponsor",$id_sponsor);
		$this->template->set("name_sponsor",$name_sponsor);
		$this->template->set("ultima",$ultima);
		$this->template->set("cuentasPorPagar",$cuentasPorPagar);
		$this->template->set("notifies",$notifies);
		$this->template->set("actividad",$actividad);
		
		$this->template->set("puntos_semana",$puntos_semana);
		$this->template->set("puntos_mes",$puntos_mes);
		$this->template->set("puntos_total",$puntos_total);
		$this->template->set("puntos_red_semana",$puntos_red_semana);
		$this->template->set("puntos_red_mes",$puntos_red_mes);
		$this->template->set("puntos_red_total",$puntos_red_total);
		
		$this->template->set("titulo",$titulo);
		
		$this->template->set("ultimos_auspiciados",$ultimos_auspiciados);
		
		$this->template->set("numeroAfiliadosRed",$numeroAfiliadosRed);
		
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/ov/view_dashboard');
	}

	private function getAfiliadosRed($id) {
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

	private function isViewBonos() {
		$query = "SELECT * FROM information_schema.TABLES
					WHERE table_name like 'vb_%'";
		
		$q=$this->db->query($query);
		$q= $q->result();
		
		if($q){
			echo "Se estan calculando los Bonos, Puedes Ingresar despues de unos minutos ...";
			sleep(3); 
			echo "<script>window.location.href='/auth/logout';</script>";		
			exit();
		}
		
	}

    private function getPlayerDashboard($id)
    {
        $usuario=$this->general->get_username($id);

        $nombre_completo = $usuario[0]->nombre." ".$usuario[0]->apellido;
        $bienvenido = "Welcome, $nombre_completo";
        $imgbtc="<img src=\"https://games.playerbitcoin.com/template/btc.PNG\"/>";

        $empresa = $this->web_personal->empresa();
        $web = "https://playerbitcoin.com/";
        #$web = $this->general->issetVar($empresa,"web",$web);

        $web .= '/dashboard/';
        $home = $this->setFormatDashboard($id,$web);
    }

    private function printTranslateElement()
    {
        echo '<script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement(
                    {
                        pageLanguage: \'es\',
                        layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT,
                        multilanguagePage: true,
                        gaTrack: true,
                        gaId: \'UA-85400219-1\'
                    },
                    \'google_translate_element\'
                );
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <style>
            #google_translate_element{
                background: #fff;
                padding: .5rem;
                border-radius: 5px 0 0 5px;
                z-index: 10000;
                right: 0;
            }
        </style>
<div class="pull-right" id="google_translate_element"></div>';
    }


    private function setFormatDashboard($id,$web)
    {
        $web = $this->setWeb($web,"/ov/dashboard");
        $home = file_get_contents($web);
        $home = str_replace('href="', 'href="' . $web . '', $home);
        $home = str_replace('href="' . $web . 'http', 'href="http', $home);
        $home = str_replace('src="', 'src="' . $web . '', $home);
        $home = str_replace('src="' . $web . 'http', 'src="http', $home);
        $home = $this->setStylesDashboard($home);
        $home = $this->setJQueryDashboard($id,$home);

        echo $home;

        return $home;
    }

    private function setWeb($web,$return = 'auth/login/') {

        set_error_handler(
            create_function(
                '$severity, $message, $file, $line',
                'throw new ErrorException($message, $severity, $severity, $file, $line);'
            )
        );

        try {
            $s = file_get_contents($web);
            #log_message('ERROR',strlen($s));
            return $web;
        }
        catch (Exception $e) {
            redirect($return);
            #log_message('ERROR','page not found : '.$web);
        }

        restore_error_handler();
    }


    private function setStylesDashboard($home)
    {
        $link  = '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
 integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" 
 crossorigin="anonymous">';
        $home = str_replace("</head>","$link \n </head>",$home);

        return $home;
    }


    private function setJQueryDashboard($id,$home)
    {
        $usuario=$this->general->get_username($id);

        $nombre_completo = $usuario[0]->nombre." ".$usuario[0]->apellido;
        $bienvenido = "Welcome, $nombre_completo";
        $imgbtc="<img src=\"https://games.playerbitcoin.com/template/btc.PNG\"/>";
        $logoutbutton = "<div class=\"row\"><a href=\"/auth/logout\" class=\"btn btn-danger\" ".
            "style=\"float: right;padding: 1em 2em !important\">".
            "LOGOUT</a></div>";

        $coinmarket = "https://coinmarketcap.com/charts/";
        $widget =  file_get_contents($coinmarket);
        $widget = str_replace("\n"," ",$widget);
        $widget = str_replace("</script>","<\/script>",$widget);
        $widget = "";#TODO: configurar iframe

        $script = " <script>
                $('.btn.btn-registro8').attr('href','/ov/dashboard');
                $('#nuevaClase').attr('href','/ov/dashboard');
                $('.btn.btn-registro6').attr('href','/shoppingcart');
                $('.btn.btn-registro7').attr('href','/ov/wallet/requestPayment');
                $('img[src=\"https://www.tradingview.com/x/hlruquwj/\"]').parent().append('$widget');
                $('.btn.btn-registro6').removeAttr('data-target');
                $('.btn.btn-registro6').removeAttr('data-toggle');
                $('#txtUsuario').html('$bienvenido');
                $('.fa.fa-bitcoin.iconBitcoin').parent().prepend('$imgbtc');
                $('.fa.fa-bitcoin.iconBitcoin').remove();
                </script>";

        $home = str_replace("</body>","$script \n </body>",$home);

        return $home;

    }

}
