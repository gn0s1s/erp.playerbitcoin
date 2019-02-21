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
        $this->load->model('model_coinmarketcap');
        $this->load->model('web_personal');

    }

    private $afiliados = array();
    private $test = true;

    function index()
    {
        if (!$this->tank_auth->is_logged_in()) {                                                                        // logged in
            redirect('/auth');
        }

        $id = $this->tank_auth->get_user_id();
        $usuario = $this->general->get_username($id);

        if ($this->test) {
            $this->getPlayerDashboard($id);
            return;
        }

        $this->getAfiliadosRed($id);
        $numeroAfiliadosRed = count($this->afiliados);

        $id_sponsor = $this->modelo_dashboard->get_red($id);
        $ultima = $this->modelo_dashboard->get_ultima($id);
        $telefono = $this->modelo_dashboard->get_user_phone($id);
        $email = $this->modelo_dashboard->get_user_email($id);
        $username = $this->modelo_dashboard->get_user_name($id);
        $pais = $this->modelo_dashboard->get_user_country_code($id);

        $cuentasPorPagar = $this->modelo_dashboard->get_cuentas_por_pagar_banco($id);
        $notifies = $this->model_admin->get_notify_activos();

        $name_sponsor = ($id_sponsor) ? $this->general->get_username($id_sponsor[0]->id_usuario) : '';

        $image = $this->modelo_dashboard->get_images($id);
        $fondo = "/template/img/portada.jpg";
        $user = "/template/img/avatars/male.png";

        foreach ($image as $img) {
            $url = getcwd() . $img->url;
            if (file_exists($url)) {
                $user = $img->url;
            }
        }

        $style = $this->modelo_dashboard->get_style($id);

        $link_personal = $this->web_personal->val_web_personal($id);
        $this->template->set("link_personal", $link_personal);

        $actividad = $this->modelo_compras->is_afiliado_activo($id, date('Y-m-d'));

        $puntos_semana = $this->modelo_dashboard->get_puntos_personales_semana($id);
        $puntos_mes = $this->modelo_dashboard->get_puntos_personales_mes($id);
        $puntos_total = $this->modelo_dashboard->get_puntos_personales_total($id);

        $puntos_red_semana = $this->modelo_dashboard->get_puntos_red_semana($id);
        $puntos_red_mes = $this->modelo_dashboard->get_puntos_red_mes($id);
        $puntos_red_total = $this->modelo_dashboard->get_puntos_red_total($id);

        $ultimos_auspiciados = $this->modelo_dashboard->get_ultimos_auspiciados($id);

        $titulo = $this->titulo->getNombreTituloAlcanzadoAfiliado($id, date('Y-m-d'));

        $this->template->set("id", $id);
        $this->template->set("usuario", $usuario);
        $this->template->set("telefono", $telefono);
        $this->template->set("email", $email);
        $this->template->set("username", $username);
        $this->template->set("pais", $pais);
        $this->template->set("style", $style);
        $this->template->set("user", $user);
        $this->template->set("fondo", $fondo);
        $this->template->set("id_sponsor", $id_sponsor);
        $this->template->set("name_sponsor", $name_sponsor);
        $this->template->set("ultima", $ultima);
        $this->template->set("cuentasPorPagar", $cuentasPorPagar);
        $this->template->set("notifies", $notifies);
        $this->template->set("actividad", $actividad);

        $this->template->set("puntos_semana", $puntos_semana);
        $this->template->set("puntos_mes", $puntos_mes);
        $this->template->set("puntos_total", $puntos_total);
        $this->template->set("puntos_red_semana", $puntos_red_semana);
        $this->template->set("puntos_red_mes", $puntos_red_mes);
        $this->template->set("puntos_red_total", $puntos_red_total);

        $this->template->set("titulo", $titulo);

        $this->template->set("ultimos_auspiciados", $ultimos_auspiciados);

        $this->template->set("numeroAfiliadosRed", $numeroAfiliadosRed);

        $this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
        $this->template->build('website/ov/view_dashboard');
    }

    private function getAfiliadosRed($id)
    {
        $redesUsuario = $this->model_tipo_red->cantidadRedesUsuario($id);

        foreach ($redesUsuario as $redUsuario) {
            $red = $this->model_tipo_red->ObtenerFrontalesRed($redUsuario->id);

            if ($red) {

                if ($red[0]->profundidad == 0)
                    $this->preOrdenRedProfundidadInfinita($id, $redUsuario->id, $red[0]->frontal);
                else
                    $this->preOrdenRed($id, $redUsuario->id, $red[0]->frontal, $red[0]->profundidad);
            }
        }
    }


    function preOrdenRed($id, $id_red, $frontalidad, $profundidad)
    {

        $datos = $this->modelo_compras->traer_afiliados_red_frontalidad_profundidad($id, $id_red, $frontalidad);

        foreach ($datos as $dato) {

            if (($dato != NULL) && ($profundidad > 0)) {
                array_push($this->afiliados, $dato);
                $this->preOrdenRed($dato->id_afiliado, $id_red, $frontalidad, $profundidad - 1);
            }
        }
        $profundidad++;
    }

    function preOrdenRedProfundidadInfinita($id, $id_red, $frontalidad)
    {

        $datos = $this->modelo_compras->traer_afiliados_red_frontalidad_profundidad($id, $id_red, $frontalidad);

        foreach ($datos as $dato) {
            if (($dato != NULL)) {
                array_push($this->afiliados, $dato);
                $this->preOrdenRedProfundidadInfinita($dato->id_afiliado, $id_red, $frontalidad);
            }
        }
    }

    private function isViewBonos()
    {
        $query = "SELECT * FROM information_schema.TABLES
					WHERE table_name like 'vb_%'";

        $q = $this->db->query($query);
        $q = $q->result();

        if ($q) {
            echo "Se estan calculando los Bonos, Puedes Ingresar despues de unos minutos ...";
            sleep(3);
            echo "<script>window.location.href='/auth/logout';</script>";
            exit();
        }

    }

    private function getPlayerDashboard($id)
    {
        $usuario = $this->general->get_username($id);

        $nombre_completo = $usuario[0]->nombre . " " . $usuario[0]->apellido;
        $bienvenido = "Welcome, $nombre_completo";
        $imgbtc = "<img src=\"https://games.playerbitcoin.com/template/btc.PNG\"/>";

        $empresa = $this->web_personal->empresa();
        $web = "https://playerbitcoin.com/";
        #$web = $this->general->issetVar($empresa,"web",$web);

        $web .= '/dashboard/';
        $home = $this->setFormatDashboard($id, $web);
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


    private function setFormatDashboard($id, $web)
    {
        $web = $this->setWeb($web, "/ov/dashboard");
        $home = file_get_contents($web);
        $home = str_replace('href="', 'href="' . $web . '', $home);
        $home = str_replace('href="' . $web . 'http', 'href="http', $home);
        $home = str_replace('src="', 'src="' . $web . '', $home);
        $home = str_replace('src="' . $web . 'http', 'src="http', $home);
        $home = $this->setStylesDashboard($home);
        $home = $this->setJQueryDashboard($id, $home);
        $home = $this->getChart( $home);
        echo $home;

        return $home;
    }

    private function setWeb($web, $return = 'auth/login/')
    {

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
        } catch (Exception $e) {
            redirect($return);
            #log_message('ERROR','page not found : '.$web);
        }

        restore_error_handler();
    }


    private function setStylesDashboard($home)
    {
        $link = '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
 integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" 
 crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="screen" href="/template/css/font-awesome.min.css">
    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
    <link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-production.min.css">
    <!-- SmartAdmin RTL Support is under construction
         This RTL CSS will be released in version 1.5
    <link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-rtl.min.css"> -->

    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="/template/css/your_style.css"> -->
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="/template/css/demo.min.css">
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="/template/img/favicon/favicon.png" type="image/x-icon">
    <link rel="icon" href="/template/img/favicon/favicon.png" type="image/x-icon">

 ';
        $home = str_replace("</head>", "$link \n </head>", $home);

        return $home;
    }

    private function getChart($home)
    {
        $api = $this->model_coinmarketcap->getCoinmarket();
        $bitcoinCap = new $this->model_coinmarketcap(1);//$api->test);
        $getChart = $bitcoinCap->report();
        $chart = $bitcoinCap->getData();

        $chartobj = "6,4,3,5,2,4,6,4,3,3,4,5,4,3,2,4,5,";
        if($chart){
            $chart = $chart["data"]["quotes"];
            $chartobj = array();
            $max_val = false;
            $min_val = false;
            foreach($chart as $key => $data){
                $value = $data["quote"]["USD"]["close"];
                if($key == 0){
                    $min_val = $value;
                    $max_val = $value;
                }else if ($value > $min_val)
                    $max_val = $value;
                else if ($value < $max_val)
                    $min_val = $value;

                array_push($chartobj, $value);
                log_message('DEV',"chartData >> ".json_encode($data));
            }

            $middle = array_sum($chartobj)/sizeof($chartobj);
            $min_val -= $middle;
            $max_val -= $middle;
            $min_val = round($min_val,2);
            $max_val = round($max_val,2);

            $chartobj = implode(",",$chartobj);
        }

        $chart = ' <div class="col-xs-12">
                            <div class="well padding-10">
                                <h4 class="txt-color-teal">Balance
                                <a href="javascript:void(0);"   class="pull-right txt-color-white">
                                <i  class="fa fa-refresh"></i></a></h4>
                                <br>
                                <div class="sparkline"
                                     data-sparkline-type="line"
                                     data-fill-color="#e6f6f5"
                                     data-sparkline-line-color="#0aa699"
                                     data-sparkline-spotradius="5"
                                     data-sparkline-width="100%"
                                     data-sparkline-height="180px">'.$chartobj.'
                                </div>
                                <h4 class="air air-top-right padding-10 font-xl txt-color-teal">
                                + '.$max_val .'<i class="fa fa-caret-up fa-lg"></i>
                                    </small>
                                </h4>
                                <h5 class="air air-bottom-left padding-10 font-md text-danger">
                                '.$min_val .'
                                <i class="fa fa-caret-down fa-lg"></i>
                                    </small>
                                </h5>
                            </div>

                        </div>';


        $replace = '<img src="https://www.tradingview.com/x/hlruquwj/"';
        return str_replace($replace,$chart.' '.$replace.' class="hide"',$home);
    }

    private function setScripts(){
        return "<script src='/template/js/plugin/sparkline/jquery.sparkline.min.js'></script>
<script data-pace-options='{ \"restartOnRequestAfter\": true }' src=\"/template/js/plugin/pace/pace.min.js\"></script>
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js\"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src=\"/template/js/libs/jquery-2.0.2.min.js\"><\/script>');
    }
