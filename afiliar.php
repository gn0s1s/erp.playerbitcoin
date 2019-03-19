<?php
$secure = isset($val) ? $val : false;
$link_retorno = "http://games.playerbitcoin.com";
if (! $secure) {
    echo '<div class="col-md-6">
 <h4>ROBOT DETECTED</h4>
 </div><script>window.location.href="/";</script>';
    exit();
}

$datos = isset($_POST) && sizeof($_POST) > 6 ? $_POST : false;

if (! $datos) {
    echo '<div class="col-md-6">
  <h4>ERROR, Please confirm data & try again.</h4>
  </div><script>window.location.href="/";</script>';
    exit();
}

function setDir_(){
   return str_replace("public_html", "erp.playerbitcoin", getcwd());
}

require_once(setDir_()."/bk/dataset.php");

$query = "SELECT * FROM users where lower(email) = '".strtolower($datos["email"])."'";
$isRepeated = newQuery($db,$query);

if ($isRepeated) {
    echo '<div class="col-md-6">
  <h4> Email already exists in another acoount.</h4>
     <p>If you want to log in on this email 
           <a href="'.$link_retorno.'">Push Here</a>.</p>
  </div><script>setTimeout (\'window.location.href="'.$link_retorno.'"\', 5000);</script>';
    terminar(1);
}

include(setDir_()."/bk/registro.php");
$registro = new registro($db,$datos);
$afilia = $registro->crearUsuario();

if(!$afilia){
        echo '<div class="col-md-6">
  <h4>ABORTED, Registration not finished.</h4>
  <p>Please, Verify data & try again.</p>
  </div>';
        terminar(1);
}

unlink(setDir_(). "/bk/db_access.php");

$userData = $registro->userData;