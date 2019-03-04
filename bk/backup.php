#!/usr/bin/php
<?php
date_default_timezone_set('UTC');#city
echo "Leyendo datos..."; 
	
	#function setDir($base="C:/wamp64/www/"){
    #function setDir($base="/var/www/"){
	function setDir($base="/home/playerbitcoin/"){
		$project="erp.playerbitcoin"; #"erp.clientes"
		#$project.="/rtm";#"erp.multinivel"
		return $base.$project;
	}
	
	function setCommand ($db,$file,$data = ""){
		$hostname = $db['default']['hostname'];
		$username = $db['default']['username'];
		$password = $db['default']['password'];
		$database = $db['default']['database'];
        #echo setDir()."/bk/".$file." ".$hostname." ".$username." ".$password." ".$database." \"$data\"";
		return "sh ".setDir()."/bk/".$file." ".$hostname." ".$username." ".$password." ".$database." \"$data\"";
	}
	
$code = "->playerbitcoin";#code
$val = md5(date('Y-m-d')."^".date('H:i:s').$code);    
	 require_once(setDir()."/bk/dataset.php");
echo "
>OK
Cargando base de datos...";
		
	$db_config=setDir()."/application/config/database.php";
	$linea="";
	$file = fopen($db_config, "r");
	while(!feof($file)){
		$linea.=fgets($file)."\n";
	}
	fclose($file);
			
	$val="<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');";
	$texto=str_replace($val, "<?php ", $linea);
		  
	$fp2 = fopen(setDir()."/bk/db_access.php", "w"); 
	fputs($fp2, $texto);
	fclose($fp2);
		
	include(setDir()."/bk/db_access.php");
echo "\n>OK\nCreando dump...";
	$command = setCommand($db,"bk_daily.sh");
	exec($command);
echo "\n>OK\n!Dump creado con exito!\n";

echo "\n\n>PROCESOS 1> AUTOBONO DIARIO\n";
include(setDir()."/bk/autobono.php");
echo "\n\nCargando Datos\n";
$autobono = new autobono($db);
echo "\n>OK\nProcesando Datos\n";
$afiliados = $autobono->calcular();
echo "\n>OK\n\n!PROCESO COMPLETADO!\n";	

exit();

echo "\n\nPROCESOS 2> Compresion dinamica:\n";
#include(setDir()."/bk/autored.php");

echo "\n>OK\nProcesando Datos\n";
#$autored = new autored($db);
echo "\n>OK\nRealizando Acciones\n";
#$autored->procesar();	
echo "\n>OK\n!Proceso realizado con exito\n";
	