</script>
<script src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js\"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src=\"/template/js/libs/jquery-ui-1.10.3.min.js\"><\/script>');
    }
</script>
<!-- IMPORTANT: APP CONFIG -->
<script src=\"/template/js/app.config.js\"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src=\"/template/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js\"></script>
<!-- BOOTSTRAP JS -->
<script src=\"/template/js/bootstrap/bootstrap.min.js\"></script>
<!-- CUSTOM NOTIFICATION -->
<script src=\"/template/js/notification/SmartNotification.min.js\"></script>
<!-- JARVIS WIDGETS -->
<script src=\"/template/js/smartwidgets/jarvis.widget.min.js\"></script>
<!-- EASY PIE CHARTS -->
<script src=\"/template/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js\"></script>
<!-- SPARKLINES -->
<script src=\"/template/js/plugin/sparkline/jquery.sparkline.min.js\"></script>
<!-- JQUERY VALIDATE -->
<script src=\"/template/js/plugin/jquery-validate/jquery.validate.min.js\"></script>
<!-- JQUERY MASKED INPUT -->
<script src=\"/template/js/plugin/masked-input/jquery.maskedinput.min.js\"></script>
<!-- JQUERY SELECT2 INPUT -->
<script src=\"/template/js/plugin/select2/select2.min.js\"></script>
<!-- JQUERY UI + Bootstrap Slider -->
<script src=\"/template/js/plugin/bootstrap-slider/bootstrap-slider.min.js\"></script>
<!-- browser msie issue fix -->
<script src=\"/template/js/plugin/msie-fix/jquery.mb.browser.min.js\"></script>
<!-- FastClick: For mobile devices -->
<script src=\"/template/js/plugin/fastclick/fastclick.min.js\"></script>
<script src=\"/template/js/demo.min.js\"></script>
<!-- MAIN APP JS FILE -->
<script src=\"/template/js/app.min.js\"></script>
<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
<!-- Voice command : plugin -->
<script src=\"/template/js/speech/voicecommand.min.js\"></script>
<script type=\"text/javascript\">
    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    $(document).ready(function () {
        pageSetUp();
    })
