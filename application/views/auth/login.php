<!DOCTYPE html>
<?php $ci = &get_instance();
   	$ci->load->model("bo/model_admin");
        $empresa=$ci->model_admin->val_empresa_multinivel();  
        $nombre_empresa = $ci->general->issetVar($empresa,"nombre","NetworkSoft");
        $logo = $ci->general->issetVar($empresa,"logo","/logo.png");
        $icon = $ci->general->issetVar($empresa,"icono","/template/img/favicon/favicon.png");
        $web = $ci->general->issetVar($empresa,"web","/");
   	    $style=$ci->general->get_style(1);
        $style = array(
            $ci->general->issetVar($style,"bg_color","#00B4DC"),
            $ci->general->issetVar($style,"btn_1_color","#17222d"),
            $ci->general->issetVar($style,"btn_2_color","#17222d")
        );
?>
<html lang="en-us" id="extr-page">
    <head>
        <meta charset="utf-8">
        <title><?=$nombre_empresa?></title>
        <meta name="description" content="Software especializado en Multinivel">
        <meta name="author" content="<?=$nombre_empresa?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="/template/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="/template/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <!-- <link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-production.min.css"> -->
        <!-- <link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-skins.min.css"> -->

        <!-- SmartAdmin RTL Support is under construction
                 This RTL CSS will be released in version 1.5
        <link rel="stylesheet" type="text/css" media="screen" href="/template/css/smartadmin-rtl.min.css"> -->

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="/template/css/your_style.css"> -->

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <!-- <link rel="stylesheet" type="text/css" media="screen" href="/template/css/demo.min.css"> -->
        <!-- nuevos CSS para player Bitcoin. Farez Prieto @orugal-->
        <link rel="stylesheet" type="text/css" media="screen" href="/template/css/login.css">

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="<?=$icon?>" type="image/png">
        <link rel="icon" href="<?=$icon?>" type="image/png">

        <!-- #GOOGLE FONT -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- #APP SCREEN / ICONS -->
        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="/template/img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/template/img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/template/img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/template/img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="/template/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="/template/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="/template/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

    </head>

    <body class="animated fadeInDown">

        <div class="container-fluid noPadding noMargin">
            <div class="container">
                <div class="row">
                    <div class="col col-lg-4 col-md-4"></div>
                    <div class="col col-lg-4 col-md-4 columnaLogin ">
                        <center><img id="compania" src="/template/img/playerBitcoin/logo.png" alt=""><br></center><br>
                        <div class="contForm">
                            <form id="login-form" method="POST" action="/auth/login" class="smart-form client-form">
                                    <fieldset>
                                        <?php
                                        if (isset($data['errors'])) {
                                            $pswd = "";
                                            if (isset($data['errors']['login'])) {
                                                $login = 'Error in the account. ';
                                            }
                                            if (isset($data['errors']['password'])) {
                                                $pswd = 'Password error. ';
                                            }
                                            if (isset($data['errors']['blocked'])) {
                                                $pswd = 'Your account is blocked, try to enter in 30 Minutes.<br>';
                                            }
                                            if (isset($data['errors']['attempts'])) {
                                                $pswd .= 'You only have ' . $data['errors']['attempts'] . ' attempts left before the account is blocked.<br>';
                                            }
                                        }
                                        ?>

                                        <span style="color: red;"><?php if (isset($login)) echo $login ?></span>
                                        <span style="color: red;"><?php if (isset($pswd)) echo $pswd ?></span>
                                        <!-- <div class="hidden-xs hidden-md hidden-sm col-lg-6">
                                                 <img src="/template/img/empresario.jpg" alt="Empresario">
                                        </div>-->
                                        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                            <h2 class="tituloLogin"><i class="fa fa-lock txt-color-teal"></i> Log In</h2>
                                        </div>
                                        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
                                            <div class="form-group grupoCajas">
                                                <label for="login">Account</label>
                                                <input required class="form-control cajas" type="text" name="login" placeholder="Enter your id, user or email" id="login" title="Enter your account">
                                            </div>

                                            <div class="form-group grupoCajas">
                                                <label for="password">Password</label>
                                                <input required type="password" class="form-control cajas" placeholder="Enter the password" name="password" id="password" title="Enter your password">
                                            </div>
                                            <div class="form-group grupoCajas"><br>
                                                <button id="enviar" type="submit" class="btn btnLogin">
                                                    Log In
                                                </button>
                                            </div>
                                            <div class="form-group grupoCajas">
                                                <hr>
                                                    <a class="link_login" href="/auth/forgot_password">Forgot your password?</a>
                                            </div>
                                            
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <br>
                            <span class="derechos">
                            <small>Copyright © <?=date('Y')?> <a href="<?=$web?>" target="_blank">
                                <?=$nombre_empresa?></a>. All rights reserved.</small>
                            </span>
                    </div>
                    <div class="col col-lg-4 col-md-4"></div>
                </div>
            </div>
        </div>

        <?php $this->load->view('website/traslate'); ?>
        <!-- <div id="footer" class="fade in">
            <br />
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <small>Copyright © <?=date('Y')?> <a href="<?=$web?>" target="_blank">
                        <?=$nombre_empresa?></a> . All Rights Reserved.  </small>
            </div>
        </div> -->
        <!--================================================== -->	
       <!--  <style type="text/css" media="screen">
                .form_login{
                    background: rgba(<?= $ci->general->hex2rgb($style[2],true)?>,0.6) !important;
                        border: none;
                        box-shadow: 1px 1px 4px rgba(<?= $ci->general->hex2rgb($style[2],true)?>, 0.5);
                }	
                #login-form header{
                        background: none;
                }
                .smart-form fieldset{
                        background: none;
                }
                #header
                { 
                        text-align: center; 
                        color: <?=$style[2]?> !important;				
                        background: rgba(255,255,255,0.1) !important;

                }
                #header > div, #header{
                        height: 100% !important;
                        background: <?=$style[0]?> !important;
                }
                #compania{height: 10em; }
                #header h1{font-weight: bold !important;}
                header h2{
                    text-align: center; 
                    color: <?=$style[1]?> ;
                    font-weight: bold !important;
                    font-size: 2em;
                }
                #login-form{
                        padding: 1em 3em;
                }
                #enviar{	
                    color: #FFF;
                    font-weight: initial; 
                    height: 2.5em; 
                    background-color: <?=$style[1]?>;
                    border-color: rgba(<?= $ci->general->hex2rgb($style[1],true)?>,0.1);
                    font-size: 1.5em;
                }			
                #enviar:hover{							
                                        border-bottom: medium solid #268498;
                }	
                .link_login{							
                        color: <?=$style[1]?> !important;
                }		
                .header2{text-align: center; padding-bottom: 10px;}

                #footer
                {
                        height: 5em;
                        text-align: center;
                        color: #FFF !important;
                        background-color: <?=$style[2]?>;
                }
                #extr-page #main{
                    padding: 2% 5% 10% 5%;
                    background-size: cover;
                    background: <?=$style[0]?> no-repeat fixed 0 0;
                }
                .smart-form .label {
                        font-size: 1.5em;
                        color: <?=$style[1]?> !important;
                }
        </style> -->
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="/template/js/plugin/pace/pace.min.js"></script>

        <!-- spinner lib -->
        <script src="/template/js/spin.js"></script>
        <script src="/template/js/spinner-loader.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script> if (!window.jQuery) {
                        document.write('<script src="/template/js/libs/jquery-2.0.2.min.js"><\/script>');}</script>

        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script> if (!window.jQuery.ui) {
                        document.write('<script src="/template/js/libs/jquery-ui-1.10.3.min.js"><\/script>');}</script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
        <script src="/template/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

        <!-- BOOTSTRAP JS -->		
        <script src="/template/js/bootstrap/bootstrap.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="/template/js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="/template/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!--[if IE 8]>
                
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
                
        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="/template/js/app.min.js"></script>

        <script type="text/javascript">
            runAllForms();

            $(function () {
                // Validation
                $("#login-form").validate({
                    // Rules for form validation
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 3,
                            maxlength: 20
                        }
                    },

                    // Messages for form validation
                    messages: {
                        email: {
                            required: 'Please enter an email account',
                            email: 'Please enter a valid email account'
                        },
                        password: {
                            required: 'Please enter your email'
                        }
                    },

                    // Do not change code below
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
        </script>

    </body>
</html>