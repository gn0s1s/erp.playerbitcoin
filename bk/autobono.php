<?php

include(setDir()."/bk/calculo.php");

class autobono
{
	
	public $fechaInicio = '';
	public $fechaFin = '';
	public $afiliados = array(),
        $ganadores = array();
	
	public $db = array();
    private $bitcoinVal = 0;

    function __construct($db){
		$this->db = $db;
	}

	function setFechaInicio($value = '')
	{
		if (! $value)
			$value = date('Y-m-d');
			
			$this->fechaInicio = $value;
	}
	
	function setFechaFin($value = '')
	{
		if (! $value)
			$value = date('Y-m-d');
			
			$this->fechaFin = $value . " 23:59:59";
	}

    function getGanadores() {
        $val = $this->ganadores;
        $this->ganadores = array();
        return $val;
    }

    function setGanadores($ganador) {

        array_push($this->ganadores,$ganador);

    }

	function getAfiliados() {
		$val = $this->afiliados;
		$this->afiliados = array();
		return $val;
	}
	
	function setAfiliados($afiliados) {
		
		array_push($this->afiliados,$afiliados);
		
	}
	
	/** principal **/

    public function calcularBitcoin(){

        #TODO: isPeriodo();

        $usuario= new calculo($this->db);
        $afiliados = $usuario->getUsuariosRed();

        $reparticion= array();

        foreach ($afiliados as $afiliado){

            $afiliado = $afiliado["id"];

            $calcular = $this->calcularBonos($afiliado,1);

            $reparticion[$afiliado] = $calcular;

            #TODO: $this->activos_procedure($afiliado);
        }

        $this->repartirGanadores();

    }
	
	public function calcular($bono = false){

		#TODO: isPeriodo();
		
		$usuario= new calculo($this->db);
		$afiliados = $usuario->getUsuariosRed();

		$reparticion= array();

		foreach ($afiliados as $afiliado){
			
			$afiliado = $afiliado["id"];

			$calcular = $this->calcularBonosExclude($afiliado,$bono);
			
			$reparticion[$afiliado] = $calcular;
			
			#TODO: $this->activos_procedure($afiliado);
		}
		return $reparticion;
		
	}

	function readBitcoinStats(){

        $db_config=setDir()."/bk/bitcoin.txt";
        $linea="";
        $file = fopen($db_config, "r");
        while(!feof($file)){
            $linea.=fgets($file)."\n";
        }
        fclose($file);

        $data = explode("\n",$linea);
        $stats = array();

        foreach ($data as $index => $line) {
            $row = explode(",",$line);
            $datetime = $row[0];
            $values = explode(";",$row[1]);
            $amount = $values[0];
            $row = array(
                "date_status" => $datetime,
                "amount" => $amount,
            );

            #TODO:
            $dateHour = date('Hi', strtotime($datetime));
            $condicion = $dateHour == "2100";
            echo "condicion :: $datetime -> $dateHour => [[ $condicion ]] \n";

            array_push($stats,$row);
            if($condicion)
                $this->insertDatos("bitcoin_stats",$row);
        }

        $json = json_encode($stats);
        echo "\n ". $json ." \n";
        return $stats;
    }

	private function repartirGanadores()
	{
		$ganadores = $this->getGanadores();
        $extra = json_encode($ganadores);
        $this->updateHistorical($extra);

		if(!$ganadores){
            $this->acumularSiguiente();
		    return false;
        }
        echo "EVAL acumulado \n";
		$valor = $this->getAcumulado();
		$valor/=sizeof($ganadores);

		if($valor <= 0)
			return false;

		foreach ($ganadores as $key => $ganador) {

            $datos = explode("|", $ganador);
            $id_ganador = $datos[0];
            $ticket = $datos[1];
            $this->repartirBono(1,$id_ganador,$valor,$ticket);
            $this->notificarJackpot($id_ganador, $valor );
        }

        $this->desactivarTickets();

		return true;	

	}

    function notificar($id, $msj="bienvenido...", $sub="importante", $f1=false, $url = "#"){

	    if(!$f1)
	        $f1 = date('Y-m-d H:i:s');

	    $data = array(
	        "fecha_fin" =>$f1,
            "nombre" => $sub,
            "descripcion" => $msj,
            "link" => $url,
            "user_id" => $id
        );

        $this->insertDatos("notificacion",$data);

        return true;
    }
	
	private function getAcumulado(){
        $tarifa = 2.5;

        $date_final = $this->getLastDayUTC();
        $format = 'Y-m-d H:i:s';
        $date_init = $this->getLastTime($date_final,"day", $format);
        echo "EVAL query $date_init - $date_final \n";
        $query = "SELECT 
                        sum((m.costo*t.bonus/100))
                        total
                    from ticket t,mercancia m,
                     cross_venta_mercancia c,
                      venta v,items i
                  where
                    m.id = c.id_mercancia
                    and c.id_venta = v.id_venta
                    and v.id_venta = t.reference
                    and i.categoria = 4
                    -- and t.date_creation > '$date_init'
                    and date_format(t.date_final,'%Y-%m-%d') =  date_format('$date_final','%Y-%m-%d') 
                    and t.estatus = 'ACT'";
        $q = newQuery($this->db,$query);

        if(!$q)
            return 0;

        $total = $q[1]["total"];
        echo "TOTAL GANANCIA :: $total \n\n";

        return $total;
    }

	private function getIDBonos($where = "")
	{
        #TODO: return array(array("id" => 1));

		$data = "SELECT
                    	   id
                        FROM
                            bono
                        WHERE
                            estatus = 'ACT' $where";
		
		$result = newQuery($this->db,$data);
		return $result;
	}
	
	
	private function getAfiliadosTodos()
	{
		$data = "SELECT
                    	   id
                        FROM
                            users
                        WHERE
                            id > 1";
		
		$result = newQuery($this->db,$data);
		return $result;
	}

    private function calcularBonosExclude($id_usuario,$id_bono = false){

        $where = "";
        if($id_bono)
            $where = "AND id not in ($id_bono)";

        $bonos = $this->getIDBonos($where);

        $parametro = array(
            "id_usuario" => $id_usuario,
            "fecha" => $this->getLastDay()
        );

        $repartido = array();

        foreach ($bonos as $bono){
            $id_bono = $bono["id"];
            $isActived = $this->isActived($id_usuario,$id_bono);

            if($id_bono == 1)
                $this->setBitcoinValue();

            if($isActived){
                $monto = $this->getValorBonoBy($id_bono, $parametro);
                $repartir = $this->repartirBono($id_bono,$id_usuario,$monto);
                $repartido[$id_bono] = $monto;

            }

        }

        return $repartido;

    }