</script>
<!-- Your GOOGLE ANALYTICS CODE Below -->
<script type=\"text/javascript\">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

</script>
";
    }

    private function setJQueryDashboard($id, $home)
    {
        $usuario = $this->general->get_username($id);

        $nombre_completo = $usuario[0]->nombre . " " . $usuario[0]->apellido;
        $bienvenido = "Welcome, $nombre_completo";
        $imgbtc = "<img src=\"https://games.playerbitcoin.com/template/btc.PNG\"/>";
        $logoutbutton = "<div class=\"row\"><a href=\"/auth/logout\" class=\"btn btn-danger\" " .
            "style=\"float: right;padding: 1em 2em !important\">" .
            "LOGOUT</a></div>";

        $coinmarket = "https://coinmarketcap.com/charts/";
        $widget = file_get_contents($coinmarket);
        $widget = str_replace("\n", " ", $widget);
        $widget = str_replace("</script>", "<\/script>", $widget);
        $widget = "";#TODO: configurar iframe

        $script = $this->setScripts();

        $script .= "
 <script>
                $('.btn.btn-registro8').attr('href','/ov/dashboard');
                $('#nuevaClase').attr('href','/ov/dashboard');
                $('.btn.btn-registro3').attr('href','/ov/tickets/manual');
                $('.btn.btn-registro4').attr('href','/ov/tickets/automatic');
                $('.btn.btn-registro6').attr('href','/shoppingcart');
                $('body').css('background','#0046B5 url(../img/bgCabezaN.jpg) no-repeat');
                $('.btn.btn-registro7').attr('href','/ov/wallet/requestPayment');
                $('img[src=\"https://www.tradingview.com/x/hlruquwj/\"]').parent().append('$widget');
                $('.btn.btn-registro6').removeAttr('data-target');
                $('.btn.btn-registro6').removeAttr('data-toggle');
                $('#txtUsuario').html('$bienvenido');
                $('.fa.fa-bitcoin.iconBitcoin').parent().prepend('$imgbtc');
                $('.fa.fa-bitcoin.iconBitcoin').remove();
                </script>";

        $home = str_replace("</body>", "$script \n </body>", $home);

        return $home;

    }

}
