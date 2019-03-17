<?php
$secure = isset($val) ? $val : false;

if (! $secure) {
    echo '<div class="col-md-6">
 <h4>ROBOT DETECTADO</h4>
 </div><script>window.location.href="/";</script>';
    exit();
}

$function_exists = function_exists("setDir");
echo "setDir ? [[ $function_exists ]] \n";
if(!$function_exists){
    function setDir(){
        $dir = str_replace("public_html", "erp.playerbitcoin", getcwd());
        $dir = str_replace("/bk", "", $dir);
        if(stripos($dir,"/erp.playerbitcoin")===false)
            $dir.="/erp.playerbitcoin";
        return $dir;
    }
}

function newPDO($db) {
	
	$hostname = $db['default']['hostname'];
	$username = $db['default']['username'];
	$password = $db['default']['password'];
	$database = $db['default']['database'];
	
	$pdo = new PDO(			
			'mysql:dbname=' . $database.			
			';host=' . $hostname .			
			';port:63343;',			
			$username,			
			$password,			
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")			
			);		
	
	// Habilitar excepciones
	
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $pdo;
}

function newResult($db,$query) {
    try {
        // Preparar la sentencia
        $db = newPDO ($db);
        $cmd = $db->prepare($query);

        // Relacionar y ejecutar la sentencia
        $cmd->execute();
        $result = $cmd->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    } catch (PDOException $e) {
        // Aquí puedes clasificar el error dependiendo de la excepción
        // para presentarlo en la respuesta Json
        return array();

    }
}

function newStatement($db,$query) {
	try {
		// Preparar la sentencia
		$db = newPDO ($db);
		$cmd = $db->prepare($query);
		
		// Relacionar y ejecutar la sentencia
		$cmd->execute();
		return $cmd ? 1 : 0;
		
	} catch (PDOException $e) {
		// Aquí puedes clasificar el error dependiendo de la excepción
		// para presentarlo en la respuesta Json
		return 0;
		
	}
}
if(!function_exists("setCommand")) {
    function setCommand($db, $file, $data = "")
    {
        $hostname = $db['default']['hostname'];
        $username = $db['default']['username'];
        $password = $db['default']['password'];
        $database = $db['default']['database'];

        $exec = "sh " . setDir() . "/bk/" . $file . " " . $hostname . " " . $username . " " . $password . " " . $database . " \"$data\"";

        return $exec;
    }
}
function newQuery($db,$query = "")
{
    $command = setCommand($db, "query.sh", $query);
    log_message("<db> $query </db>");
    #TODO: echo "SQL>> \n $query \n\n";
    $cmd = $command;
    $data = shell_exec($cmd);

    $datos = explode("\n", $data);
    
    if (! $datos)
        return false;
    
    $atributos = explode("	", $datos[0]);
    $result = setArray($datos, $atributos);
    return $result;
}

function setArray( $datos, $atributos) {
    
    unset($datos[sizeof($datos) - 1]);
    unset($datos[0]);
    
    for ($i = 1; $i <= sizeof($datos); $i ++) {
        $valores = explode("	", $datos[$i]);
        $datos[$i] = array();
        $k = 0;
        foreach ($valores as $valor) {
            
            if ($valor == "NULL")
                $valor = "";
            
            $datos[$i][$atributos[$k]] = $valor;
            $k ++;
        }
    }
    
    return $datos;
}

$db_config = setDir() . "/application/config/database.php";

$linea = "";
$file = fopen($db_config, "r");
while (! feof($file)) {
    $linea .= fgets($file) . "\n";
}
fclose($file);

$val = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
$texto = str_replace($val, "<?php ", $linea);

$fp2 = fopen(setDir() . "/bk/db_access.php", "w");
fputs($fp2, $texto);
fclose($fp2);

include (setDir(). "/bk/db_access.php");

function terminar($exit_ = 0){
    unlink(setDir(). "/bk/db_access.php");
    if($exit_ ==1) exit();
}

function log_message($texto =  ""){

    if(strlen($texto)<3)
        return false;

    $log_file = setDir() . "/logs/log.php";
    $linea=date('Y-m-d H:i:s')." - $texto \n\n ";
    $file = fopen($log_file, "a");
    fputs($file, $linea);
    fclose($file);
}