	private function calcularBonos($id_usuario,$id_bono = false){

	    $where = "";
	    if($id_bono)
	        $where = "AND id in ($id_bono)";

		$bonos = $this->getIDBonos($where);

		$parametro = array(
		    "id_usuario" => $id_usuario,
            "fecha" => $this->getLastDay()
        );
		
		$repartido = array();

		foreach ($bonos as $bono){
			$id_bono = $bono["id"];
			$isActived = $this->isActived($id_usuario,$id_bono);

			if($id_bono == 1)
			    $this->setBitcoinValue();

			if($isActived){
				$monto = $this->getValorBonoBy($id_bono, $parametro);
				$repartir = $this->repartirBono($id_bono,$id_usuario,$monto);
				$repartido[$id_bono] = $monto;
				
			}
			
		}
		
		return $repartido;
		
	}
	
	private function repartirBono($id_bono, $id_usuario, $valor,$extra ="") {

		if($id_bono == 1 && $valor <= 0)
			return false;

		$fechaInicio = $this->getPeriodoFecha ( "QUI", "INI", '' );
		$fechaFin = $this->getPeriodoFecha ( "QUI", "FIN", '' );
		
		$historial = $this->getHistorialBono ( $id_bono, $fechaInicio, $fechaFin );
		
		if (! $historial)
			$historial = $this->newHistorialBono ( $id_bono, $fechaInicio, $fechaFin );
		
		if ($valor > 0)
			echo "Payment on $id_usuario : $ $valor | OK! \n\n";
		
		$data = "INSERT INTO comision_bono
				(id_usuario,id_bono,id_bono_historial,valor,extra)
				VALUES
				($id_usuario,$id_bono,$historial,$valor,'$extra')";
		
		newQuery ( $this->db, $data );
		
		// $this->cobrar($id_usuario, $valor, $fechaFin);
		
		return true;
	}
	
	private function cobrar($id_usuario, $monto, $fecha)
	{
		$cuenta_cobro = $this->get_cuenta_banco($id_usuario);
		
		if (! $cuenta_cobro)
			return false;
			
			$cuenta = $cuenta_cobro["cuenta"];
			$titular = $cuenta_cobro["titular"];
			$pais = $cuenta_cobro["pais"];
			$banco = $cuenta_cobro["banco"];
			
			$data = "INSERT INTO cobro
			(id_user,id_metodo,id_estatus,monto,fecha,cuenta,titular,banco,pais)
			VALUES
			($id_usuario,1,3,$monto,'$fecha','$cuenta','$titular','$banco','$pais')";
			
			newQuery($this->db, $data);
			
			return true;
	}
	
	private function get_cuenta_banco($id_usuario)
	{
		$data = "SELECT
		c.*,
		CONCAT(u.nombre, ' ', u.apellido) titular
		FROM
		cross_user_banco c,
		user_profiles u
		WHERE
		c.id_user = u.user_id
		AND u.user_id = $id_usuario
		AND c.estatus = 'ACT'";
		
		$result = newQuery($this->db, $data);
		
		if (! $result)
			return false;
			
			$valid = $result[1];
			
			return $valid;
	}
	
	private function newHistorialBono($id_bono, $fechaInicio, $fechaFin)
	{
		$dia = date('d', strtotime($fechaInicio));
		$mes = date('m', strtotime($fechaInicio));
		$anio = date('Y', strtotime($fechaInicio));
		
		$data = "INSERT INTO comision_bono_historial
		(id_bono,dia,mes,ano,fecha)
		VALUES
		($id_bono,$dia,$mes,$anio,'$fechaFin')";
		
		newQuery($this->db, $data);
		
		$result = $this->getHistorialBono($id_bono, $fechaInicio, $fechaFin);
		
		return $result;
	}
	
	private function getHistorialBono($id_bono, $fechaInicio, $fechaFin)
	{
		$data = "SELECT
		*
		FROM
		comision_bono_historial
		WHERE
		fecha  between '$fechaInicio' and '$fechaFin'
		AND id_bono = $id_bono";
		
		$result = newQuery($this->db, $data);
		
		if (! $result)
			return false;
			
			$historial = $result[1]["id"];
			
			return $historial;
	}
	
	/** preproceso **/

	function isActived ( $id_usuario,$id_bono = 0,$red = 1,$fecha = '' ){

        if($id_usuario == 2 || $id_bono == 1)
            return true;

        #TODO:

		$this->setFechaInicio($this->getPeriodoFecha("QUI", "INI", $fecha));
		$this->setFechaFin($this->getPeriodoFecha("QUI", "FIN", $fecha));
		
		#if($id_bono==1)
		#    $fecha = $this->setFechaQuincena($fecha);
		$isPaid = $this->isPaid($id_usuario,$id_bono);
		
		if($isPaid){
			return false;
		}		
		
		$isActived = $this->isActivedAfiliado($id_usuario,$red,$fecha,$id_bono);
		
		$isScheduled = ($id_bono > 1)
		? $this->isScheduled($id_usuario,$id_bono,$this->fechaFin) : true;
		
		echo "ID : $id_usuario -[$id_bono] PAGADO >> ".intval($isPaid)." | ACTIVO !! ".intval($isActived)." | AGENDADO :: ".intval($isScheduled)."\n";
		
		if(!$isActived||!$isScheduled){
			return false;
		}
		
		return true;
		
	}

    function isActivedPSR($id_usuario,$red = 1,$fecha = '',$bono = false){

        if($id_usuario==2)
            return true;

        $binario = 2;
        $isBinario = $bono == $binario;
        $puntos = $this->getEmpresa ("puntos_personales");

        $Afiliado = true;#$this->isAfiliadoenRed($id_usuario,$binario);

        if(!$Afiliado){
            log_message('DEV',"ID : $id_usuario not in BINARIO");
            return $isBinario ? false : 0;
        }

        $bono = $this->getBono($binario);
        $periodo = "UNI";#$this->issetVar($bono,"frecuencia","DIA");# "MES";

        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $fecha );
        if($this->fechaFin)
            $fechaFin = $this->fechaFin;

        $fechaInicio = $this->getAnyTime($fechaFin,"180 days");
        if($this->fechaInicio)
            $fechaInicio= $this->fechaInicio;

        $where = "AND i.categoria = 2";
        $venta = $this->getVentaMercancia($id_usuario,$fechaInicio,$fechaFin,2,false,$where);

        $Pasa = ( $venta ) ? true : false;

        log_message('DEV',"ID : $id_usuario PSR :: [[ $Pasa ]]");

        return $Pasa;
    }

    function isActivedAfiliado($id_usuario,$red = 1,$fecha = '',$bono = false){

        if($id_usuario==2)
            return true;

        $binario = 2;
        $isBinario = $bono == $binario;
        $puntos = $this->getEmpresa ("puntos_personales");

        $Afiliado = $this->isAfiliadoenRed($id_usuario,$binario);

        if(!$Afiliado){
            log_message('DEV',"ID : $id_usuario not in BINARIO");
            return $isBinario ? false : 0;
        }

        $bono = $this->getBono($binario);
        $periodo = "UNI";#$this->issetVar($bono,"frecuencia","DIA");# "MES";

        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $fecha );
        if($this->fechaFin)
            $fechaFin = $this->fechaFin;

        $fechaInicio = $this->getInicioFecha($id_usuario);
        if($this->fechaInicio)
            $fechaInicio= $this->fechaInicio;

        $venta = $this->getVentaMercancia($id_usuario,$fechaInicio,$fechaFin,5);

        $Pasa = ( $venta ) ? true : false;

        log_message('DEV',"ID : $id_usuario BINARIO :: [[ $Pasa ]]");

        return $Pasa;
    }

    function isAfiliadoenRed($id, $red = false,$order=false,$where=false)
    {
        $query = "SELECT -- imprimir 
* FROM afiliar WHERE id_afiliado = $id";

        if ($red)
            $query.=" AND id_red in ($red)";
        if ($where)
            $query.=$where;
        if ($order)
            $query .= " ORDER BY $order";

        $q = newQuery($this->db,$query);

        return $q;
    }

    private function getVentaMercancia($id,$f0,$f1,$tp=false,$mc=false,$WH="",$OD=false,$GP=false)
    {
        if ($tp)
            $WH .= " AND m.id_tipo_mercancia in ($tp)";

        if ($mc)
            $WH .= " AND cvm.id_mercancia in ($mc)";

        if ($GP)
            $GP = "GROUP BY cvm.id_mercancia";
        else
            $GP = "";

        if ($OD)
            $OD = "ORDER BY v.fecha DESC,v.id_venta DESC";
        else
            $OD = "";

        $query = "SELECT *
						FROM
							cross_venta_mercancia cvm,
							mercancia m,
                            items i,
							venta v
						WHERE
                            i.id = m.id
							AND m.id = cvm.id_mercancia
							AND cvm.id_venta = v.id_venta
							$WH
							AND v.id_user in ($id)
							AND v.id_estatus = 'ACT'
							AND v.fecha BETWEEN '$f0' AND '$f1 23:59:59'
						$GP $OD";

        $q = newQuery($this->db,$query);


        return $q;
    }

    function activos_procedure($id_usuario = 2)
	{
	    return false;#TODO:
	    
	    $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
	    $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
	    
	    $condicion = $this->getEmpresa ("puntos_personales");
	    
	    $puntos =  $this->getValoresby($id_usuario, $fechainicio, $fechafin);
	    $this->setCalculoDatos($id_usuario, $puntos, $fechainicio, $fechafin);
	    
	    if($puntos<$condicion){
	        $condicion*=2;
	        $fechainicio = $this->getPeriodoFecha("MES", "INI", '');
	        $fechafin = $this->getPeriodoFecha("MES", "FIN", '');
	        $puntos =  $this->getValoresby($id_usuario, $fechainicio, $fechafin);
	    }
	    
	    $activo = $puntos<$condicion;
	    
	    $this->setRedActivo($id_usuario,$activo); 
	    $this->setCalculoBonos($id_usuario, $fechainicio, $fechafin);
	    
	}
	
	private function setCalculoBonos($id_usuario, $fechainicio, $fechafin)
	{
	    $default = array("tipo"=>1,"item"=>0,"condicion"=>"PUNTOS");
	    $bonos=array($default);
	    
	    foreach ($bonos as $bono){
	        $valor =  $this->getValoresby($id_usuario, $fechainicio, $fechafin,$bono["tipo"],$bono["item"],$bono["condicion"]);
	        $this->setCalculoDatos($id_usuario, $valor, $fechainicio, $fechafin,$bono["tipo"],$bono["item"],$bono["condicion"]);
	    }
	    
	}
	
	private function setCalculoDatos($id_usuario,$valor,$fechainicio, $fechafin,$tipo = 0,$item = 0,$set = "PUNTOS"){
	    
	    newQuery($this->db,"DELETE FROM calculo_bonos
                        where id_afiliado = $id_usuario
                        and tipo = '$tipo' and item = '$item'
                        AND fecha BETWEEN $fechainicio AND CONCAT('$fechafin', ' 23:59:59')
                        AND condicion = '$set'");
	    
	    newQuery($this->db,"INSERT INTO calculo_bonos (id_afiliado,valor,condicion,tipo,item) 
			VALUES ($id_usuario,$valor,'$set','$tipo','$item');");
	    
	}
	
	private function setRedActivo($id_usuario,$estatus = false){
	    
	    $estatus = ($estatus) ? "ACT": "DES";
	    
	    $q = newQuery($this->db," update red set estatus = '$estatus' where id_usuario = $id_usuario");
	    
	}
	
	private function getValoresby($id_usuario, $fechainicio, $fechafin,$tipo = 0,$mercancia = 0,$set = "PUNTOS")
	{
	    $set = ($set == "COSTO") ? "m.costo" : "m.puntos_comisionables";
	    
	    if(!$fechainicio||!$fechafin){
	        $fechainicio = $this->getPeriodoFecha("QUI", "INI", '');
	        $fechafin = $this->getPeriodoFecha("QUI", "FIN", '');
	    }
	    
	    $where = "";
	    
	    if($tipo!=0){
	        $in = (gettype($tipo)=="array") ? implode(",", $tipo) : $tipo;
	        $where.=" AND m.id_tipo_mercancia in ($in)";
	    }
	    
	    if($mercancia!=0){
	        $in = (gettype($mercancia)=="array") ? implode(",", $mercancia) : $mercancia;
	        $where.=" AND m.id in ($in)";
	    }
	    
	    $query = "SELECT ( SELECT
						(CASE WHEN SUM($set * cvm.cantidad)
        				 THEN SUM($set * cvm.cantidad)
        				 ELSE 0 END) cart_val
        				FROM
        				    venta v,
        				    cross_venta_mercancia cvm,
                            mercancia m
        				WHERE
							m.id = cvm.id_mercancia
        				    AND v.id_venta = cvm.id_venta
        				    AND v.id_user in ($id_usuario)
        				    AND v.id_estatus = 'ACT'
        				    AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                            +
                            (SELECT
								 (CASE WHEN SUM($set * p.cantidad)
									THEN SUM($set * p.cantidad) ELSE 0 END)
                                 cedi_val
							FROM
								pos_venta o,
								venta v,
							    pos_venta_item p,
                                mercancia m
							WHERE
								p.id_venta = o.id_venta AND m.id = p.item
								AND o.id_venta = v.id_venta
								AND v.id_user in ($id_usuario)
								AND v.id_estatus = 'ACT'
								AND v.fecha BETWEEN '$fechainicio' AND concat('$fechafin',' 23:59:59') $where)
                                total ";
	    
	    $q = newQuery($this->db,$query);	    	    
	    
	    if(!$q)
	        return 0;
	        
	        return $q[1]["total"];
	}
	
	
	private function isRedActivo($id_usuario = 2)
	{
	    $q = newQuery($this->db,"select * from red where id_usuario = $id_usuario");	   
	    
	    if(!$q)
	        newQuery($this->db,"insert into red values (1,$id_usuario,0,'DES',0)");
	        
	        return true;
	}
	
	function isActivedAfiliado_bk($id_usuario,$red = 1,$fecha = '',$bono = false){
		
		if($id_usuario==2)
			return true;
			
			$puntos = $this->getEmpresa ("puntos_personales");
			$usuario= new calculo($this->db);
			
			#$productos=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,1);
			#$lider=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,5,8);
			#$intermedia=$this->getComprasUnidades($id_usuario,$this->fechaInicio,$this->fechaFin,5,7);
			
			$fechaInicio= ($this->fechaInicio) ? $this->fechaInicio :$this->getPeriodoFecha("QUI","INI", $fecha);
			$fechaFin= ($this->fechaFin) ? $this->fechaFin : $this->getPeriodoFecha("QUI", "FIN", $fecha );
			$fechaInicio2=$this->getPeriodoFecha("MES", "INI", $fechaFin);
			
			$bonoFecha = ($bono)&&($this->fechaInicio)&&($this->fechaFin) ? true : false;
			
			/*if($bonoFecha){
			 $valor = $usuario->getPuntosTotalesPersonalesView($id_usuario,$red,$fechaInicio,$fechaFin,"0","0","vb_activo");
			 $valorMes = $usuario->getPuntosTotalesPersonalesView($id_usuario,$red,$fechaInicio2,$fechaFin,"0","0","vb_activo_2");
			 }else{ 	*/
			$valor = $usuario->getComprasPersonalesIntervaloDeTiempo($id_usuario,$red,$fechaInicio,$fechaFin,"0","0","PUNTOS");
			$valorMes = $usuario->getComprasPersonalesIntervaloDeTiempo($id_usuario,$red,$fechaInicio2,$fechaFin,"0","0","PUNTOS");
			//}
			
			if(!$bonoFecha){
				$log_act = $fechaInicio." - ".$fechaFin;
				$log_act = "ID : $id_usuario -> ACTIVO { $log_act } : $puntos | $valor | $valorMes ";
				echo $log_act;
			}
			
			$pasaMes = ($puntos*2)<=$valorMes;
			
			$Pasa = ($puntos<=$valor||$pasaMes) ? true : false;
			
			return $Pasa;
	}
	
	private function getComprasUnidades($id_usuario = 2,$fechaInicio,$fechaFin,$tipo = 0,$mercancia = 0,$red = 1){
		
		if(!$fechaInicio||!$fechaFin){
			$fechaInicio = $this->getPeriodoFecha("QUI", "INI", '');
			$fechaFin = $this->getPeriodoFecha("QUI", "FIN", '');
		}
		
		$where = "";
		
		if($tipo!=0){
			$in = (gettype($tipo)=="array") ? implode(",", $tipo) : $tipo;
			$where.=" AND i.id_tipo_mercancia in ($in)";
		}
		
		if($mercancia!=0){
			$in = (gettype($mercancia)=="array") ? implode(",", $mercancia) : $mercancia;
			$where.=" AND i.id in ($in)";
		}
		
		$cart = "SELECT
		(CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
		FROM
		venta v,
		cross_venta_mercancia cvm,
		items i
		WHERE
		i.id = cvm.id_mercancia
		AND cvm.id_venta = v.id_venta
		AND v.id_user = $id_usuario
		AND i.red = $red
		$where
		AND v.id_estatus = 'ACT'
		AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$cedi = "SELECT
		(CASE WHEN (cvm.cantidad) THEN SUM(cvm.cantidad) ELSE 0 END) unidades
		FROM
		venta v,
		pos_venta_item cvm,
		items i
		WHERE
		i.id = cvm.item
		AND cvm.id_venta = v.id_venta
		AND v.id_user = $id_usuario
		AND i.red = $red
		$where
		AND v.id_estatus = 'ACT'
		AND v.fecha BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$query = "SELECT ($cart)+($cedi) unidades";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return 0;
			
			return intval($q[1]["unidades"]);
			
	}
	
	private function isPaid($id_usuario,$id_bono){
		
		$query = "SELECT
		*
		FROM
		comision_bono c,
		comision_bono_historial h
		WHERE
		c.id_bono_historial = h.id
		AND c.id_bono = h.id_bono
		AND h.id_bono = $id_bono
		AND c.id_usuario = $id_usuario
		AND h.fecha BETWEEN '$this->fechaInicio' AND '$this->fechaFin'
		#AND c.valor > 0";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = (sizeof($q)>0) ? true : false;
			
			return $valid;
			
	}
	
	private function isPaidHistorial($id_usuario,$historial){
		
		$query = "SELECT
		*
		FROM
		comision_bono c,
		comision_bono_historial h
		WHERE
		c.id_bono_historial = h.id
		AND c.id_bono = h.id_bono
		AND h.id = $historial
		AND c.id_usuario = $id_usuario
		#AND c.valor > 0";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = (sizeof($q)>0) ? true : false;
			
			return $valid;
			
	}
	
	private function isValidDate($id_usuario,$id_bono,$fecha = false,$dia = false){
		
		$bono = $this->getBono($id_bono);
		
		$mes_inicio = $bono[1]["mes_desde_afiliacion"];
		$mes_fin = $bono[1]["mes_desde_activacion"];
		
		if($mes_inicio<=0){
			return true;
		}
		
		if($fecha)
			$fecha = "'".$fecha."'";
			else
				$fecha = "NOW()";
				
				$mes_inicio*=2;
				
				$select = "DATE_FORMAT(created, '%Y-%m') < DATE_FORMAT(DATE_SUB($fecha, INTERVAL $mes_inicio WEEK),'%Y-%m')";
				
				if($dia){
					$select = "created < DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH)";
				}
				
				$query = "SELECT
				$select 'valid'
				FROM
				users
				WHERE
				id = ".$id_usuario;
				
				$q = newQuery($this->db, $query);
				
				
				if(!$q)
					return false;
					
					$valid = ($q[1]["valid"]==1) ? true : false;
					
					return $valid;
					
	}
	
	private function isScheduled($id_usuario,$id_bono,$fecha = ""){
		
		$bono = $this->getBono($id_bono);
		
		$mes_inicio = $bono[1]["mes_desde_afiliacion"];
		$mes_fin = $bono[1]["mes_desde_activacion"];
		$where = "";
		
		if(strlen($fecha)>2){
			$fecha = "'".$fecha."'";
		}else{
			$fecha = "NOW()";
		}
		
		$limiteInicio = "(CASE WHEN (DATE_FORMAT(fecha,'%d')<16) THEN CONCAT(DATE_FORMAT(fecha,'%Y-%m'),'-15') ELSE LAST_DAY(fecha) END)";
		
		if($mes_inicio>0){
			$where .= "DATE_FORMAT($fecha, '%Y-%m-%d') > ".$limiteInicio;
		}
		
		if($mes_fin>0){
			$mes_fin+=$mes_inicio;
			$where .= "DATE_FORMAT($fecha, '%Y-%m-%d') <= ".$limiteInicio;
		}
		
		$query = "SELECT
		$where 'valid'
		FROM
		venta
		WHERE
		id_estatus = 'ACT'
		AND id_user = ".$id_usuario
		." ORDER BY fecha asc
                    LIMIT 1";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return false;
			
			$valid = ($q[1]["valid"]==1) ? true : false;
			
			return $valid;
			
	}
	
	/** calculo **/
	
	function getValorBonoBy($id_bono,$parametro){
		switch ($id_bono){
			
			case 1 :
				
				return $this->getValorBonoBitcoin($parametro);
				
				break;
				
			default:
				return 0;
				break;
				
		}
		
	}
	
	private function getValorBonoBitcoin($parametro){
		
		$valores = $this->getBonoValorNiveles(1);
		
		$bono = $this->getBono(1);
		$periodo = "DIA";#TODO: isset($bono[1]["frecuencia"]) ? $bono[1]["frecuencia"] : "UNI";
		
		$fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
		$fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);
		
		$id_usuario = $parametro["id_usuario"];
		
		echo "between ($id_usuario) : $fechaInicio - $fechaFin \n";
		
		$isRange = $this->evalTicketsRange($id_usuario);

		if($isRange){
            $ganador = $id_usuario . "|" . $isRange;
            $this->setGanadores($ganador);
		    echo "GANADOR :: $id_usuario \n";
		}

		return 0;
	}
    function evalTicketsRange( $id, $ntwk = 1){

	    $date_final = $this->getLastDayUTC();
        $format = 'Y-m-d H:i:s';
        $date_init = $this->getLastTime($date_final,"day", $format);

        $query = "SELECT * FROM ticket WHERE user_id = $id 
                    AND estatus = 'ACT' 
                    and date_creation > '$date_init' 
                    and date_format(date_final,'%Y-%m-%d') = date_format('$date_final','%Y-%m-%d') ";

        $q = newQuery($this->db, $query);
        #TODO: $q = array(array("amount"=>1002.6));

        if(!$q)
            return false;

        foreach ($q as $ticket){
            $amount = $ticket["amount"];
            $ticket_id = $ticket["id"];
            $isTicket = $this->isTicketRange($amount);
            echo ">>> RANGE  ($id) :: $amount [$ticket_id] \n";
            if($isTicket){
                echo ">>> RANGE MATCHES ($id) :: $amount [$ticket_id] \n";
                return $ticket_id;
            }

        }

    }
    function isTicketRange($value){

        $bitcoin_value = $this->setBitcoinValue();

        if(!$value)
            return false;

        $min_value = (integer) ($bitcoin_value/5);
        echo (">>| [ $min_value*5 ]");
        $min_value *= 5;
        echo ("RANGE | $min_value");
        $max_value = $min_value+5;
        echo (" - $min_value \n");

        $valueMatches = $value < $max_value;
        $valueMatches &= $min_value <= $value;

        echo ">>>  EVAL RANGE ($value) :: $min_value - $max_value \n\n";

        return $valueMatches;

    }

	
	private function getAfiliados_A($id,$nivel,$fechaInicio,$fechaFin) {
		
		
		$where = "";#" AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$afiliados = array();
		for ($i=1; $i <= $nivel; $i++) {
			
			$this->getDirectosBy($id,  $i, $where);
			$directos = $this->getAfiliados();
			#echo ">> ".json_encode($directos));
			array_push($afiliados, implode(",", $directos));
		}
		
		$afiliados = implode(",", $afiliados);
		$afiliados = explode(",", $afiliados);
		
		#echo ">>> ".json_encode($afiliados));
		return $afiliados;
	}
	
	private function getMonto_A($id_usuario,$afiliados,$valores,$fechaInicio,$fechaFin,$red = 1) {
		
		$inscritos=array();
		
		#echo "afiliados: ".json_encode($afiliados));
		
		foreach ($afiliados as $afiliado){
			$valor=0;
			if($afiliado>0)
				$valor=$this->getComprasUnidades($afiliado,$fechaInicio,$fechaFin,5);
				#echo ">> $afiliado :  ".$valor);
				if($valor>0)
					#if($afiliado>0)
						array_push($inscritos, $afiliado);
		}
		
		$monto = 0;
		$cantidad = sizeof($inscritos);
		
		/*$trio = 0;
		foreach ($valores as $valor){
			$tri = ($valor["nivel"]*3);
			if($tri<=$cantidad){
				$monto = $valor["valor"];
				$trio = $tri;
			}
		}
		
		$fechaInicio=$this->getPeriodoFecha("ANO", "INI", '');
		$fechaFin=$this->getPeriodoFecha("ANO", "FIN", '');
		
		$membresia = array(7,8);
		$valor=$this->getComprasUnidades($id_usuario,$fechaInicio,$fechaFin,5,$membresia);
		
		if($trio>=9&&$valor>0){
			$monto*=2;
		}*/
		
		$monto = $valores[1]["valor"];
		
		echo "->> ".json_encode($inscritos)." : $monto X $cantidad ";
		
		$monto *= $cantidad;
		
		return $monto;
	}
        
	private function getAfiliados_B($valores,$id,$fechaInicio,$fechaFin) {
		
		
		$where = "";#" AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
		
		$afiliados = array();
		
		foreach ($valores as $nivel){
			
			if($nivel["nivel"]>0){
				
				$this->getDirectosBy($id,  $nivel["nivel"], $where);
				array_push($afiliados, $this->getAfiliados());
			}
		}
		
		return $afiliados;
	}
	
	private function getMonto_B($valores, $afiliados,$fechaInicio,$fechaFin,$red = 1) {
		$monto = 0;$lvl=0;
		$usuario= new calculo($this->db);
		$afiliados = $this->setScheduled($valores,$afiliados, $fechaInicio,2);
		for($i = 1;$i<=sizeof($valores);$i++){
			$Corre = ($i>1)&&isset($afiliados[$lvl]);
			if($Corre){
				$per = $valores[$i]["valor"]/100;
				#foreach ($afiliados[$lvl] as $afiliado){
				$afiliado = implode(",", $afiliados[$lvl]);
				$valor=$usuario->getCalculoPersonal($afiliado,$fechaInicio,$fechaFin,"0","0","PUNTOS");
				$valor*=$per;
				#$activoAfiliado = $this->isActivedAfiliado($afiliado);
				echo "->> $afiliado [2]: $i | ".($per*100)." % | ".$valor." | ".$monto;
				$monto+= $valor;
				#TODO:}
				$lvl++;
			}
		}
		return $monto;
	}
	
	private function setScheduled($valores,$afiliados,$fechaInicio,$id_bono=1){
		
		for ($i = 0; $i < sizeof($valores); $i ++) {
			$afiliados_scheduled = array();
			$Corre = isset($afiliados[$i]);
			if ($Corre) {
				foreach ($afiliados[$i] as $afiliado) {
					$isScheduled = $this->isScheduled($afiliado, $id_bono, $fechaInicio);
					if ($isScheduled) {
						#echo  " >>-> isScheduled [$afiliado]  :: " . intval($isScheduled));
						array_push($afiliados_scheduled, $afiliado);
					}
				}
				$afiliados[$i] = $afiliados_scheduled;
			}
		}
		
		return $afiliados;
		
	}
	
	private function setActivedAfiliados($valores,$afiliados,$fecha,$id_bono=1){
		
		for($i = 0;$i<sizeof($valores);$i++){
			$afiliados_actived = array();
			$Corre = isset($afiliados[$i]);
			if ($Corre) {
				foreach ($afiliados[$i] as $afiliado) {
					$activoAfiliado = $this->isActivedAfiliado($afiliado, 1, $fecha, $id_bono);
					if ($activoAfiliado) {
						#echo  " >->> isActived [$afiliado]  :: " . intval($activoAfiliado));
						array_push($afiliados_actived, $afiliado);
					}
				}
				$afiliados[$i] = $afiliados_actived;
			}
		}
		
		return $afiliados;
		
	}
	
	private function getValoresMatriz($id,$valores,$fechaInicio,$fechaFin){
		
		$isActivoMatriz = $this->isActivoMatriz($id,$fechaInicio,$fechaFin);
		
		if(!$isActivoMatriz){
			for ($i=(sizeof($valores)); $i > 3; $i--) {
				unset($valores[$i]);
			}
		}
		
		return $valores;
		
	}
	
	private function isActivoMatriz($id,$fechaInicio,$fechaFin)
	{
		$this->getDirectosBy($id, 1);
		$afiliados = $this->getAfiliados();
		
		$puntos = $this->getEmpresa ("puntos_personales");
		$usuario= new calculo($this->db);
		$inscritos = array();
		
		foreach ($afiliados as $afiliado){
			$valor=0;
			if($afiliado>0)
				$valor=$this->isActivedAfiliado($afiliado,1,$fechaFin,3);
				#$valor=$this->getComprasUnidades($afiliado,$fechaInicio,$fechaFin,1);
				if($valor>=$puntos)
					#if($valor>0)
						array_push($inscritos, $afiliado);
		}
		
		$afiliados = $inscritos;
		#echo json_encode($afiliados));
		
		$isActivoMatriz = (sizeof($afiliados)<3) ? false : true;
		return $isActivoMatriz;
	}
	
	
	private function getAfiliados_C($valores,$id) {
		
		$where = "";
		
		$afiliados = array();
		
		foreach ($valores as $nivel){
			
			if($nivel["nivel"]>0){
				$this->getAfiliadosBy($id,  $nivel["nivel"], $nivel["condicion_red"], $where,$id);
				
				array_push($afiliados, $this->getAfiliados());
			}
		}
		return $afiliados;
	}
	
	private function getMonto_C($valores, $afiliados,$fechaInicio,$fechaFin,$red = 1) {
		$monto = 0;$lvl=0;
		$usuario= new calculo($this->db);
		$afiliados = $this->setScheduled($valores,$afiliados, $fechaInicio,3);	
		
		for($i = 1;$i<=sizeof($valores);$i++){
			$Corre = ($i>1)&&isset($afiliados[$lvl]);
			if($Corre){
				$per = $valores[$i]["valor"]/100;
				#foreach ($afiliados[$lvl] as $afiliado){
				$afiliado = implode(",", $afiliados[$lvl]);
				$valor=$usuario->getCalculoPersonal($afiliado,$fechaInicio,$fechaFin,1,"0","PUNTOS");
				$valor*=$per;
				#$activoAfiliado = $this->isActivedAfiliado($afiliado);
				echo "->> $afiliado [3]: $i | ".($per*100)." % | ".$valor." | ".$monto;
				$monto+= $valor;
				#TODO:}
				$lvl++;
			}
		}
		return $monto;
	}	
	
	private function getMonto_D($valores, $afiliados,$fechaInicio,$fechaFin,$red = 1) {
		$monto = 0;$lvl=0;
		$afiliados = $this->setScheduled($valores,$afiliados, $fechaInicio,3);
		$afiliados = $this->setActivedAfiliados($valores,$afiliados, $fechaInicio,3);
		for($i = 1;$i<=sizeof($valores);$i++){
			$Corre = ($i>1)&&isset($afiliados[$lvl]);
			if($Corre){
				$per = $valores[$i]["valor"]/100;
				foreach ($afiliados[$lvl] as $afiliado){
					$valor=$this->getMontoBono($afiliado,3,$fechaInicio,$fechaFin);
					$valor*=$per;
					echo "->> $afiliado [4]: $i | ".($per*100)." % | ".$valor;
					$monto+= $valor;
				}
				$lvl++;
			}
		}
		return $monto;
	}
	
	private function duplicarRed($id_usuario,$red=1){
		
		$query = "UPDATE afiliar SET duplicado = 'ACT' WHERE id_red = $red AND id_afiliado = $id_usuario";
		$q = newQuery($this->db, $query);
		return true;
		
	}
	
	private function getMonto_E($valores, $afiliados,$fechaInicio,$fechaFin,$red = 1) {
		$monto = 0;$lvl=0;
		$usuario= new calculo($this->db);
		$afiliados = $this->setActivedAfiliados($valores, $afiliados, $fechaInicio,3);
		for($i = 1;$i<=sizeof($valores);$i++){
			$Corre = ($i>1)&&isset($afiliados[$lvl]);
			if($Corre){
				#foreach ($afiliados[$lvl] as $afiliado){
				$afiliado = ($afiliados[$lvl]) ? implode(",", $afiliados[$lvl]) : 0;
					$valor=$usuario->getComprasPersonalesIntervaloDeTiempo($afiliado,$red,$fechaInicio,$fechaFin,"0","0","COSTO");
					$monto+= $valor;
				#TODO:}
				$lvl++;
			}
		}
		return $monto;
	}
	
	private function isLlenadoRed($opcion_red, $afiliados)
	{
		unset($opcion_red[0]);
		
		$lvl=0;
		foreach ($opcion_red as $red){
			$frontales = sizeof($afiliados[$lvl]);
			if($frontales<$red["valor"]){
				$lvl = 0;
				break;
			}
			$lvl++;
		}
		
		if($lvl>0)
			return true;
			
			return false;
	}
	
	
	private function getMontoBono($id_usuario,$id_bono,$fechaInicio,$fechaFin){
		$query = "SELECT
		max(c.valor) valor
		FROM
		comision_bono c,
		comision_bono_historial h
		WHERE
		c.id_usuario = $id_usuario
		AND h.id_bono = c.id_bono
		AND c.id_bono = $id_bono
		AND c.id_bono_historial = h.id
		AND h.fecha between '$fechaInicio' and '$fechaFin'";
		
		$q = newQuery($this->db, $query);
		
		
		if(!$q)
			return 0;
			
			return $q[1]["valor"];
	}
	
	private function getBonoValorNiveles($id) {
		$q = newQuery($this->db, "SELECT * FROM cat_bono_valor_nivel WHERE id_bono = $id ORDER BY nivel asc");
		
		return $q;
	}
	
	private function getBono($id) {
		$q = newQuery($this->db, "SELECT * FROM bono WHERE id = $id");
		
		return $q;
	}
	
	private function getDirectosBy($id,$nivel,$where = "",$red = 1){
		
		$query = "SELECT
		a.id_afiliado id,
		a.directo
		FROM
		afiliar a,
		users u
		WHERE
		u.id = a.id_afiliado
		AND a.id_red = $red
		AND a.directo = $id
		$where";
		
		$q = newQuery($this->db, $query);
		
		$datos= $q;
		
		if(!$q){
			return;
		}
		
		$nivel--;
		foreach ($datos as $dato){
			
			if ($nivel <= 0){
				
				$this->setAfiliados ($dato["id"]);
				
			}else{
				$this->getDirectosBy($dato["id"], $nivel, $where,$red);
			}
		}
		
		
	}
	
	private function getAfiliadosBy ($id,$nivel,$tipo,$where,$padre = 2,$red = 1){
		
		$is = array("DIRECTOS" =>"a.directo","RED"=>"a.debajo_de");
		
		$query = "SELECT
		a.id_afiliado id,
		a.directo
		FROM
		afiliar a,
		users u
		WHERE
		u.id = a.id_afiliado
		AND a.id_red = $red
		AND a.debajo_de = $id
		$where";
		
		$q = newQuery($this->db, $query);
		
		$datos= $q;
		
		if(!$q){
			return;
		}
		
		$nivel--;
		foreach ($datos as $dato){
			
			if ($nivel <= 0){
				
				if($tipo != "DIRECTOS" || $padre == $dato["directo"]){
					$this->setAfiliados ($dato["id"]);
				}
				
			}else{
				$this->getAfiliadosBy($dato["id"], $nivel, $tipo, $where,$padre, $red);
			}
		}
		
		
	}
	
	private function getEmpresa($attrib = 0) {
		
		$q = newQuery($this->db, "SELECT * FROM empresa_multinivel GROUP BY id_tributaria");
		
		
		if(!$q){
			return 0;
		}
		
		if($attrib === 0){
			return $q;
		}
		
		return $q[1][$attrib];
		
	}
	
	
	/** complemento **/
	
	private function getLastDay() {

	    $query = "SELECT
					    DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY),
					            '%Y-%m-%d') fecha";
	    $q = newQuery($this->db,$query);
	    $fecha = $q[1]["fecha"]." 23:59:59"; #TODO: '2019-02-28';
	    return $fecha;

	}
	
	private function getPeriodoFecha ($frecuencia,$tipo,$fecha = ''){
		
		if(!$fecha)
			$fecha= $this->getLastDay();
			
			$periodoFecha = array(
					"SEM" => "Semana",
					"QUI" => "Quincena",
					"MES" => "Mes",
					"ANO" => "Ano"
			);
			
			$tipoFecha= array(
					"INI" => "Inicio",
					"FIN" => "Fin"
			);
			
			if($frecuencia=="UNI"){
				return  ($tipo == "INI") ? $this->getInicioFecha() : date('Y-m-d');
			}
			
			if(!isset($periodoFecha[$frecuencia])||!isset($tipoFecha[$tipo])){
				return $fecha;
			}
			
			$functionFecha = "get".$tipoFecha[$tipo].$periodoFecha[$frecuencia];
			
			return $this->$functionFecha($fecha);
			
	}
	
	private function getInicioFecha($id = false)
    {
        $query = "SELECT
                        date_format(MIN(created),'%Y-%m-%d') fecha
                    FROM
                        users";

        if($id)$query.=" WHERE id = $id";
		
		$q = newQuery($this->db, $query);
		
		
		$year = new DateTime();
		$year->setDate($year->format('Y'), 1, 1);
		
		if(!$q)
			date_format($year, 'Y-m-d');
			
			return $q[1]["fecha"];
			
	}
	
	private function getFinSemana($date) {
		$offset = strtotime($date);
		
		$dayofweek = date('w',$offset);
		
		if($dayofweek == 6){
			return $date;
		}
		else{
			return date("Y-m-d", strtotime('last Saturday', strtotime($date)));
		}
	}
	
	private function getInicioSemana($date) {
		
		$fecha_sub = new DateTime($date);
		date_sub($fecha_sub, date_interval_create_from_date_string('6 days'));
		$date = date_format($fecha_sub, 'Y-m-d');
		
		$offset = strtotime($date);
		
		$dayofweek = date('w',$offset);
		
		if($dayofweek == 0)
		{
			return $date;
		}
		else{
			return date("Y-m-d", strtotime('last Sunday', strtotime($date)));
		}
	}
	
	private function getInicioQuincena($date) {
		$dateAux = new DateTime();
		
		if(date('d',strtotime($date))<=15){
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 1);
			return date_format($dateAux, 'Y-m-d');
		}else {
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 16);
			return date_format($dateAux, 'Y-m-d');
		}
	}
	
	private function getFinQuincena($date) {
		
		$dateAux = new DateTime();
		
		if(date('d',strtotime($date))<=15){
			$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 15);
			return date_format($dateAux, 'Y-m-d');
		}else {
			return date('Y-m-t',strtotime($date));
		}
	}
	
	private function getInicioMes($date) {
		$dateAux = new DateTime();
		$dateAux->setDate(date('Y',strtotime($date)),date('m',strtotime($date)), 1);
		return date_format($dateAux, 'Y-m-d');
	}
	
	private function getFinMes($date) {
		return date('Y-m-t',strtotime($date));
	}
	
	private function getInicioAno($date) {
		$year = new DateTime($date);
		$year->setDate($year->format('Y'), 1, 1);
		return date_format($year, 'Y-m-d');
	}
	
	private function getFinAno($date) {
		$year = new DateTime($date);
		$year->setDate($year->format('Y'), 12, 31);
		return date_format($year, 'Y-m-d');
	}

    private function getAnyTime($date,$time = '1 month'){

        $fecha_sub = new DateTime($date);
        $q= newQuery($this->db,"select date_add('".$date."', interval ".$time.") fecha");
        $fecha_sub = $q ? $q[1]["fecha"] : date('Y-m-d');
        $date = date('Y-m-d',strtotime($fecha_sub));

        return $date;

    }

    private function getNextTime($date,$time = 'month',$format = 'Y-m-d'){

        $fecha_sub = new DateTime($date);
        $q= newQuery($this->db,"select date_add('".$date."', interval 1 ".$time.") fecha");
        $fecha_sub = $q ? $q[1]["fecha"] : date('Y-m-d');
        $date = date($format,strtotime($fecha_sub));

        return $date;

    }

    private function getLastTime($date,$time = 'month',$format = 'Y-m-d'){

        $fecha_sub = new DateTime($date);
        $q= newQuery($this->db,"select date_sub('".$date."', interval 1 ".$time.") fecha");
        $fecha_sub = $q ? $q[1]["fecha"] : date('Y-m-d');
        $date = date($format,strtotime($fecha_sub));

        return $date;

    }

    private function setBitcoinValue()
    {
        if($this->bitcoinVal > 0){
            echo ("BITCOIN ALREADY CHARGED : $this->bitcoinVal \n");
            return $this->bitcoinVal;
        }

        $API = false;
        $db = $this->db;
        include(setDir()."/bk/bitcoin.php");

        if(!$API){
            $this->bitcoinVal = 0;
            echo ("ERROR EN API COINMARKET \n");
            return 0;
        }

        $this->bitcoinVal = 3853.7;
        #TODO: $this->bitcoinVal = $API->newHistorical();
        echo "NEW BITCOIN ".date('Y-m-d')." $this->bitcoinVal \n";

        return $this->bitcoinVal;

    }

    private function acumularSiguiente()
    {
        $date_final = $this->getLastDayUTC();
        $format = 'Y-m-d H:i:s';
        $date_init = $this->getLastTime($date_final,"day", $format);
        $date_next = $this->getNextTime($date_final,"day", $format);

        $query = "SELECT 
                        t.*
                    from ticket t
                  where
                    t.date_creation >= '$date_init' 
                    and date_format(t.date_final,'%Y-%m-%d') <=  date_format('$date_final','%Y-%m-%d') 
                    and t.estatus = 'ACT'";
        $tickets = newQuery($this->db,$query);

        if(!$tickets)
            return false;

        $data = array(
            "bonus" => 25,
            "date_final" => $date_next,
        );

        foreach ($tickets as $ticket) {
            $ticket_id = $ticket["id"];
            $date_creation = $ticket["date_creation"];
            $nextCreation = $this->getNextTime($date_creation, "day", $format);
            #TODO: $data["date_creation"] = $nextCreation;
            $where = "id = $ticket_id";
            $this->updateDatos("ticket",$data,$where);
            echo "acumulando Ticket: $date_creation -> $ticket_id \n";
        }

        return true;

    }


    private function insertDatos($table,$datos){
        $attribs = array();$values=array();

        foreach ($datos as $key => $value){
            array_push($attribs, $key);
            $value = "'$value'";
            array_push($values, $value);
        }

        $query = "INSERT INTO $table (".implode(",", $attribs).")
                        VALUES (".implode(",", $values).")";

        newQuery($this->db,$query);

        return true;
    }

    private function updateDatos($table,$datos,$where = false){

        $values=array();

        foreach ($datos as $key => $value){
            $value = "$key = '$value'";
            array_push($values, $value);
        }

        if($where)
            $where = " WHERE ".$where;

        $query = "UPDATE $table SET ".implode(",", $values).$where;

        newQuery($this->db,$query);

        return true;
    }

    private function desactivarTickets()
    {
        $date_final = $this->getLastDayUTC();
        $format = 'Y-m-d H:i:s';
        $date_init = $this->getLastTime($date_final,"day", $format);

        $query = "SELECT 
                        t.*
                    from ticket t
                  where
                   -- t.date_creation > '$date_init' and
                     date_format(t.date_final,'%Y-%m-%d') <=  date_format('$date_final','%Y-%m-%d') 
                    and t.estatus = 'ACT'";
        $tickets = newQuery($this->db,$query);
        echo "TICKETS DES : ".json_encode($tickets)."\n";
        if(!$tickets)
            return false;

        $data = array("estatus" => 'BLK');
        foreach ($tickets as $ticket) {
            $ticket_id = $ticket["id"];
            $where = "id = $ticket_id";
            $this->updateDatos("ticket",$data,$where);
            $query = "DELETE FROM ticket WHERE id = $ticket_id";
            #newQuery($this->db, $query);

            echo "desactivando Ticket: $ticket_id \n";
        }

        return true;

    }

    private function getLastDayUTC($hour = " 21:00:00")
    {
        #TODO: Config UTC range tickets data
        date_default_timezone_set('UTC');
        $datetime = date('Y-m-d') . $hour;

        echo "fecha actual: $datetime \n";

        return $datetime;
    }

    private function notificarJackpot( $ganador,$valor = 0, $fecha = false)
    {
        if(!$fecha)
            $fecha = $this->getLastDayUTC();

        $msj = "Congrats, You won this jackpot: $ $valor.";
        $link = "/ov/accountStatus/accountHistory";
        $subject = "JACKPOT $fecha";
        $this->notificar($ganador, $msj, $subject, false, $link);
    }

    private function updateHistorical($extra = false)
    {
        $fecha = $this->getLastDayUTC();
        $data = array(
            "date_status" => $fecha,
            "amount" => $this->bitcoinVal,
        );

        if($extra){
            $data_extra = substr($extra,1,-1);
            $data_extra = str_replace('"','',$data_extra);
            $data["extra"] = $data_extra;
        }

        echo "NEW STATUS BITCOIN :".json_encode($data)."\n\n";

        $this->insertDatos("bitcoin_stats",$data);
    }

}