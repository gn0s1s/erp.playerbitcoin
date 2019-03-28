<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class playerbonos extends CI_Model
{

    private $afiliados = array();
    private $temp = false;
    private $fechaInicio = '';
    private $fechaFin = '';
    private $remanente = array(0,0);
    private $bitcoinCap;

    function __construct()
    {
        parent::__construct();
        $this->load->model('/bo/bonos/afiliado');
        $this->load->model('/model_coinmarketcap');
        $api = $this->getCoinmarket();
        $test = isset($api->test) ? $api->test : 1;
        $this->bitcoinCap = new $this->model_coinmarketcap($test);
    }

    function getTemp()
    {
        $val = $this->temp;
        $this->temp = array();
        #log_message("DEV","<tmp> ".json_encode($val)."</tmp>");
        return $val;
    }
    function getTempRows($implode = false,$del =  false)
    {
        $val = $this->temp;
        if(!$val)
            return false;
        if($del)
            $this->temp = array();
        #log_message("DEV","<tmp> ".json_encode($val)."</tmp>");
        if($implode)
            $val = implode(",", $val);
        return $val;
    }
    function setTempRows($tmp) {
        if(gettype($this->temp)!="array")
            $this->temp = array();
        foreach ($this->temp as $tmps)
            if($tmps == $tmp)
                return false;
        array_push($this->temp,$tmp);
    }

    function getAfiliados($log = false)
    {
        $val = $this->afiliados;
        $this->afiliados = array();
        if($log)
            log_message("DEV","<afiliados> ".json_encode($val)."</afiliados>");
        if($this->temp)
            $this->getTemp();
        return $val;
    }

    function setAfiliados($afiliados) {
        if(gettype($this->afiliados)!="array")
            $this->afiliados = array();

        array_push($this->afiliados,$afiliados);
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

        $this->fechaFin = $value;
    }

    function getTituloAfiliado($id_usuario,$red = 1,$fecha = '' )
    {
        $query ="SELECT * FROM cross_rango_user 
                    WHERE id_user = $id_usuario";

        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return false;

        $id = $this->issetVar($q,"id_rango",0);

        return $this->getTitulo("nombre","id = $id");
    }

    /**
     * @param $id : id afiliado
     * @param int $ntwk : red
     * @param mixed $value : valor de boleto
     */
    function setTicketRange($value = false,  $ntwk = 1){

        # $bitcoin_value = $this->getBitcoinValue();

        if(!$value)
            $value = $this->getValueTicketAuto();

        $min_value = (integer) ($value/5);
        log_message('DEV',"->->| $min_value");
        $min_value *= 5;
        $max_value = $min_value+5;

        return array($min_value,$max_value);

    }

    function isTicketRange($value = false, $id, $ntwk = 1){

        $bitcoin_value = $this->getBitcoinValue();

        if(!$value)
            $value = $this->getValueTicketAuto();

        $min_value = (int) $bitcoin_value/5;
        $min_value *= 5;
        $max_value = $min_value+5;

        $valueMatches = $value < $max_value;
        $valueMatches &= $min_value <= $value;

        return $valueMatches;

    }

    function isActived ( $id_usuario,$id_bono = 0,$red = 1,$fecha = '' )
    {
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $this->setFechaInicio($this->getPeriodoFecha($periodo,"INI", $fecha));
        $this->setFechaFin($this->getPeriodoFecha($periodo, "FIN", $fecha));

        $isActived = $this->isActivedAfiliado($id_usuario,$red);
        $isPaid = $this->isPaid($id_usuario,$id_bono,$red);
        $isScheduled = $this->isValidDate($id_usuario,$id_bono);

        $part= "P:($isPaid) A:($isActived) S:($isScheduled)";
        $log = "ID:$id_usuario BONO:$id_bono RED:$red ACTIVO: [$part]";
        log_message('DEV',$log);

        if($isPaid||!$isActived||!$isScheduled)
            return false;

        return true;
    }

    function isActivedAfiliadoRedes($id_usuario,$nored = 1)
    {
        $redes = $this->getBonoRedes($id_usuario,$nored);

        if(!$redes)
            return false;

        $actividad = array();
        foreach ($redes as $red){
            $nombre_red= strtoupper($red->nombre);
            $id_red = $red->id_red;
            $valor = $this->isActivedAfiliado($id_usuario,$id_red);
            $actividad[$nombre_red] = $valor;
        }

        return $actividad;
    }

    function isActivedAfiliadoRedesID($id_usuario,$nored = 1)
    {
        $redes = $this->getBonoRedes($id_usuario,$nored);

        if (! $redes)
            return false;

        $actividad = array();
        foreach ($redes as $red) {
            $nombre_red = strtoupper($red->nombre);
            $id_red = $red->id_red;
            $valor = $this->isActivedAfiliado($id_usuario, $id_red);
            $actividad[$id_red] = $valor;
        }

        return $actividad;
    }


    function isActivedCompras($id_usuario,$red = 1,$fecha = false)
    {
        if($id_usuario==2)
            return true;

        $suscripcion = array(0,30,90,30);

        if(!$fecha)
            $fecha=date('Y-m-d');

        $fecha = $this->getPeriodoFecha("UNI","FIN",$fecha);
        for($item = 1; $item<sizeof($suscripcion);$item++){
            $time = $suscripcion[$item];
            $fechaInicio=$this->getAnyTime($fecha,"$time days");
            $valor=$this->getVentaMercancia($id_usuario,$fechaInicio,$fecha,false,$item,"",true);

            if($valor){
                log_message('DEV',"Subs $item -> $fechaInicio - $fecha");
                return true;
                break;
            }

        }
        log_message('DEV',"Subs 0 -> $fechaInicio - $fecha");
        return false;
    }

    function isActivedPSR($id_usuario,$data = false,$fecha = '',$bono = false){

        if($id_usuario==2)
            return true;

        $binario = 2;
        $isBinario = $bono == $binario;
        $puntos = $this->getEmpresa ("puntos_personales");

        $Afiliado = true;#$this->isAfiliadoenRed($id_usuario,$binario);

        if(!$Afiliado){
            log_message('DEV',"ID (PSR) : $id_usuario not in BINARIO");
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

        return  $venta;
    }

    function isActivedAfiliado($id_usuario,$red = 1,$fecha = '',$bono = false){

        if($id_usuario==2)
            return true;

        $binario = 2;
        $isBinario = $bono == $binario;
        $puntos = $this->getEmpresa ("puntos_personales");

        $Afiliado = $this->isAfiliadoenRed($id_usuario,$binario);

        if(!$Afiliado){
            log_message('DEV',"ID : $id_usuario not in VIP");
            return $isBinario ? false : 0;
        }

        $bono = $this->getBono($binario);
        $periodo = "UNI";#$this->issetVar($bono,"frecuencia","DIA");# "MES";

        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $fecha );
        #if($this->fechaFin) $fechaFin = $this->fechaFin;

        $fechaInicio = $this->getInicioFecha($id_usuario);
        #if($this->fechaInicio) $fechaInicio= $this->fechaInicio;

        $venta = $this->getVentaMercancia($id_usuario,$fechaInicio,$fechaFin,5);

        $Pasa = ( $venta ) ? true : false;

        log_message('DEV',"ID : $id_usuario VIP :: [[ $Pasa ]]");

        return $Pasa;
    }

    function isActivedAfiliado_bk($id_usuario,$red = false)
    {
        if($id_usuario==2)
            return true;

        $where = "AND id_red != 1 AND estatus = 'ACT'";

        if($red)
            $where.=" AND id_red = $red";

        $red_activa = $this->getLastRed($id_usuario,$where);

        return ($red_activa);
    }

    function getLastRed($id_usuario,$where = "")
    {
        $query = "SELECT * FROM afiliar 
                    WHERE id_afiliado = $id_usuario 
                        $where
                        ORDER BY id DESC";

        $q = $this->db->query($query);
        $q =$q->result();

        if (! $q)
            return false;

        $valid = $this->issetVar($q,"red",1);

        return $valid;
    }

    public function getNombreRed($id_red)
    {
        $dato_red = $this->getTipoRed($id_red);
        $nombre_red = $this->issetVar($dato_red, "nombre", "Red");

        return $nombre_red;
    }

    public function getLastAfiliar($id, $red)
    {
        $query = "SELECT max(id) id FROM afiliar WHERE id_afiliado = $id AND id_red = $red";

        $q = $this->db->query($query);
        $q = $q->result();

        $id = $q ? $q[0]->id : rand(1000, 9999);
        return $id;
    }

    public function isCuotaBinario($id,$mercancias)
    {
        $cuota = 2;
        $binario = 2;
        $isCuota = false;
        foreach ($mercancias as $mercancia) {
            $isCuota = $mercancia->categoria == $cuota;
            if($isCuota)
                break;
        }

        if(!$isCuota)
            return false;

        $isBinario = $this->isAfiliadoenRed($id,$binario);
        log_message('DEV',"IS BINARIO ? $id");

        if($isBinario){
            $this->db->query("UPDATE afiliar SET estatus = 'ACT' 
                                WHERE id_afiliado = $id AND id_red = $binario");
            return true;
        }

        $afiliado = $this->isAfiliadoenRed($id,1);
        $debajo_de = $this->issetVar($afiliado,"debajo_de",2);
        $directo = $this->issetVar($afiliado,"directo",2);
        log_message('DEV',"AFILIADO TO BINARIO -> $debajo_de - $directo");
        $query = "INSERT INTO afiliar
                      SELECT null,$binario,$id,$debajo_de,$directo,a.lado,'ACT'
                         FROM afiliar a WHERE a.id_afiliado = $id AND a.id_red = 1 
                         order by id desc limit 1";

        $this->db->query($query);

        log_message('DEV',"NEW BINARIO :: $id");

        return false;

    }

    private function getAfiliacionbydata($id, $negocio, $id_data,$red = 2)
    {
        $sponsor = $this->setSponsorNegocio($id,$negocio,$red);
        log_message('DEV',"sponsor oro ($id)[$id_data] :: ".json_encode($sponsor));#TEST: -

        if(sizeof($sponsor)>1)
            $negocio = $sponsor[1];

        $sponsor = $sponsor[0];

        $posicion = $this->getPosicionNegocio($sponsor,$red,$id);
        log_message('DEV',"posicion oro ($id)[$id_data] :: ".json_encode($posicion));#TEST:
        $debajo_de = $posicion["debajo_de"];
        $lado = $posicion["lado"];

        $dato = array(
            "debajo_de" => $debajo_de,
            "directo" => $sponsor,
            "red" => $negocio,
            "lado" => $lado
        );
        $where = array('id' => $id_data);

        $this->editar_afiliacion($id,$dato,$where);

    }


    private function isRegistered($id)
    {
        $query = "SELECT * FROM afiliar WHERE id_afiliado = $id AND id_red = 1";

        $this->db->query($query);
        return $query;
    }

    private function setFrontalesDebajo($afiliados, $red, $id,$sponsor = false)
    {
        $red_datos = $this->getTipoRed($red);
        $frontal = $this->issetVar($red_datos, "frontal", 0);
        $dato_ciclos = $this->isLiderenRed($id, $red);
        $ciclos = sizeof($dato_ciclos);

        if($sponsor){
            $dato_ciclos = $this->isLiderenRed($sponsor, $red);
            $ciclos = sizeof($dato_ciclos);
        }

        if ($frontal > 0) {
            $cociente = $ciclos - 1;
            $restante = $cociente * $frontal;
            $frontales = array();
            foreach ($afiliados as $key => $afiliado) {
                $index = $key + 1;
                if ($index > $restante)
                    array_push($frontales, $afiliado);
            }
            $json_1 = json_encode($frontales);
            $json_2 = json_encode($afiliados);
            $log = "--o> frontales debajo ($id)[$red] -> $ciclos : $json_1 : $json_2";
            log_message('DEV', $log);
            $afiliados = $frontales;
        }
        return $afiliados;
    }


    private function setFrontalesDebajoGroup($id,$afiliados, $red, $idx = false)
    {
        $red_datos = $this->getTipoRed($red);
        $frontal = $this->issetVar($red_datos, "frontal", 0);
        $total = sizeof($afiliados);

        $dato_ciclos = $this->isLiderenRed($id, $red);
        $ciclos = sizeof($dato_ciclos);
        $noiniciado = $frontal * ($ciclos-1);

        if($total <= $noiniciado)
            return array();

        if ($frontal > 0) {
            $grupo = array();
            $frontales = array();
            foreach ($afiliados as $key => $afiliado) {
                $index = $key + 1;
                array_push($grupo, $afiliado);
                $islimit = ($index % $frontal == 0);
                $islimit |= ($index == $total);
                if ($islimit){
                    $json = json_encode($grupo);
                    log_message('DEV',"grupo frontal [$index] -> $json");
                    array_push($frontales, $grupo);
                    $grupo = array();
                }
            }
            $json_1 = json_encode($frontales);
            $nfrontales = sizeof($frontales);
            $afiliados = $frontales;
            if(!$afiliados)
                return $afiliados;

            if($idx === true)
                $idx = $nfrontales -1;
            $log = "--|> grupos [$red] ->($total) : $json_1 o-> $idx";
            log_message('DEV', $log);

            if($idx || isset($frontales[$idx]))
                $afiliados =  $frontales[$idx];
        }
        return $afiliados;
    }

    private function estimarCiclos($id, $red, $fechaInicio = false, $fechaFin = false,$totales = false)
    {
        if (!$fechaInicio)
            $fechaInicio = $this->getPeriodoFecha("UNI", "INI", '');
        if (!$fechaFin)
            $fechaFin = $this->getPeriodoFecha("UNI", "FIN", '');

        $where = "select max(id) from afiliar
                      where id_afiliado = a.debajo_de and id_red = $red";
        $where = "AND a.estatus != 'TMP' AND a.id > ($where)";
        $this->getAfiliadosBy($id, 2, "RED", $where, false, $red);
        $afiliados = $this->getAfiliados(true);

        $nAfiliados = 0;
        $red_bono = $red-1;

        $usuario = new $this->afiliado ();
        $compras = "getComprasPersonalesIntervaloDeTiempo";
        $where = "AND i.categoria = 1";
        foreach ($afiliados as $id_afiliado) {
            $valor = $usuario->$compras($id_afiliado, 1, $fechaInicio, $fechaFin, 5, $red_bono, "COSTO", $where);
            if ($valor > 0)
                $nAfiliados++;
        }

        $nAfiliados = sizeof($afiliados);
        $fraccion = $nAfiliados / 4;
        $estimados = (int) ($fraccion);
        $gettype = gettype($fraccion);
        if($gettype !="integer")
            $estimados++;

        $log = "$estimados =>($gettype) $nAfiliados";
        log_message('DEV', "ciclo estimado ($id)[$red] : $log");
        return ($totales) ? array($estimados,$nAfiliados) : $estimados;
    }

    function getNegocioRed($id_red,$where = "")
    {
        $query = "SELECT * FROM red 
                    WHERE tipo_red = $id_red
                        $where
                        ORDER BY id_red DESC";

        $q = $this->db->query($query);
        $q =$q->result();

        if (! $q)
            return false;

        $valid = $q[0];

        return $valid;
    }

    private function isPaid($id_usuario,$id_bono,$red = 1)
    {
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
						AND c.valor > 0";

        $q = $this->db->query($query);
        $q =$q->result();

        if (! $q)
            return false;

        $valid = (sizeof($q) > 0) ? true : false;

        return $valid;
    }

    private function isValidDate($id_usuario,$id_bono,$dia = false)
    {
        $bono = $this->getBono($id_bono);

        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;

        if($mes_inicio<=0)
            return true;

        $select = "DATE_FORMAT(created, '%Y-%m')";
        $select.= " < DATE_FORMAT(DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH),'%Y-%m')";

        if($dia)
            $select = "created < DATE_SUB(NOW(), INTERVAL $mes_inicio MONTH)";

        $query = "SELECT
					    $select 'valid'
					FROM
					    users
					WHERE
					    id = " . $id_usuario;

        $q = $this->db->query($query);
        $q = $q->result();

        if (! $q)
            return false;

        $valid = $this->issetVar($q,"valid",0);
        $valid = ($valid == 1) ? true : false;

        return $valid;
    }

    private function isScheduled($id_usuario,$id_bono,$fecha = "")
    {
        $bono = $this->getBono($id_bono);

        $mes_inicio = $bono[0]->mes_desde_afiliacion;
        $mes_fin = $bono[0]->mes_desde_activacion;
        $where = "";

        $fecha = (strlen($fecha)>2) ? "'".$fecha."'" : "NOW()";

        $isHalfMonth = "(DATE_FORMAT(created,'%d')<16)";
        $halfMonth = "CONCAT(DATE_FORMAT(created,'%Y-%m'),'-15')";
        $limiteInicio = "(CASE WHEN $isHalfMonth THEN $halfMonth ELSE LAST_DAY(created) END)";

        if($mes_inicio>0)
            $where .= " AND DATE_FORMAT($fecha, '%Y-%m-%d') > ".$limiteInicio;

        if($mes_fin>0){
            $mes_fin+=$mes_inicio;
            $where .= " AND DATE_FORMAT($fecha, '%Y-%m-%d') <= ".$limiteInicio;
        }

        $query = "SELECT
					    1 'valid'
					FROM
					    users
					WHERE
					    id = ".$id_usuario.$where;

        $q = $this->db->query($query);
        $q = $q->result();
        if (! $q)
            return false;

        $valid = $this->issetVar($q,"valid",0);
        $valid = ($valid == 1) ? true : false;

        return $valid;
    }

    function getBitcoinStats($date = '21:00',$format = '%H:%i', $group = true)
    {
        $query = "SELECT * FROM bitcoin_stats -- imprimir
                    WHERE amount > 0 
                    and date_format(date_status,'$format') = '$date'";

        if($group)
            $query.=" GROUP BY date_format(date_status,'%Y-%m-%d')";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function getBonosUsuario($id_usuario)
    {
        $redes = $this->getBonoRedes($id_usuario);
        $bonos = $this->getBonos();

        $fecha = date('Y-m-d');
        $parametro = array("id_usuario" => $id_usuario,"fecha" => $fecha);
        $formatoMonto = 'integer';
        $formatoDoble = 'array';

        foreach ($redes as $red){

            $id_red= $red->id_red;
            $parametro["red"] = $id_red;

            foreach ($bonos as $bono){

                $id_bono = $bono->id;
                $valor = 0;
                $extra = "";

                $isActived = $this->isActived($id_usuario,$id_bono,$id_red);

                if($isActived)
                    $valor = $this->getValorBonoBy($id_bono, $parametro);

                $isDoble = gettype($valor) == $formatoDoble;
                $isMonto = gettype($valor) == $formatoMonto;

                if($isDoble){
                    $extra = $valor[1];
                    $valor = $valor[0];
                }else if(!$isMonto){
                    $extra = $valor;
                    $valor = 0;
                }

                $isGanancia = $valor>0||strlen($extra)>2;

                if($isGanancia)
                    $this->repartirBono($id_bono, $id_usuario, $valor,$extra,$fecha,$id_red);

            }/* foreach: $bonos */
        }/* foreach: $redes */
    }

    private function repartirBono($id_bono,$id_usuario,$valor,$extra = "",$fecha="",$red = 1)
    {
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo,"INI", $fecha);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $fecha);

        $historial = $this->getHistorialBono($id_bono,$fechaInicio, $fechaFin,$red);

        if(!$historial)
            $historial= $this->newHistorialBono($id_bono, $fechaInicio, $fechaFin,$red);

        $this->insertBonoUsuario($id_bono, $id_usuario, $valor, $historial,$extra);

        return true;
    }

    private function newHistorialBono($id_bono, $fechaInicio, $fechaFin,$red=1)
    {
        $dia = date('d', strtotime($fechaInicio));
        $mes = date('m', strtotime($fechaInicio));
        $anio = date('Y', strtotime($fechaInicio));

        $query = "INSERT INTO comision_bono_historial (id_bono,dia,mes,ano,fecha)
                    VALUES ($id_bono,$dia,$mes,$anio,'$fechaFin')";

        $this->db->query($query);

        $result = $this->getHistorialBono($id_bono,$fechaInicio, $fechaFin);

        return $result;
    }

    private function getHistorialBono($id_bono, $fechaInicio, $fechaFin,$red=1)
    {
        $query = "SELECT * FROM comision_bono_historial
            		WHERE
                        fecha  between '$fechaInicio' AND '$fechaFin'
                        AND id_bono = $id_bono";

        $q = $this->db->query($query);
        $result = $q->result();

        if (! $result)
            return false;

        $historial = $result[0]->id;

        return $historial;
    }

    private function insertBonoUsuario($id_bono, $id_usuario, $valor, $historial, $extra="")
    {
        $query = "INSERT INTO comision_bono
                     (id_usuario,id_bono,id_bono_historial,valor,extra)
                    VALUES 
                     ($id_usuario,$id_bono,$historial,$valor,'$extra')";

        $this->db->query($query);
    }


    function getValorBonoBy($id_bono,$parametro)
    {
        switch ($id_bono){

            case 2 :
                return $this->getValorBonoPasivo($parametro);
                break;

            case 3 :
                return $this->getValorBonoRangos($parametro);
                break;

            default:
                return 0;
                break;

        }/* switch: $id_bono */
    }

    function getValorBonoRangos($parametro,$pagar = false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $id_bono = 3;
        $valores = $this->getBonoValorNiveles($id_bono);

        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio = $this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin = $this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];

        log_message('DEV', "between: $fechaInicio - $fechaFin");

        $titulo = $this->getRangoAfiliado($id_usuario);

        $date_now = date('Y-m-d', strtotime($parametro["fecha"]));
        $isCobro = true;#TODO: $date_now == $fechaFin;
        if (! $titulo || ! $isCobro):
            log_message('DEV',"\n Not end month ($id_bono) : $date_now - $fechaFin \n");
            return 0;
        endif;

        $valores = $this->getTitulo();
        $monto = $this->getMontoRangos($id_usuario,$fechaInicio,$fechaFin,$titulo,$valores);

        if($pagar&&$monto>0)
            $this->repartirBono($id_bono, $id_usuario, $monto,"",$fechaFin);

        return $monto;
    }

    private function getValorBonoRapido($parametro)
    {
        $valores = $this->getBonoValorNiveles(5);

        $bono = $this->getBono(5);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        log_message('DEV', "between: $fechaInicio - $fechaFin");

        $afiliados = $this->getAfiliadosInicial($valores, $id_usuario, $fechaInicio, $fechaFin);

        $monto = $this->getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin);

        return $monto;
    }

    private function getAfiliadosInicial($valores, $id, $fechaInicio, $fechaFin)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        foreach ($valores as $nivel) {

            if ($nivel->nivel > 0) {

                $this->getDirectosBy($id, $nivel->nivel, $where);
                array_push($afiliados, $this->getAfiliados());

            }/* if: $nivel */
        }/* foreach: $valores */

        return $afiliados;
    }

    private function getMontoDirectos($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0;
        $usuario = new $this->afiliado();
        $calculo = "getComprasPersonalesIntervaloDeTiempo";
        $limit = sizeof($valores);
        for ($i = 0; $i < sizeof($afiliados); $i++) {
            $lvl = $i + 1;
            $valor = isset($valores[$lvl]->valor) ? $valores[$lvl]->valor : $valores[$limit]->valor;

            #@test: 2
            $afiliado = implode(",", $afiliados[$i]);
            $isActivo = $usuario->$calculo($afiliado, $fechaInicio, $fechaFin, 5, "0", "PUNTOS");

            #@test: 3
            log_message('DEV', "A:$afiliado N:$i V:$valor S:$monto");
            if($isActivo >0)
                $monto += $valor;
            #@test: 4
        }/* for: $valores */
        return $monto;
    }
    private function getMontoInicial($valores, $afiliados, $fechaInicio, $fechaFin, $red = 1)
    {
        $monto = 0; $lvl = 0;
        $usuario = new $this->afiliado();
        $calculo = "getComprasPersonalesIntervaloDeTiempo";
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                $per = $valores[$i]->valor / 100;
                #@test: 2
                $afiliado = implode(",", $afiliados[$lvl]);
                $valor = $usuario->$calculo($afiliado,$fechaInicio,$fechaFin,5,"0","COSTO");
                $valor *= $per;
                #@test: 3
                log_message('DEV', "A:$afiliado N:$i P:".($per * 100)."% V:$valor S:$monto");
                $monto += $valor;
                #@test: 4
                $lvl ++;
            }/* if: $corre */
        }/* for: $valores */
        return $monto;
    }

    private function getValorBonoPasivo($parametro){

        $id_bono = 2;
        $valores = $this->getBonoValorNiveles($id_bono);

        $bono = $this->getBono($id_bono);
        $periodo = "DIA";#TODO: isset($bono[1]["frecuencia"]) ? $bono[1]["frecuencia"] : "UNI";

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];

        log_message('DEV',"between ($id_usuario)[$id_bono] : $fechaInicio - $fechaFin ");

        $monto = $this->calcularBonoPasivo($id_usuario, $valores, $fechaInicio, $fechaFin);

        $where = "AND estatus = 'ACT' order by reference ASC";
        $pasivos = $this->getPasivos($id_usuario, $where);

        if(!$pasivos) :
            #TODO: proceso bloquear y 5 dias para desactivar vip
            return $monto;
        endif;

        foreach ($pasivos as $pasivo)
            $this->setRecompraTickets( $id_usuario, $pasivo,$fechaFin);

        return $monto;
    }

    private function getLastDayUTC($fecha = false,$hour = " 21:00:00")
    {
        #TODO: Config UTC range tickets data
        date_default_timezone_set('UTC');
        $date =date('Y-m-d');
        if($fecha)
            $date =date('Y-m-d',strtotime($fecha));
        $datetime = $date . $hour;

        log_message('DEV'," fecha actual: $datetime");

        return $datetime;
    }

    private function setRecompraTickets($id_usuario, $pasivo, $fechaFin = false)
    {
        if(!$fechaFin)
            $fechaFin = $this->getLastDayUTC($fechaFin);

        $timeInit = strtotime($pasivo->initdate);
        $initdate = date('Y-m-d', $timeInit);
        $id_venta = $pasivo->reference;
        $fecha_venta = date_create($initdate);
        $fecha_actual = date_create($fechaFin);
        $interval = date_diff($fecha_venta, $fecha_actual);
        $tiempo = $interval->format('%d');

        $factor = 30;
        $corte = $tiempo % $factor;
        log_message('DEV', "is recompra ($id_venta) ? $initdate - $fechaFin ::> $tiempo day(s)");

        if ($corte == 0):
            $where = "AND v.id_venta = $id_venta";
            $itemsPSR = $this->getPSRuser($id_usuario, $where);
            $id_mercancia = $itemsPSR ? $itemsPSR[0]->id_mercancia : 2;
            $this->setAutoTicket($id_usuario, $id_mercancia);
            $this->deteleTickets($id_usuario, $fechaFin);
        endif;
    }

    private function deteleTickets($id_usuario, $fechaFin)
    {
        $query = "DELETE FROM ticket 
                        WHERE date_final < '$fechaFin' 
                        AND estatus = 'DES' AND user_id = $id_usuario";
        $this->db->query($query);
    }

    private function setAutoTicket($id_afiliado, $id_mercancia)
    {
        $query="SELECT * FROM mercancia m,items i 
                    WHERE 
                    i.id = m.id 
                    AND i.categoria = 2
                    AND i.id = $id_mercancia
                    GROUP BY m.id";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q){
            log_message('DEV',"PSR for autoticket not found :: $id_mercancia");
            return false;
        }

        $item = $q[0]->sku;

        $bono_psr = 2;
        $valores = $this->getBonoValorNiveles($bono_psr);

        if(!$valores){
            log_message('DEV',"PSR bono not found :: $bono_psr");
            return false;
        }

        $limite = sizeof($valores) - 1;
        $valor_tickets = $valores[$limite]->valor ;
        if( isset($valores[$item]) )
            $valor_tickets = $valores[$item]->valor;

        log_message('DEV',"auto tickets for PSR ($item) :: $valor_tickets");

        $tickets = array();
        for($i = 0;$i< $valor_tickets;$i++){
            $ticket = $this->getValueTicketAuto();
            array_push($tickets,$ticket);
        }

        date_default_timezone_set('UTC');
        $date_final = $this->getAnyTime('now', '30 days',true);
        $date_final.=" 21:00:00";

        $this->newTickets($id_afiliado,$tickets,'DES',$date_final);

    }


    private function getPSRuser($id_usuario,$where = "")
    {
        $query = "SELECT * 
              FROM venta v, cross_venta_mercancia c, items i,mercancia m
              WHERE i.id = c.id_mercancia 
              AND m.id  = c.id_mercancia
              AND c.id_venta = v.id_venta
              AND i.categoria = 2 AND v.id_estatus = 'ACT'
              AND v.id_user = $id_usuario $where";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function getPasivos($id_usuario, $where = "",$select = "*")
    {
        $query = "SELECT $select 
              FROM comision_pasivo
              WHERE user_id = $id_usuario
              $where";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function get_total_comisiones_afiliado($id,$where =""){

        $query = "SELECT sum(valor)as valor FROM comision
                      where id_afiliado=$id $where";
        $q=$this->db->query($query);
        $comisiones=$q->result();
        return $comisiones ? $comisiones[0]->valor : 0;
    }

    private function setPasivoUser($id_usuario, $fechaInicio, $fechaFinal, $amount, $id_venta)
    {
        $query = "INSERT INTO comision_pasivo
                            (user_id,initdate,enddate,amount,reference)  
                            VALUES
                            ($id_usuario,'$fechaInicio','$fechaFinal',$amount,$id_venta)";

        $this->db->query($query);
        return $this->db->insert_id();
    }

    private function acumularPasivo($amount, $id_pasivo = 1)
    {
        $query = "UPDATE comision_pasivo
                            set amount = amount+$amount  
                            WHERE id = $id_pasivo";
        $this->db->query($query);
        return $query;
    }

    private function desactivarPasivo($id_pasivo)
    {
        $query = "UPDATE comision_pasivo set
                            estatus = 'DES' 
                            WHERE id = $id_pasivo";
        $this->db->query($query);
    }

    private function calcularBonoPasivo($id_usuario, $valores, $fechaInicio, $fechaFin)
    {
        $per = $valores[0]->valor;
        $percent = $per / 100;
        log_message('DEV',"INCREMENT VALUE :: $percent | $per \n");

        $itemsPSR = $this->getPSRuser($id_usuario);
        $where = "order by reference ASC";
        $pasivos = $this->getPasivos($id_usuario, $where);
        $fechaFinal = $this->getAnyTime($fechaFin, "180 day", true);

        $where = "AND id_red = 2";
        $comisiones = $this->get_total_comisiones_afiliado($id_usuario,$where);

        $monto = 0;

        foreach ($itemsPSR as $index => $psr) {

            $json = json_encode($psr);
            #TODO: log_message('DEV',"VENTA PSR :: $json \n");

            $reference = $psr->id_venta;
            $valor = $psr->costo;
            $tope = $valor * 2;

            $where = "AND reference = $reference AND estatus = 'ACT'";
            $pasivo = $this->getPasivos($id_usuario, $where);

            $amount = $valor * $percent;
            if (!$pasivo):
                $this->setPasivoUser($id_usuario, $fechaInicio, $fechaFinal, $amount, $reference);
                $where = "AND reference = $reference AND estatus = 'ACT'";
                $pasivo = $this->getPasivos($id_usuario, $where);
                log_message('DEV',"NEW PSR PASIVE $reference :: $valor \n");
            endif;

            $acumulado = $pasivo[0]->amount;
            $acumulado+=$comisiones;
            $comisiones = $acumulado - $tope;
            if($acumulado>$tope):
                $acumulado = $tope;
            endif;

            if($comisiones<0)
                $comisiones =0;

            $id_pasivo = $pasivo[0]->id;
            $sumado = $acumulado + $amount;

            if($sumado > $tope)
                $amount = $tope - $acumulado;

            if($acumulado < $tope && $amount>0):
                $this->acumularPasivo($amount, $id_pasivo);
                $monto = $amount;
                log_message('DEV',"SUM $amount in PSR :: $sumado \n");
            endif;

            if ($sumado > $tope):
                $this->desactivarPasivo($id_pasivo);
                log_message('DEV',"DESACTIVAR PSR :: $id_pasivo \n");
                break;
            elseif ($comisiones <= 0) :
                log_message('DEV',"REFERENCE $reference PSR ($id_usuario) :: $id_pasivo \n");
                break;
            endif;

        }

        return $monto;
    }

    private function getAfiliadosValores($valores, $id, $red_id = 1)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        foreach ($valores as $nivel) {

            if ($nivel->nivel > 0) {

                $this->getAfiliadosBy($id, $nivel->nivel,"RED" ,$where,$id,$red_id);
                array_push($afiliados, $this->getAfiliados());

            }/* if: $nivel */
        }/* foreach: $valores */

        return $afiliados;
    }

    private function getAfiliadosDirectos($valores, $id, $red_id = 1)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        if(!$valores)
            return array();

        $limite = $valores[0]->valor;

        for($key = 0;$key<$limite; $key++) {

            $nivel = $key + 1;

            $this->getDirectosBy($id, $nivel, $where,$red_id);
            array_push($afiliados, $this->getAfiliados());


        }/* foreach: $valores */
        log_message('DEV',"afiliados ".json_encode($afiliados));
        return $afiliados;
    }

    private function getAfiliadosMatriz($valores, $id, $red_id = 1)
    {
        $where = ""; #@test: 1

        $afiliados = array();

        if(!$valores)
            return array();

        $limite = $valores[0]->valor;

        for($key = 0;$key<$limite; $key++) {

            $nivel = $key + 1;

            $this->getAfiliadosBy($id, $nivel, "RED", $where,$id,$red_id);
            array_push($afiliados, $this->getAfiliados());


        }/* foreach: $valores */
        log_message('DEV',"afiliados ".json_encode($afiliados));
        return $afiliados;
    }

    function getGananciaBinario($id_usuario,$afiliados,$valores,$fechaInicio, $fechaFin) {

        $datos = $this->ComprobarBrazos($afiliados);

        if(!$datos)
            return 0;

        list($afiliados,$brazos) = $datos;

        log_message('DEV',"NIVEL 1 : ".json_encode($brazos));
        list($puntos, $ventas) = $this->setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos);

        if(!$afiliados)
            return $puntos;

        $uplines =$brazos;

        foreach ($afiliados as $n => $nivel){
            $idx = $n+1;
            log_message('DEV',"NIVEL $idx : ".json_encode($nivel));
            foreach ($nivel as $key => $afiliado){

                $this->setPuntosDerrame( $afiliado, $fechaInicio,$fechaFin, $uplines, $puntos, $ventas);

                log_message('DEV',"lados [$key] : ".json_encode($uplines));
            }
            log_message('DEV', "VENTAS :::>>> ".json_encode($ventas)."PUNTOS :::>>> ".json_encode($puntos));
        }
        log_message('DEV',"ventas  : ".json_encode($ventas));

        $conteo = $puntos;

        $puntos = $this->setPuntosTotales($conteo);
        $puntos = $this->setBrazoMenor($puntos);

        if(!$puntos)
            return false;

        list($puntos,$debil) = $puntos;

        $cumple= $this->getAfiliadosBinario($id_usuario,$fechaFin);

        if($cumple){
            list ($afiliados,$binario) = $cumple;
            $cumple = sizeof($binario) >= 2;
        }
        $remanente = $this->setDatosArrayUnset($ventas, $debil);
        $sobrante= $this->setDatosArrayUnset($conteo, $debil);

        if($cumple){
            $remanente = $this->setRemanentesBinario( $remanente, $sobrante, $puntos);

        } else {
            $remanente = $this->setRemanentesBinario( $remanente, $sobrante);
        }

        $remanente = json_encode($remanente);
        $this->updateRemanenteDebil($id_usuario, $debil, $remanente);

        $ganados = $ventas[$debil];

        $ganados = explode(",", $ganados);
        $pagadas = explode(",", $conteo[$debil]);

        $reporte = $this->setReporteBinario($ganados, $pagadas);
        $reporte =  json_encode($reporte);
        $this->updateRemanente($id_usuario, $debil, $reporte);
        if($ganados == 0)
            return 0;

        if(!$cumple){
            log_message('DEV',">>> NO CUMPLE CONDICION ($id_usuario)");
            return 0;
        }

        $this->updateRemanente($id_usuario, $debil);

        $per = $valores[1]->valor / 100;
        $ganancia = $puntos*$per;

        $regresion = json_encode($this->remanente);
        $extra = "$reporte|$regresion";

        log_message('DEV',">>> BINARIO -> $puntos * $per V:$extra R:$remanente");

        return array($ganancia,$extra);
    }

    private function getAfiliadosBinario($id_usuario,$fecha = false,$fechaInicio = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $this->getAfiliadosBy($id_usuario, 1, "RED", "order by lado",$id_usuario,2);
        $afiliados = $this->getAfiliados();

        $this->getDirectosBy($id_usuario, 1,  "",2);
        $directos = $this->getAfiliados();

        if(!$directos)
            return false;

        if(!$fechaInicio)
            $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');

        $lados = array();
        foreach ($afiliados as $key =>$id_user){

            $venta = $this->getVentaMercancia($id_user, $fechaInicio, $fecha, 5);

            $Directo = $this->getAfiliacion($id_user);
            if($Directo)
                $Directo = ($Directo[0]->directo == $id_usuario) ? $id_user : false;

            $json = json_encode($venta);
            log_message('DEV',"Directo ($id_user) -->>>> $json | [[ $Directo ]]");

            if(!$venta || !$Directo)
                $Directo = $this->isDirectoLado($id_usuario,$afiliados, $id_user,$fecha);

            log_message('DEV',"condicion ($id_user)[$key] :: [[ $Directo ]]");

            if(!$Directo)
                return false;

            $lados[$key] = $Directo;

        }

        return array($afiliados,$lados);
    }

    private function getAfiliacion($id, $red = 1)
    {
        $q = $this->db->query("SELECT * FROM afiliar WHERE id_afiliado = $id and id_red = $red");
        $q = $q->result();

        return $q;
    }

    private function setBrazoMenor($puntos)
    {
        $sumPuntos = array_sum($puntos);

        if($sumPuntos == 0)
            return false;

        $menor = 0;$debil=false;$aplica = false;
        foreach ($puntos as $key => $punto) {
            log_message('DEV',">>> PUNTO [$key] -> [[ $punto ]]");
            if (!$aplica || $punto < $menor){
                $debil = $key;
                $menor = $punto;
            }
            $aplica=true;
        }

        $json = json_encode($puntos);
        log_message('DEV',">>> PUNTOS : $json -> $menor");
        return array($menor,$debil);
    }

    private function isDirectoLado($id_usuario , $afiliados, $directo,$fecha = false)
    {
        if (! $fecha)
            $fecha = date ( "Y-m-d" );

        $fechaInicio=$this->getPeriodoFecha("UNI", "INI", '');
        $fechaFin=$fecha;

        $datoid = $this->getAfiliacion($directo,1);
        $lado = $datoid[0]->lado;

        $mired = $afiliados;
        $directos = false;
        $isdirecto = false;
        $isDirectoCompra = false;
        while(!$isdirecto){

            $islado = false;
            foreach ($mired as $uid){
                $datoid = $this->getAfiliacion($uid,1);
                $milado = $datoid[0]->lado;
                $islado = ($milado==$lado);
                if($islado){
                    $this->getAfiliadosBy($uid, 1, "RED", "",$id_usuario,1);
                    $mired = $this->getAfiliados();
                    $this->getAfiliadosBy($uid, 1, "DIRECTOS", "",$id_usuario,1);
                    $directos = $this->getAfiliados();

                    $isDirectoCompra = false;
                    foreach ($directos as $id_user){
                        $venta = $this->getVentaMercancia($id_user, $fechaInicio, $fechaFin, 5);

                        if ($venta)
                            $isDirectoCompra = $id_user;
                    }

                    break;
                }
            }

            if ($isDirectoCompra)
                $isdirecto = true;
            else if (! $islado)
                $isdirecto = true;
        }

        return $isDirectoCompra;
    }

    private function setPuntosFrontales($id_usuario,$fechaInicio, $fechaFin, $brazos)
    {
        list($puntos, $ventas) = $this->setValoresRemanente($id_usuario);

        foreach ($brazos as $key => $brazo) {
            $venta = $this->getVentaMercancia($brazo, $fechaInicio, $fechaFin, 5, false);

            if (!$venta)
                continue;

            $valor = $this->issetVar($venta,"puntos_comisionables",0);
            $id_venta = $this->issetVar($venta,"id_venta",1);
            log_message('DEV', "Frontales : A1:$brazo N:1 V:$valor K:$key");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta);
        }

        return array($puntos, $ventas);
    }

    private function isUpline($id,$id_debajo = 2, $red = 1)
    {
        $query = "select * from afiliar -- imprimir
                    where debajo_de in ($id_debajo)
                      and id_afiliado = $id
                      and id_red = $red ";
        $query = $this->db->query($query);

        $lados = $query->result();
        return $lados;
    }

    private function setPuntosDerrame($afiliado,$fechaInicio,$fechaFin, &$uplines, &$puntos, &$ventas)
    {
        foreach ($uplines as $key => $upline) {
            $isUpline = $this->isUpline($afiliado, $upline);
            if (!$isUpline)
                continue;

            $uplines[$key] .= ",$afiliado";

            $venta = $this->getVentaMercancia($afiliado,$fechaInicio,$fechaFin,5,false);

            if(!$venta)
                continue;

            $valor = $venta[0]->puntos_comisionables;
            $id_venta = $venta[0]->id_venta;

            log_message('DEV', ">>>! ADD lado[$key] ->> a:$afiliado I:$id_venta V:$valor");

            $puntos = $this->setValueSeparated($puntos, $key, $valor);
            $ventas = $this->setValueSeparated($ventas, $key, $id_venta,true);

        }
    }


    private function setRemanente($id,$remanente,$bono = 2){

        $exist = $this->getRemanente($id, $bono);

        if($exist){
            $this->db->where('id_usuario', $id);
            $this->db->where('id_bono', $bono);
            $this->db->update('comisionPuntosRemanentes',$remanente);
        }else{
            $remanente['id_usuario'] = $id;
            $remanente['id_bono'] = $bono;
            $this->db->insert('comisionPuntosRemanentes',$remanente);
        }

    }

    private function getBonoRemanente($id,$bono = 2) {

        $q = $this->getRemanente ($id,$bono);

        if (!$q)
            return array (0,0);

        $remanente = array (
            $q[0]->izquierda,
            $q[0]->derecha
        );

        return $remanente;
    }

    private function getRemanente($id,$bono) {
        $q = $this->db->query ( "SELECT * FROM comisionPuntosRemanentes WHERE id_bono = $bono and id_usuario = $id" );
        $q = $q->result ();
        return $q;
    }

    private function getPuntosRemanente($id_usuario)
    {
        $remanentes = $this->getBonoRemanente($id_usuario);
        $puntos = array(0, 0);
        foreach ($remanentes as $key => $pata) {
            $datos = json_decode($pata);
            $isObject = gettype($datos) == "object";
            $isArray = gettype($datos) == "array";
            $isEmpty = sizeof($datos) < 1 || $datos === 0;
            log_message('DEV', "pata $key :: $pata ");

            if ($isObject && $isArray)
                continue;
            else if($isEmpty )
                continue;

            foreach ($datos as $id_venta => $valor)
                $puntos [$key] += $valor;

        }

        $json_1 = json_encode($puntos);

        log_message('DEV', "Puntos remanente : $json_1");
        return $puntos;
    }


    private function setValoresRemanente($id_usuario)
    {
        $remanentes = $this->getBonoRemanente($id_usuario);
        $this->remanente = $remanentes;
        $puntos = array(0, 0);
        $ventas = array(0, 0);
        foreach ($remanentes as $key => $pata) {
            $datos = json_decode($pata);
            $isObject = gettype($datos) == "object";
            $isArray = gettype($datos) == "array";
            $isEmpty = sizeof($datos) < 1 || $datos === 0;
            log_message('DEV', "pata $key :: $pata ");

            if ($isObject && $isArray)
                continue;
            else if($isEmpty )
                continue;

            foreach ($datos as $id_venta => $valor) {
                $puntos = $this->setValueSeparated($puntos, $key, $valor);
                $ventas = $this->setValueSeparated($ventas, $key, $id_venta);
            }
        }
        $json_1 = json_encode($puntos);
        $json_2 = json_encode($ventas);
        log_message('DEV', "remanente : P:$json_1 V:$json_2");
        return array($puntos, $ventas);
    }

    private function setValueSeparated($datos, $key, $value,$split= false)
    {
        if($split){
            $list = explode(",",$datos[$key]."");
            foreach ($list as $data)
                if($data == $value)
                    return $datos;
        }

        if ($datos[$key] == 0)
            $datos[$key] = $value;
        else
            $datos[$key] .= ",$value";
        return $datos;
    }

    private function setPuntosTotales($datos)
    {
        $puntos = array(0, 0);
        foreach ($datos as $key => $dato) {
            $values = explode(",", $dato);
            $puntos[$key] = array_sum($values);
        }
        return $puntos;
    }

    private function setDatosArrayUnset($datos, $unset = 0)
    {
        $newDatos = $datos;
        unset($newDatos[$unset]);
        $newDatos = explode(",", implode(",", $newDatos));
        return $newDatos;
    }

    private function setDatosUnset($datos, $unset = 0)
    {
        $newDatos = $datos;
        unset($newDatos[$unset]);

        $newDatos = implode(",", $newDatos);
        return $newDatos;
    }

    private function setReporteBinario($ganados, $pagadas)
    {
        $reporte = array();
        foreach ($ganados as $key => $id_venta) {
            $valor = $pagadas[$key];
            $reporte[$id_venta] = $valor;
        }
        return $reporte;
    }

    private function setRemanentesBinario($remanente, $conteo,$puntos = 0)
    {
        $monto = 0;
        $sobrantes = array();
        foreach ($remanente as $key => $id_venta) {

            $valor = $conteo[$key];

            $suma = $monto + $valor;

            if ($suma > $puntos ){

                if($monto < $puntos)
                    $valor = $suma-$puntos;

                $sobrantes[$id_venta] = $valor;
            }

            $monto = $suma;
        }
        return $sobrantes;
    }
    private function updateRemanenteDebil($id_usuario, $debil, $remanente = 0)
    {
        $contrario = ($debil == 0) ? 1 : 0;
        $this->updateRemanente($id_usuario,$contrario,$remanente);
    }

    private function updateRemanente($id_usuario, $lado, $remanente = 0)
    {
        $lados = array();

        $ladomayor = ($lado == 0) ? "izquierda" : "derecha";

        $lados[$ladomayor] = $remanente;

        $this->setRemanente($id_usuario, $lados, 2);
    }

    function ComprobarBrazos($afiliados){
        if(!isset($afiliados[0]))
            return false;

        $brazos =  $afiliados[0];

        $nBrazos = sizeof($brazos);
        if($nBrazos <2)
            return false;

        unset($afiliados[0]);

        log_message('DEV',"brazos $nBrazos : ".json_encode($brazos));
        return array($afiliados,$brazos);
    }

    private function getValorBonoDirectos($parametro,$pagar=false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(4);

        $bono = $this->getBono(4);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        $afiliados = $this->getAfiliadosDirectos($valores,$id_usuario,2);

        if(!$afiliados)
            return 0;

        $Ganancia = $this->getMontoDirectos($valores,$afiliados,$fechaInicio,$fechaFin);

        if($pagar&&$Ganancia>0)
            $this->repartirBono(3, $id_usuario, $Ganancia,"",$fechaFin);

        return $Ganancia;
    }
    private function getValorBonoGanancias($parametro,$pagar=false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(3);

        $bono = $this->getBono(3);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        $afiliados = $this->getAfiliadosValores($valores,$id_usuario,2);

        if(!$afiliados)
            return 0;

        $Ganancia = $this->getGananciaAvance($afiliados,$valores,$id_red,$fechaInicio,$fechaFin);

        if($pagar&&$Ganancia>0)
            $this->repartirBono(3, $id_usuario, $Ganancia,"",$fechaFin);

        return $Ganancia;
    }

    private function getValorBonoUpline($parametro,$pagar=false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(2);

        $bono = $this->getBono(1);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        $Ganancia = $this->getGananciaAvance($id_usuario,$valores,$id_red,$fechaInicio,$fechaFin);
        $extra = "Cierre de Ciclo del Directo ID:$id_usuario [$id_red]";
        $upline = $this->getUpline($id_usuario,$id_red);

        if($pagar&&$Ganancia>0)
            $this->repartirBono(2, $upline, $Ganancia,$extra,$fechaFin);

        return $Ganancia;
    }

    function getUpline($id_usuario,$id_red)
    {
        $order = "red DESC";
        $afiliacion = $this->isAfiliadoenRed($id_usuario,1, $order);
        $red = $this->issetVar($afiliacion,"red",1);
        $directo = $this->issetVar($afiliacion, "directo", 2);

        $afiliacion = $this->isLiderenRed($directo,$id_red);
        log_message('DEV',"UPLINE ($id_usuario)[$id_red] ->>o $directo");

        #TODO: if($afiliacion)
        return $directo;

        $isLider = $this->getCicloRed($red);
        $lider = $this->issetVar($isLider, "lider", 2);
        if($lider == $id_usuario)
            $lider = $directo;
        return $lider;
    }


    private function getGananciaAvance($afiliados,$valores,$id_red,$fechaInicio,$fechaFin)
    {
        $monto = 0; $lvl = 0;
        $usuario = new $this->afiliado();
        $calculo = "getComprasPersonalesIntervaloDeTiempo";
        for ($i = 0; $i < sizeof($valores); $i ++) {
            $Corre = ($i > 0) && isset($afiliados[$lvl]);
            if ($Corre) {
                $per = $valores[$i]->valor / 100;
                #@test: 2
                $afiliado = implode(",", $afiliados[$lvl]);
                $valor = $this->getMontoBono($afiliados,2,$fechaInicio,$fechaFin);
                $valor *= $per;
                #@test: 3
                log_message('DEV', "A:$afiliado N:$i P:".($per * 100)."% V:$valor S:$monto");
                $monto += $valor;
                #@test: 4
                $lvl ++;
            }/* if: $corre */
        }/* for: $valores */
        return $monto;
    }


    function getValorBonoCumplimiento($parametro,$pagar = false)
    {
        if(!isset($parametro["fecha"]))
            $parametro["fecha"] = date('Y-m-d');

        $valores = $this->getBonoValorNiveles(3);

        $bono = $this->getBono(1);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        $fechaInicio=$this->getPeriodoFecha($periodo, "INI", $parametro["fecha"]);
        $fechaFin=$this->getPeriodoFecha($periodo, "FIN", $parametro["fecha"]);

        $id_usuario = $parametro["id_usuario"];
        $id_red = isset($parametro["red"]) ?  $parametro["red"] : 1;

        $calculo = "getGananciaCumplimiento";
        $Ganancia = $this->$calculo($id_usuario,$valores,$id_red,$fechaInicio,$fechaFin);

        $isGanancia = $Ganancia > 0;
        if(!$isGanancia)
            return 0;

        if(gettype($Ganancia) == "array")
            list($Ganancia,$cumple) = $Ganancia;

        if(!isset($cumple))
            $cumple = false;

        $nombreRed = $this->getNombreRed($id_red);
        $extra = $extra = "Cierre de Ciclo de $nombreRed";
        if($pagar&& $isGanancia)
            $this->repartirBono(3, $id_usuario, $Ganancia,$extra,$fechaFin);

        return $pagar ? $cumple : $Ganancia;
    }

    private function getGananciaCumplimiento($id_usuario,$valores,$id_red,$fechaInicio=false,$fechaFin=false)
    {
        if($id_usuario == 1)
            return 0;

        log_message('DEV',"EVALUAR CICLO ($id_usuario)[$id_red]");
        $inicioFecha = $this->getInicioFecha($id_usuario);
        $red_bono = $id_red-1;
        $monto = $valores[$red_bono]->valor;

        if (!$fechaFin)
            $fechaFin = date('Y-m-d');

        list($estimados,$afiliados) = $this->estimarCiclosCompletos($id_usuario, $id_red,  $fechaInicio, $fechaFin);

        $order = "id_red DESC";
        $Afiliado = $this->isLiderenRed ( $id_usuario, $id_red , $order);
        $ciclos = sizeof($Afiliado);
        $aplica = 4;#TODO: *$ciclos;
        $noAplica = ($afiliados < $aplica);
        #TODO: $noAplica |= $estimados < $ciclos;

        log_message('DEV',"ciclo actual ($id_usuario)[$id_red] : $ciclos => $afiliados de $aplica");

        if($noAplica)
            return 0;

        $cumple = $this->procesoCiclo($id_usuario,$id_red);

        $inscripcion = $this->getVentaMercancia($id_usuario, $inicioFecha, $fechaFin,5,$red_bono);
        $data = sizeof($inscripcion);
        if (! $cumple && $inscripcion) {
            $monto = $this->issetVar($inscripcion,"costo",0);
            $this->setRedCumplida($id_usuario, $id_red);
            $this->nueva_red($id_usuario,$id_red);
            $this->insertNewRed($id_usuario,$id_red);
        }

        log_message('DEV',"Cumplimiento ($id_usuario)[$id_red] :: M:$monto C:[$cumple] I:$data");
        $Ganancia = $monto;

        return array($Ganancia,$cumple);
    }



    private function getRangoAfiliado($id_usuario)
    {
        $query = "SELECT * FROM cross_rango_user
					WHERE
					    id_user = $id_usuario
					    AND estatus = 'ACT'";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q ? $q[0] : false;
    }

    private function getTitulo($param = "", $where = "")
    {
        if ($where)
            $where = " WHERE " . $where;

        $query = "SELECT * FROM cat_titulo
					$where
					ORDER BY orden ASC";

        $q = $this->db->query($query);
        $result = $q->result();

        if (! $result)
            return false;

        if ($param && isset($result[0]->$param))
            $result = $result[0]->$param;
        else if ($param === 0)
            $result = $result[0];

        return $result;
    }

    function getPuntosBinario($id_usuario, $fecha = false)
    {
        if (!$fecha)
            $fecha = date('Y-m-d');

        $usuario = new $this->afiliado;

        $def = "0";
        $fechaInicio = $this->getSemanaFecha( "INI", $fecha);
        $fechaFin = $this->getSemanaFecha( "FIN", $fecha);

        #$redes = $this->getRedes();

        $puntos = $this->getPuntosRemanente($id_usuario,2);
        $id_red = 1;
        $profundidad = 0;

        $this->getAfiliadosBy($id_usuario, 1, "RED", "order by lado",$id_usuario,2);
        $brazos = $this->getAfiliados();
        $isbrazos = sizeof($brazos)>=2;

        if(!$isbrazos){
            $this->getAfiliadosBy($id_usuario, 1, "RED", "order by lado",$id_usuario,1);
            $brazos = $this->getAfiliados();
            $isbrazos = sizeof($brazos)>=2;
        }

        log_message('DEV',"PUNTOS BINARIO ($fechaInicio - $fechaFin) [[ $isbrazos ]]:: $id_usuario");
        if(!$isbrazos)
            return $puntos;

        $personal = "getPuntosTotalesPersonalesIntervalosDeTiempo";
        $enlared="getVentasTodaLaRed";
        foreach ($brazos as $key => $brazo) {
            $puntos_personal= $usuario->$personal($brazo, $id_red, $def, $def, $fechaInicio, $fechaFin);
            $puntos_red= $usuario->$enlared($brazo, $id_red, "RED", "EQU", $profundidad, $fechaInicio, $fechaFin, $def, $def, "PUNTOS");

            $puntos[$key] =  $puntos_personal + $puntos_red;

            log_message('DEV',"[$key]($brazo) -> $puntos_personal + $puntos_red = ".$puntos[$key]);
        }

        return $puntos;
    }

    function getPuntos($id,$where = ""){
        $redes = $this->getRedUsuarioby($id,$where);
        $acumulado = 0;
        foreach ($redes as $key => $red_dato){
            $acumulado+=$red_dato->puntos;
        }
        return $acumulado;
    }

    function estimarRango($id_usuario,$fechaInicio = false,$fechaFin = false)
    {
        $id_bono = 3;
        $bono = $this->getBono($id_bono);
        $periodo = $this->issetVar($bono,"frecuencia","UNI");

        if(!$fechaInicio)
            $fechaInicio = $this->getPeriodoFecha($periodo, "INI", '');

        if(!$fechaFin)
            $fechaFin = $this->getPeriodoFecha($periodo, "FIN", '');

        $valores = $this->getTitulo();
        $rango = $this->getRangoAfiliado($id_usuario);

        $id_rango = 0;
        if($rango)
            $id_rango = $rango->id_rango;

        $vip = 2;
        $red_todo = $this->getRedTodo($id_usuario, $vip);

        $servicio = 2;
        $items = false;
        $where = "AND i.categoria = 2";
        $total = $this->getMontoMercanciaRed($red_todo, $fechaInicio, $fechaFin, $servicio, $items, $where);

        $cumple = $this->isCondicionesRango($id_rango, $total,true);

        if(!$cumple):
            log_message('DEV'," no cumple rango $id_usuario [$id_rango]");
            return 0;
        endif;

        $id_rango = $cumple;

        log_message('DEV'," new rango $id_usuario [$id_rango]: ");

        $this->update_rango($id_usuario,$id_rango);

        return $id_rango;
    }

    private function getMontoRangos($id_usuario,$fechaInicio,$fechaFin,$rango,$valores)
    {
        $monto = 0;
        $id_rango = 0;
        if($rango)
            $id_rango = $rango->id_rango;

        $vip = 2;
        $red_todo = $this->getRedTodo($id_usuario, $vip);

        $servicio = 2;
        $items = false;
        $where = "AND i.categoria = 2";
        $total = $this->getMontoMercanciaRed($red_todo, $fechaInicio, $fechaFin, $servicio, $items, $where);

        $cumple = $this->isCondicionesRango($id_rango, $total);

        if(!$cumple):
            log_message('DEV'," no cumple rango $id_usuario [$id_rango]");
            return 0;
        endif;

        $id_rango = $cumple;

        $limit = sizeof($valores)-1;
        $bono_rango= $valores[$limit]->porcentaje;
        if(isset($valores[$id_rango]) )
            $bono_rango = $valores[$id_rango]->porcentaje;

        $reporte_tickets = $this->getTodoTickets($fechaInicio,$fechaFin);
        $json = json_encode($reporte_tickets);
        $acumulado = 0;
        foreach ($reporte_tickets as $ticket):
            $acumulado+=$ticket->rankings;
        endforeach;
        log_message('DEV'," Reporte tickets ($fechaInicio,$fechaFin) \n ::>> $acumulado \n\n $json");

        $per = $bono_rango/100;

        if ($cumple)
            $monto = $acumulado*$per;

        log_message('DEV'," new rango $id_usuario [$id_rango]: ($acumulado * $per) = $ $monto");

        $this->entregar_rango($id_usuario,$id_rango);

        return $monto;
    }

    function getTodoTickets($fecha_inicio = false, $fecha_fin = false){

        $referidos = 20;
        $neto = "(m.costo/2)"; #TODO: "t.bonus"; 25;
        $company = 30;
        $rankings = 40;
        $company2 = 10;

        $where = "";

        if($fecha_inicio)
            $where .= "AND t.date_final >= '$fecha_inicio'";

        if($fecha_fin)
            $where .= "AND t.date_final <= '$fecha_fin'";

        $query = "SELECT 
                      count(*) tickets,
                      t.date_final ,
                      sum(m.costo) total,
                      sum((case when (t.bonus = 50) then (m.costo*t.bonus/100) else 0 end)) neto,
                      sum((case when (t.bonus = 25) then (m.costo*t.bonus/100) else 0 end)) acumulado,
                       sum((m.costo*$referidos/100)) referrals,
                       sum((m.costo*$company/100)) company,
                       sum($neto-(m.costo*t.bonus/100)) residuo,
                       sum(($neto-(m.costo*t.bonus/100))*($rankings*2/100)) rankings,
                       sum((case when (t.bonus = 25) then ($neto*$company2/100) else 0 end)) company2
                    from 
                        ticket t,mercancia m,
                        cross_venta_mercancia c,
                        venta v,items i
                        -- ,comision_bono cb,comision_bono_historial h
                    where
                        m.id = c.id_mercancia
                        and c.id_venta = v.id_venta
                        and v.id_venta = t.reference
                        and i.categoria = 4
                      --  and cb.id_bono = 1
                      --  and cb.id_bono_historial = h.id
                      --  and h.fecha = date_format(t.date_final,'%Y-%m-%d')
                        $where
                        group by t.date_final
                        order by t.date_final asc";

        $q = $this->db->query($query);

        return $q->result() ;

    }
    private function entregar_rango($id_usuario,$rango = 0)
    {
        $query = "UPDATE cross_rango_user 
                    SET id_rango = $rango, entregado = 1 
                    WHERE id_user = $id_usuario";
        $this->db->query($query);
    }

    private function update_rango($id_usuario,$rango = 0)
    {
        $query = "UPDATE cross_rango_user 
                    SET id_rango = $rango, entregado = 0 
                    WHERE id_user = $id_usuario";
        $this->db->query($query);
    }

    private function condicionCiclo($id_usuario,$id_red)
    {
        $lastrow = "select max(id) from afiliar 
                        where id_red = $id_red and id_afiliado = $id_usuario";
        $where = "AND a.id > ($lastrow) AND a.estatus != 'TMP'";
        $this->getDirectosBy($id_usuario, 1, $where,$id_red,0);
        $afiliados = $this->getAfiliados();
        $json = json_encode($afiliados);
        log_message('DEV',"condicion ciclo ($id_usuario)[$id_red] :: $json");
        if(sizeof($afiliados)<1)
            return false;

        $where = "AND estatus != 'ACT'";
        $order = "id_red DESC";
        $Afiliado = $this->isLiderenRed ( $id_usuario, $id_red , $order, $where);
        $actual = sizeof($Afiliado);

        $redes_actual = sizeof($Afiliado);
        $DatosRed = $this->getNegocioRed($id_red,"AND lider = $id_usuario");
        $CicloRed = $DatosRed ? $DatosRed->ciclo : 0;

        $redavanza = $id_red + 1;
        $Afiliado = $this->isLiderenRed ( $id_usuario, $redavanza, $order);
        $avanzado = sizeof($Afiliado)>0;
        $avanzaRed = ($CicloRed==0) || ($CicloRed < $actual) || (!$avanzado);

        log_message('DEV',"Validacion nuevo ciclo ($id_usuario)[$id_red] : ->[$avanzaRed]");
        return $avanzaRed;
    }

    private function setCiclo($id,$red)
    {
        $ciclo = $this->getCicloRedUsuario($id,$red);
        $ciclo++;

        $dato = array(
            "ciclo" => $ciclo
        );

        $this->db->where('id_afiliado', $id);
        $this->db->where('id_red', $red);
        $this->db->update('afiliar', $dato);

        return true;
    }

    public function isEscalamiento($id_usuario,$id_red)
    {
        $veces = array( 2=>1, 4=>2, 5=>2, 6=>-1 );
        $veces = $veces[$id_red];

        $ciclo = $this->getCicloRedUsuario($id_usuario,$id_red);

        if($ciclo>=$veces)
            return true;

        return false;
    }

    function getCicloRedUsuario($id,$red = false,$tipo = false)
    {
        $query = "SELECT * FROM afiliar 
                    WHERE id_afiliado = $id";
        if($red)
            $query.=" AND red = $red";
        else if ($tipo)
            $query.=" AND id_red = $tipo";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function getRedUsuarioby($id,$where = "")
    {
        $query = "SELECT * FROM red WHERE lider = $id $where";
        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function getContadorCiclo($id,$tipo,$red=false,$where = "")
    {
        $query = "SELECT * FROM red 
                    WHERE lider = $id
                        AND tipo_red = $tipo
                        $where";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return 0;

        if(!$red){
            $dato = $this->isLiderenRed($id,$tipo,"id_red DESC");
            $red = $this->issetVar($dato,"id_red",1);
        }

        foreach ($q as $key => $value) {
            if($value->id_red == $red)
                return $key+1;
        }

        return 1;
    }

    function getCicloRed($id)
    {
        $query = "SELECT * FROM red WHERE id_red = $id";
        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function procesoCiclo($id_usuario,$id_red)
    {
        $cumple = $this->condicionCiclo($id_usuario,$id_red);

        $this->ComisionUpline($id_usuario,$id_red);
        if($cumple){
            $this->entregar_puntos($id_usuario,$id_red);
            $id_red = $this->renovarCompraCiclo($id_usuario, $id_red);
            $this->calcularComisionDirecta($id_usuario, $id_red);

            return $cumple;
        }

        $this->setRedCumplida($id_usuario, $id_red);
    }

    function entregar_puntos($id_usuario, $id_red)
    {
        if($id_red != 3) return false;

        $valores = $this->getBonoValorNiveles(4);
        $puntos = $this->issetVar($valores,"valor",0);

        $red = $this->isLiderenRed($id_usuario,$id_red,"id_red DESC");
        if(!$red||$puntos==0) return false;

        $red = $this->issetVar($red,"id_red",1);
        $dato = array("puntos"=>$puntos,"id_red"=>$red);
        $where = array("lider"=>$id_usuario);
        log_message('DEV',"puntos ($id_usuario)[$id_red:$red] : $puntos");
        $this->updateDatoRed($id_usuario,$dato);
    }

    private function updateDatoRed($id, $datos,$where = false)
    {
        $datos["lider"] = $id;

        if ($where)
            $datos["where"] = $where;

        return $this->myDataset("red", $datos, "id_red");
    }

    private function editar_afiliacion($id, $datos,$where = false)
    {
        $datos["id_afiliado"] = $id;

        if($where)
            $datos["where"] = $where;

        return $this->myDataset( "afiliar",$datos,"id_afiliado");
    }

    private function nueva_afiliacion($id, $datos)
    {
        $datos["id_afiliado"] = $id;

        return $this->myDataset( "afiliar",$datos,"id_afiliado",true);
    }

    private function myDataset( $table, $datos, $idtable = "id",$new=false)
    {
        if (! $datos)
            return false;

        $type_query = ($new) ? "insert" : "update";
        $isWhere = isset($datos["where"]);

        if (! $new) {

            $this->db->where($idtable, $datos[$idtable]);

            if ($isWhere) {
                foreach ($datos["where"] as $key => $value) {
                    $this->db->where($key, $value);
                }
                unset($datos["where"]);
            }/* if: $isWhere */
        }/* if: $new */

        try {
            return $this->db->$type_query($table, $datos);
        } catch (Exception $e) {
            log_message('ERROR', "$type_query :: " . json_encode($datos));
            return false;
        }/* try */
    }

    private function nueva_red($id,$red = 1,$estatus = "ACT")
    {
        $ciclo = ($red == 2) ? 1 : 0;

        $dato_red = array(
            'tipo_red' => $red,
            "lider" => $id,
            "ciclo" => $ciclo,
            "estatus" => $estatus
        );

        $this->db->insert("red", $dato_red);

        return $this->db->insert_id();
    }

    private function renovarCompraCiclo($id,$red)
    {   log_message('DEV',"Renovar ($id)[$red]");
        $red = $this->procesoNuevoNegocio($id, $red,true);

        $red_item = $red-1;

        $id_venta = $this->insertVenta($id);
        $monto = $this->insertVentaItem($id, $id_venta,$red_item);
        #@test: 5

        return $red;
    }

    private function descontarBilleteraCiclo($id, $id_venta, $monto)
    {
        date_default_timezone_set('UTC');
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "id_user" => $id,
            "tipo" => "SUB",
            "Descripcion" => "Descuento Automático - VENTA # $id_venta",
            "fecha" => $fecha,
            "monto" => $monto
        );

        $this->db->insert('transaccion_billetera', $dato);

        return true;
    }

    function limpiarAfiliaciones($where = ""){
        $limpiar = "(debajo_de = 0 OR red = null) AND id_red > 1";
        $query = "DELETE FROM afiliar WHERE $limpiar $where";

        $this->db->query($query);
    }

    function ValorComision($id_red){
        $q = $this->db->query("SELECT * FROM valor_comisiones where id_red =".$id_red." group by profundidad order by profundidad");
        return $q->result();
    }

    function CapacidadRed($id_red)
    {
        $q = $this->db->query('select id,frontal,profundidad from tipo_red where id = '.$id_red);

        return $q->result();
    }

    function ConsultarIdPadre($id , $id_red_padre){
        $q = $this->db->query("select debajo_de,lado from afiliar where id_afiliado=".$id." and id_red = ".$id_red_padre." group by debajo_de");
        $id_padre = $q->result();
        return $id_padre;
    }

    function set_comision_afiliado($id_venta,$id_red_mercancia,$id_afiliado,$valor_comision){
        $dato=array(
            "id_venta"       => $id_venta ,
            "id_afiliado"    => $id_afiliado,
            "id_red"         => $id_red_mercancia ,
            "puntos"         => 0,
            "valor"          => $valor_comision,

        );

        $this->db->insert("comision",$dato);
    }

    public function calcularComisionAfiliado($id_venta, $id_red, $costoVenta, $id_afiliado){

        $valores = $this->ValorComision($id_red);
        $capacidad_red = $this->CapacidadRed($id_red);
        $profundidadRed=$capacidad_red[0]->profundidad;
        $red_free = 1;
        for($i=0;$i<$profundidadRed;$i++){


            $afiliado_padre = $this->ConsultarIdPadre($id_afiliado, $red_free);

            if(!$afiliado_padre||$afiliado_padre[0]->debajo_de==1)
                return false;

            $id_padre=$afiliado_padre[0]->debajo_de;

            $valorComision =  0;

            if(isset($valores[$i]))
                $valorComision = $valores[$i]->valor;

            $valor_comision=(($valorComision*$costoVenta)/100);

            $this->set_comision_afiliado($id_venta,$id_red,$id_padre,$valor_comision);

            $id_afiliado=$id_padre;
        }

    }

    function newTickets($id,$tickets,$actived = 'ACT',$date_final = false,$date_init = false){

        $where = "AND i.categoria = 4";
        $mercancia = $this->getMercancia($where);
        $tarifa = $mercancia[0]->costo;
        $red_item = $mercancia[0]->id;

        if (gettype($tickets) != "array")
            $tickets = array($tickets);

        $nTickets = sizeof($tickets);
        $tarifa = $nTickets * $tarifa;

        $id_venta = $this->insertVenta($id,"BILLETERA");
        $this->insertVentaItem($id, $id_venta,$red_item,$nTickets);
        $list_tickets = implode(",",$tickets);
        $descripcion = "NEW TICKET(S) : <a onclick='alert(\"$list_tickets\")'>See details</a>";
        $this->add_sub_billetera("SUB",$id,$tarifa, $descripcion);

        $id_red = 1;
        $factor = 20;
        $costo_venta = $tarifa;
        #TODO: $costo_venta*= $factor/100;
        #$this->calcularComisionAfiliado($id_venta,$id_red,$costo_venta,$id);

        date_default_timezone_set('UTC');
        $nextTime = $this->getNextTime('now', 'day');
        $nextTime .= " 21:00:00";

        if($date_final){
            $timestamp = strtotime($date_final);
            $format = 'Y-m-d';
            $nextTime = date($format, $timestamp);
            $nextTime.= " 21:00:00";
        }

        log_message('DEV',"getNextTime ->> $nextTime");

        $format = 'Y-m-d H:i:s';
        $initTime = date($format);
        if($date_init)
            $initTime = date($format,strtotime($date_init));

        $datos = array(
            "user_id"=>$id,
            "date_creation"=> $initTime,
            "date_final"=> $nextTime,
            "reference"=> $id_venta,
            "estatus" => $actived
        );
        foreach ($tickets as $ticket){
            $datos["amount"] = $ticket;
            log_message('DEV',"creating ticket ->> ".json_encode($datos));
            $this->db->insert("ticket",$datos);
        }

        return true;
    }

    function add_sub_billetera($tipo,$id,$monto,$descripcion){

        date_default_timezone_set('UTC');
        $date = date('Y-m-d H:i:s');
        $dato_cobro = array(
            "id_user" => $id,
            "tipo" => $tipo,
            "descripcion" => $descripcion,
            "monto" => $monto,
            "fecha" => $date
        );

        $this->db->insert("transaccion_billetera",$dato_cobro);
        $id = $this->db->insert_id();
        return $id;
    }

    function insertVentaItem($id, $id_venta,$item,$cantidad =1)
    {
        $query = "INSERT INTO cross_venta_mercancia 
                    SELECT 
                        $id_venta,id,$cantidad,costo,0,costo*$cantidad,'',null
                    FROM
                        mercancia
                    WHERE
                    	id = $item";

        $this->db->query($query);

        return $this->getMontoVentaItem($id_venta, $item);
    }

    function getMercancia($where = ""){

        $query = "SELECT * FROM mercancia m,items i WHERE i.id = m.id $where";

        $q = $this->db->query($query);
        return $q->result();
    }

    private function getMontoVentaItem($id_venta, $item)
    {
        $query = "SELECT costo_total FROM cross_venta_mercancia
            		WHERE
                        id_mercancia = $item
                        AND id_venta = $id_venta";

        $q = $this->db->query($query);
        $result = $q->result();

        if(!$result)
            return false;

        $monto = $this->issetVar($result,"costo_total",0);

        return $monto;
    }

    function isVentaMercancia($id,$tp=false,$mc=false,$WH="",$OD=false,$GP=false)
    {
        $inicioFecha = $this->getInicioFecha($id);
        $fechaFin = date('Y-m-d');
        return $this->getVentaMercancia($id,$inicioFecha,$fechaFin,$tp,$mc,$WH,$OD,$GP);
    }

    /** ARGS:
     * id:id_usuario f0:fechaInicio f1:fechaFin tp:tipo mc:item WH:where OD:order GP:group
     */
    function getVentaMercancia($id,$f0,$f1,$tp=false,$mc=false,$WH="",$OD=false,$GP=false)
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

        $query = "SELECT * -- imprimir
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

        $q = $this->db->query($query);
        $q = $q->result();

        return $q;
    }

    function insertVenta($id,$metodo = "BANCO")
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "id_user" => $id,
            "id_estatus" => "ACT",
            "id_metodo_pago" => $metodo,
            "fecha" => $fecha
        );

        $this->db->insert('venta', $dato);
        return $this->db->insert_id();
    }

    public function procesoNuevoNegocio( $id, $red ,$cumple = false)
    {
        log_message('DEV', "[!] PROCESO CICLO :: ($id)[$red] ");
        $noEmpresa = ($id > 2);
        $Afiliado = $this->isLiderenRed ( $id, $red ,"id_red DESC");
        $ciclo = $this->issetVar($Afiliado,"estatus","DES");
        $negocio = $this->issetVar($Afiliado,"id_red");

        $redes_actual = sizeof($Afiliado);
        $red_actual = $red;

        $avanzaRed = $this->avanzaCiclo($id,$red,$redes_actual) ;
        $type = gettype($avanzaRed);
        $used = ($type != "boolean");
        if($used)
            $cumple = false;

        $avanza = $cumple && $avanzaRed;
        $isPlatino = ($red == 3);

        log_message('DEV',"Valida ($id)[$type] : A:[$avanza] C:[$cumple] P:[$isPlatino] U:[$used]");

        if($avanza){
            $red ++;
            $this->insertNewRed($id, $red);
            $this->nueva_red($id,$red);
            $Afiliado = $this->isAfiliadoenRed($id,$red,"red DESC");
            $negocio = $this->issetVar($Afiliado,"red",$red_actual);
        }else if($cumple)
            $negocio = $this->nueva_red($id,$red);
        else  if($isPlatino)
            $this->initOro($id);
        else if($used)
            return false;

        $posicion = false; $sponsor = false;
        $refered = $id; $sponsorNegocio = $negocio;
        $escala = 0;
        while (!$posicion){
            log_message('DEV',"Escalamiento :: $escala");
            $sponsored =  array(1);
            if($noEmpresa) $sponsored = $this->setSponsorNegocio($refered,$sponsorNegocio,$red);
            $json = json_encode($sponsored);
            log_message('DEV',"_sponsor ($id)[$red] :: $json");#TEST: -

            if(sizeof($sponsored)>1)
                $sponsorNegocio = $sponsored[1];

            $sponsored = $sponsored[0];
            if(!$sponsor)
                $sponsor = $sponsored;

            $posicion = array("debajo_de" => 1, "lado" => 0);
            if($noEmpresa) $posicion = $this->getPosicionNegocio($sponsored,$red,$id);
            $refered = $sponsored;
            $escala++;
        }

        if($posicion === true)
            return true;

        if($escala==0){
            log_message('DEV',"update negocio :: $negocio -> $sponsorNegocio");
            $this->updateRedNegocio($id, $red, $sponsorNegocio, $negocio);
            $negocio = $sponsorNegocio;
        }

        $json = json_encode($posicion);
        log_message('DEV',"_posicion ($id)[$red] :: $json");#TEST:
        $debajo_de = $posicion["debajo_de"];
        $lado = $posicion["lado"];

        $this->setRedCumplida($id, $red_actual);
        return $this->setNegocio($id,$red,$debajo_de,$sponsor, $lado,$negocio);
    }

    private function updateRedNegocio($id,$red, $sponsor, $negocio=false)
    {
        $datos = array("red"=>$sponsor);
        $where = array("id_red"=>$red);
        if($negocio)
            $where["red"]=$negocio;
        $this->editar_afiliacion($id, $datos,$where);
    }

    private function avanzaCiclo($id,$red,$actual)
    {
        $noEmpresa = ($id > 2);
        $DatosRed = $this->getNegocioRed($red,"AND lider = $id");
        $CicloRed = $DatosRed ? $DatosRed->ciclo : 0;
        $avanzaRed = $CicloRed>0 && $CicloRed <= $actual;

        if(!$avanzaRed)
            return false;

        $red ++;
        $Afiliado = $this->isLiderenRed( $id, $red ,"id_red DESC");
        $ciclo = $this->issetVar($Afiliado,"estatus","DES");
        $negocio = $this->issetVar($Afiliado,"id_red");
        $redes = sizeof($Afiliado);

        $isPlatino = $this->isLiderenRed( $id, 3 ,"id_red DESC");
        if($isPlatino && $red == 2)
            return false;

        $isRepeat = ($redes > 0);

        if($isRepeat){
            $this->setRedActiva($id,$red);
            return $negocio;
        }

        return true;
    }

    private function setSponsorNegocio($id,$negocio = 1,$red)
    {   log_message('DEV',"__directo : A:$id N:$negocio R:$red");
        if($id<=2) {
            log_message('DEV',"sponsor --> empresa :: $id");
            $Afiliado = $this->isLiderenRed(2,$red,"id_red DESC");
            $negocioSponsor = $this->issetVar($Afiliado,"id_red");
            return array(2,$negocioSponsor);
        }

        $Afiliado = $this->isAfiliadoenRed ( $id, $red );

        $sponsor = $this->issetVar($Afiliado,"directo");

        if(!$sponsor){
            $directoSponsor = $this->isAfiliadoenRed ( $id );
            $sponsor = $this->issetVar($directoSponsor,"directo",2);
            log_message('DEV',"no sponsor --> origen :: $sponsor");
            $this->initOro($sponsor);
        }

        $Afiliado = $this->isAfiliadoenRed ( $sponsor, $red ,"red DESC");
        #@test: 6

        $ciclo = 'ACT';#TODO: "isset($Afiliado) ? $Afiliado[0]->estatus : "DES";
        $where = false;#TODO: " AND estatus = 'ACT'";

        $ciclos = $this->isLiderenRed ( $id, $red ,"id_red DESC");
        $followme = sizeof($ciclos);

        $Afiliado = $this->isLiderenRed($sponsor,$red,"id_red DESC",$where);
        $negocioSponsor = $this->issetVar($Afiliado,"id_red");
        $follow = sizeof($Afiliado);

        if(!$negocioSponsor)
            return $this->setSponsorNegocio($sponsor,$negocio, $red);
        #@test: 7
        else if ($followme <= $follow)#@test: 8
            return array($sponsor,$negocioSponsor);

        return  $this->setSponsorNegocio($sponsor,$negocio, $red);
    }

    private function insertRedDerived($id, $red,$negocio = false)
    {
        if(!$negocio)
            $negocio = "(SELECT max(id_red) id FROM red
    			        WHERE
    			            lider = a.directo 
                            AND tipo_red = $red AND estatus = 'ACT')";


        $isRegistered = $this->isRegistered($id);
        if(!$isRegistered){
            $query = "INSERT INTO afiliar
                          SELECT null,1,$id,a.debajo_de,a.directo,a.lado,'ACT',0
                             FROM afiliar a WHERE a.id_afiliado = $id LIMIT 1";
            $this->db->query($query);
        }

        $query = "INSERT INTO afiliar
                          SELECT null,$red,$id,a.debajo_de,a.directo,a.lado,'ACT',$negocio
                             FROM afiliar a WHERE a.id_afiliado = $id AND a.id_red = $red 
                             order by id desc limit 1";

        $this->db->query($query);

        $id = $this->getLastAfiliar($id, $red);
        return $id;
    }

    private function insertNewRed($id, $red,$estatus = "ACT")
    {
        $negocio = "(SELECT max(id_red) id FROM red
    			        WHERE
    			            lider = a.directo 
                            AND tipo_red = $red AND estatus = 'ACT')";

        $isRegistered = $this->isRegistered($id);
        if(!$isRegistered){
            $query = "INSERT INTO afiliar
                          SELECT null,1,$id,a.debajo_de,a.directo,a.lado,'$estatus',0
                             FROM afiliar a WHERE a.id_afiliado = $id LIMIT 1";
            $this->db->query($query);
        }

        $query = "INSERT INTO afiliar
                          SELECT null,$red,$id,0,a.directo,a.lado,'$estatus',$negocio
                             FROM afiliar a WHERE a.id_afiliado = $id AND a.id_red = 1";

        $this->db->query($query);

        $id = $this->getLastAfiliar($id, $red);
        return $id;
    }

    private function setNegocio($id_usuario, $red,$debajo_de,$sponsor, $lado,$negocio)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "debajo_de" => $debajo_de,
            "directo" => $sponsor,#TEST: $debajo_de,
            "red" => $negocio,
            "lado" => $lado,
            'id_red' => $red
        );

        $isNegocio = $this->getCicloRedUsuario($id_usuario, $negocio,$red);

        $DONE = (!$isNegocio) ? $this->nueva_afiliacion($id_usuario, $dato) :
            $this->updateNegocio($id_usuario, $red, $debajo_de, $sponsor, $lado, $negocio);
        $json = json_encode($isNegocio);
        $log = "CICLO EXISTENTE ? ($id_usuario)[$red] ** $negocio ** :: -> $json";
        log_message('DEV', $log);
        return $red;
    }

    private function updateNegocio($id_usuario, $red,$debajo_de,$sponsor, $lado,$negocio)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "debajo_de" => $debajo_de,
            "directo" => $sponsor,#TEST: $debajo_de,
            "red" => $negocio,
            "lado" => $lado,
            "estatus" => "ACT"
        );

        $this->db->where('id_afiliado', $id_usuario);
        $this->db->where('id_red', $red);
        $this->db->where('red', $negocio);
        $this->db->update('afiliar', $dato);

        return true;
    }

    private function getPosicionNegocio($sponsor,$red,$id)
    {
        $posicion = $this->setPosicionDebajo($sponsor, $red,false,$id,true);
        log_message('DEV',"Frontal directo ($sponsor) :: ($id) ".json_encode($posicion));

        if($posicion){
            log_message('DEV',"--> posicion directa ($sponsor)");
            return $posicion;
        }

        log_message('DEV',"||-> segundo nivel ($sponsor)");

        $where = "AND a.estatus != 'TMP'";
        $this->getAfiliadosBy($sponsor, 1, "RED", $where, false, $red);
        $afiliados = $this->getAfiliados();

        $afiliados = $this->setFrontalesDebajo($afiliados, $red, $id,$sponsor);

        foreach ($afiliados as $afiliado){
            #log_message('DEV',"debajo de ($afiliado)");#TEST:
            $posicion = $this->setPosicionDebajo($afiliado, $red,$sponsor,$id);
            if($posicion){
                log_message('DEV',"--> posicion indirecta ($sponsor)");
                return $posicion;
            }
        }
        #log_message('DEV',"get debajo ".json_encode($afiliados));#TEST:
        foreach ($afiliados as $afiliado){
            $posicion = $this->getPosicionNegocio($afiliado,$red,$id);
            if($posicion)
                return $posicion;
        }

        return false;
    }

    private function setPosicionDebajo($id,$red,$sponsor = 2,$id_registro,$nodo = false)
    {
        $where = "AND a.estatus != 'TMP'";
        $this->getAfiliadosBy($id, 1,"RED",$where,false,$red);
        $afiliados = $this->getAfiliados(true);

        $cantidad = sizeof($afiliados);

        $lado = 0;
        if($afiliados){
            foreach ($afiliados as $key => $value) {
                if($lado == $key)
                    $lado++;
                else
                    break;
            }/* foreach: $afiliados */
        }/* if: $afiliados */

        $debajo_de = $id;

        $red_datos=$this->getTipoRed($red);
        $frontal = $this->issetVar($red_datos, "frontal", 0);

        $estimados = $this->estimarCiclos($id,$red);
        $dato_ciclos = $this->isLiderenRed($id, $red);
        $ciclos = sizeof($dato_ciclos);

        if($estimados > $ciclos){
            $this->setRedesEstimadas($id, $red, $estimados);
            $ciclos = $estimados;
        }

        $despeja = ($ciclos>0) ? $ciclos-1 : 0;
        $cociente = $frontal*$despeja;
        $log = "L:$lado D:$despeja C:$cociente";

        if($lado>=$cociente){
            $lado -= $cociente;
            if($lado>=$frontal){
                $lado %= $frontal;
            }
        }

        $dato_ciclos = $this->isLiderenRed($id_registro, $red);
        $ciclos_2 = ($nodo) ? sizeof($dato_ciclos) : $ciclos;
        $log="$frontal |-> R1:$ciclos ($nodo) R2:$ciclos_2 $log";
        log_message('DEV', "datos frontales ::>> ($id)[$red] : $log");

        if($ciclos_2 > $ciclos)
            return false;

        $afiliados = $this->setFrontalesDebajoGroup($id,$afiliados,$red,true);
        $json_2 = json_encode($afiliados);
        $sizeof = sizeof($afiliados);

        $posicion = array(
            "debajo_de" => $debajo_de,
            "lado" => $lado,
            "cupo" => $cantidad
        );
        #TEST:
        $json_1 = json_encode($posicion);

        $log = "__debajo ($id)[$red] : $json_2 : $json_1";
        log_message('DEV', $log);

        foreach ($afiliados as $afiliado)
            if($afiliado == $id_registro)
                return $sizeof == 1;

        $frontal *=$ciclos;
        $cupo = $posicion["cupo"];
        log_message('DEV', ">>>| Cupo ($id)[$red] : $cupo < $frontal");
        $iscupo = true;
        if($frontal>0)
            $iscupo = ($cupo < $frontal);

        $isSameID = $id_registro == $debajo_de;
        if(!$iscupo|| $isSameID)
            return false;

        return $posicion;
    }

    function setVIPUser($id,$debajo_de = false,$lado = false,$directo = false){
        $red_vip = 2;
        $isVIP = $this->isAfiliadoenRed($id, $red_vip);

        if(!$directo) $directo = "directo";

        if(!$debajo_de) $debajo_de = "debajo_de";

        if(!$lado) $lado = "lado";

        $query = "UPDATE afiliar 
                  SET debajo_de = $debajo_de,
                   directo = $directo, lado = $lado
                   WHERE id_afiliado = $id AND id_red = $red_vip";

        if(!$isVIP):
            $debajo_de = $id;
            while (!$isVIP) :
                $afiliacion = $this->isAfiliadoenRed($debajo_de);
                $debajo_de = $afiliacion ? $afiliacion[0]->debajo_de : 2;
                $isVIP = $this->isAfiliadoenRed($debajo_de, $red_vip);
                if($debajo_de == 2) $isVIP = true;
            endwhile;
            $lado = $this->getLadosDebajo($debajo_de, $red_vip);
            $query = "INSERT INTO afiliar
                    SELECT null,$red_vip,$id,$debajo_de,$directo,$lado -- imprimir
                    FROM afiliar WHERE id_afiliado = $id AND id_red = 1 
                    LIMIT 1";
        endif;

        $this->db->query($query);

        return true;

    }

    function isAfiliadoenRed($id, $red = false,$order=false,$where=false)
    {
        $query = "SELECT *
                    FROM afiliar WHERE id_afiliado = $id";

        if ($red)
            $query.=" AND id_red in ($red)";
        if ($where)
            $query.=$where;
        if ($order)
            $query .= " ORDER BY $order";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function isLiderenRed($id, $red = false,$order=false,$where=false)
    {
        $query = "SELECT -- imprimir 
* FROM red WHERE lider = $id";

        if ($red)
            $query .= " AND tipo_red in ($red)";

        if ($where)
            $query .= $where;

        if ($order)
            $query .= " ORDER BY $order";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function comisionUpline($id_usuario,$id_red,$fecha= false){

        $parametro =array(
            "id_usuario" => $id_usuario,
            "red" => $id_red,
            "fecha" => $fecha
        );

        $this->getValorBonoUpline($parametro,true);

    }

    function calcularComisionDirecta( $id,$id_red = 1,$where = "")
    {
        if($id<=2)return false;

        $comision = $this->getBonoValorNiveles(1);

        $fechaFin = $this->setFechaformato();
        $fechaInicio = $this->getInicioFecha($id);

        $mercancia = $this->getVentaMercancia($id, $fechaInicio, $fechaFin,2,false,$where);
        $valor = $this->issetVar($mercancia,"costo_unidad",0);
        $id_venta = $this->issetVar($mercancia,"id_venta",1);

        $ultimo = (sizeof($comision)-1);

        $profundidadRed =$ultimo;

        for ($i = 0; $i < $profundidadRed; $i ++) {

            $comision_per = $comision[$ultimo]->valor;
            $index = $i + 1;
            if( isset($comision[$index]) )
                $comision_per = $comision[$index]->valor;

            $valor *=  $comision_per /100;

            $directo = $this->isAfiliadoenRed($id,$id_red);
            $directo = $this->issetVar($directo,"directo",1);

            if ($directo == 1)
                return false;

            $id_directo = $directo;

            $this->setComision($id_directo,$id_red,$valor,$id_venta);

            $id = $id_directo;
        }/* for: $profundidadRed */
    }

    function setComision($id,$id_red,$valor,$id_venta)
    {
        $dato = array(
            "id_venta" => $id_venta,
            "id_afiliado" => $id,
            "id_red" => $id_red,
            "puntos" => 0,
            "valor" => $valor
        );

        $this->db->insert("comision",$dato);
    }

    private function isAutomaticoEscalamiento($id_usuario,$dato = 'activo')
    {
        $query = "SELECT activo,unico FROM billetera WHERE id_user = $id_usuario";
        $q = $this->db->query($query);
        $q = $q->result();

        if(!$q)
            return false;

        $result = ($q[0]->$dato == 'Si') ? true : false;

        return $result;
    }

    private function setRedActiva($id_usuario,$id_red)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "estatus" => "ACT",
            "fecha" => $fecha
        );

        $this->db->where('lider', $id_usuario);
        $this->db->where('id_red', $id_red);
        $this->db->update('red', $dato);
    }

    private function setRedBloqueada($id_usuario,$id_red)
    {
        $fecha = date('Y-m-d H:i:s');
        $dato = array(
            "estatus" => "DES",
            "fecha" => $fecha
        );

        $this->db->where('lider', $id_usuario);
        $this->db->where('id_red', $id_red);
        $this->db->update('red', $dato);
    }

    private function setRedCumplida($id_usuario,$id_red)
    {
        $dato = array(
            "estatus" => "DES"
        );

        $this->db->where('id_afiliado', $id_usuario);
        $this->db->where('id_red', $id_red);
        $this->db->update('afiliar', $dato);

        $this->setRedBloqueada($id_usuario, $id_red);
        $this->limpiarAfiliaciones();
    }

    private function getMontoBono($id_usuario,$id_bono,$fechaInicio,$fechaFin)
    {
        $query = "SELECT sum(c.valor) valor
                    FROM
                		comision_bono c,
                        comision_bono_historial h
                    WHERE
                		c.id_usuario in ($id_usuario)
                        AND h.id_bono = c.id_bono
                        AND c.id_bono = $id_bono
                        AND c.id_bono_historial = h.id
                        AND h.fecha between '$fechaInicio' AND '$fechaFin'";

        $q = $this->db->query($query);
        $q = $q->result();

        if (!$q)
            return 0;

        return $this->issetVar($q, "valor", 0);
    }

    private function getBonoRedes($id,$nored = 1)
    {
        $query = "SELECT 
                        distinct a.id_red,t.nombre 
                    FROM 
                        afiliar a, tipo_red t
                    WHERE 
                        t.id = a.id_red 
                        AND a.id_afiliado = $id 
                        AND a.id_red > 1 
                        AND a.id_red not in ($nored)";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    function getBonoValorNiveles($id)
    {
        $query = "SELECT * FROM cat_bono_valor_nivel WHERE id_bono = $id ORDER BY nivel asc";
        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

    private function getTipoRed($id)
    {
        $q = $this->db->query("SELECT * FROM tipo_red WHERE id = $id");
        $q = $q->result();
        return $q;
    }

    private function getBonos() {
        $q = $this->db->query("SELECT * FROM bono WHERE estatus = 'ACT'");
        $q = $q->result();
        return $q;
    }

    private function getBono($id)
    {
        $q = $this->db->query("SELECT * FROM bono WHERE id = $id");
        $q = $q->result();
        return $q;
    }

    private function getDirectosBy($id,$nivel,$where = "",$red = 1,$negocio =false)
    {
        $subquery ="AND estatus = 'ACT'" ;

        $query = "SELECT 
						distinct a.id_afiliado id,
						a.directo
					FROM
						afiliar a,
						users u
					WHERE
						u.id = a.id_afiliado
						AND a.id_red = $red
						AND a.directo in ($id)
						$subquery $where";

        $q = $this->db->query($query);
        $datos = $q->result();

        if (! $datos)
            return;

        $nivel --;
        foreach ($datos as $dato) {

            if ($nivel <= 0) {
                $this->setAfiliados($dato->id);
            } else {
                $this->getDirectosBy($dato->id, $nivel, $where, $red);
            }/* if: $nivel */
        }/* foreach: $datos */
    }

    private function getAfiliadosBy ($id,$nivel,$tipo,$where,$padre = false,$red = 1)
    {
        $is = array("DIRECTOS" =>"a.directo","RED"=>"a.debajo_de");

        $subquery ="-- AND estatus = 'ACT'";#@test: 9

        $query = "SELECT
						a.id_afiliado id,
						a.directo,a.id rowid
					FROM
						afiliar a,
						users u
					WHERE
						u.id = a.id_afiliado
						AND a.id_red = $red
						AND a.debajo_de = $id
						$subquery $where";

        $q = $this->db->query($query);
        $datos = $q->result();

        if (! $datos)
            return;

        $nivel --;
        foreach ($datos as $dato) {

            if ($nivel <= 0) {

                if ($tipo != "DIRECTOS" || $padre == $dato->directo) {
                    $this->setTempRows($dato->rowid);
                    $this->setAfiliados($dato->id);
                }
            } else {
                $this->getAfiliadosBy($dato->id, $nivel, $tipo, $where, $padre, $red);
            }/* if: $nivel */
        }/* foreach: $datos */
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

    function issetVar($var,$type=false,$novar = false){

        $result = isset($var) ? $var : $novar;

        if($type)
            $result = isset($var[0]->$type) ? $var[0]->$type : $novar;

        if(!isset($var[0]->$type))
            log_message('DEV',"issetVar T:($type) :: ".json_encode($var));

        return $result;
    }

    private function getEmpresa($attrib = 0)
    {
        $q = $this->db->query("SELECT * FROM empresa_multinivel GROUP BY id_tributaria");
        $q = $q->result();

        if(!$q){
            return 0;
        }

        if($attrib === 0){
            return $q;
        }

        return $q[0]->$attrib;
    }

    private function getSemanaFecha ($tipo,$fecha = '')
    {
        if (! $fecha)
            $fecha = date('Y-m-d');

        $now = strtotime($fecha);
        $day = date('w', $now);

        $start = '-' . $day . ' days';
        $end = '+' . (6 - $day) . ' days';
        $week_start = date('Y-m-d', strtotime($start, $now));
        $week_end = date('Y-m-d', strtotime($end, $now));

        return ($tipo == "INI") ? $week_start : $week_end;
    }

     function getPeriodoFecha ($frecuencia,$tipo,$fecha = '')
    {
        if (! $fecha)
            $fecha = date('Y-m-d');

        $fecha = date('Y-m-d',strtotime($fecha));

        $periodoFecha = array(
            "SEM" => "Semana",
            "QUI" => "Quincena",
            "MES" => "Mes",
            "ANO" => "Ano"
        );

        $tipoFecha = array(
            "INI" => "Inicio",
            "FIN" => "Fin"
        );

        if ($frecuencia == "UNI") {
            return ($tipo == "INI") ? $this->getInicioFecha() : date('Y-m-d');
        }

        if (! isset($periodoFecha[$frecuencia]) || ! isset($tipoFecha[$tipo])) {
            return $fecha;
        }

        $functionFecha = "get" . $tipoFecha[$tipo] . $periodoFecha[$frecuencia];

        return $this->$functionFecha($fecha);
    }

    private function getInicioFecha($id = false)
    {
        $query = "SELECT
                        date_format(MIN(created),'%Y-%m-%d') fecha
                    FROM
                        users";

        if($id)$query.=" WHERE id = $id";

        $q = $this->db->query($query);
        $q =$q->result();

        $year = new DateTime();
        $year->setDate($year->format('Y'), 1, 1);

        if(!$q)
            return date_format($year, 'Y-m-d');

        return $this->issetVar($q,"fecha",date('Y-m-d'));
    }

    private function getFinSemana($date)
    {
        $offset = strtotime($date);

        $dayofweek = date('w',$offset);

        if($dayofweek == 6)
            return $date;

        $date = date("Y-m-d", strtotime('last Saturday', strtotime($date)));

        return $date;
    }

    private function getInicioSemana($date)
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string('7 days'));
        $date = date_format($fecha_sub, 'Y-m-d');

        $offset = strtotime($date);

        $dayofweek = date('w',$offset);

        if($dayofweek == 0)
            return $date;

        $date = date("Y-m-d", strtotime('last Sunday', strtotime($date)));

        return $date;
    }

    private function getInicioQuincena($date)
    {
        $dateAux = new DateTime();

        $dayTime = $this->setFechaformato($date,"d");
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $isHalfMonth = ($dayTime<=15);

        $dayTime = ($isHalfMonth) ? 1 : 16;

        $dateAux->setDate($yearTime,$monthTime,$dayTime);

        $date = date_format($dateAux, 'Y-m-d');

        return $date;
    }

    private function getFinQuincena($date) {

        $dateAux = new DateTime();

        $dayTime = $this->setFechaformato($date,"d");
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $isHalfMonth = ($dayTime<=15);

        $date = $this->setFechaformato($date,"Y-m-t");

        if($isHalfMonth){
            $dateAux->setDate($yearTime,$monthTime, 15);
            $date = date_format($dateAux, 'Y-m-d');
        }

        return $date;
    }

    private function getInicioMes($date) {
        $dateAux = new DateTime();
        $monthTime = $this->setFechaformato($date,"m");
        $yearTime = $this->setFechaformato($date,"Y");
        $dateAux->setDate($yearTime,$monthTime, 1);
        return date_format($dateAux, 'Y-m-d');
    }

    private function getFinMes($date) {
        return $this->setFechaformato($date,"Y-m-t");
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

     function getAnyTime($date = 'now', $time = '1 month',$add= false,$format = 'Y-m-d')
    {
        $fecha_sub = new DateTime($date);
        if($add)
            date_add($fecha_sub, date_interval_create_from_date_string("$time"));
        else
            date_sub($fecha_sub, date_interval_create_from_date_string("$time"));

        $date = date_format($fecha_sub, $format);

        return $date;
    }

    function getNextTime($date = 'now', $time = 'month', $format = 'Y-m-d')
    {
        $fecha_sub = new DateTime($date);
        date_add($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, $format);

        return $date;
    }

     function getLastTime($date= 'now', $time = 'month', $format = 'Y-m-d')
    {
        $fecha_sub = new DateTime($date);
        date_sub($fecha_sub, date_interval_create_from_date_string("1 $time"));
        $date = date_format($fecha_sub, $format);

        return $date;
    }

    function reporte_activos ($fechaInicio = "",$fechaFin = "",$id = 2,$status = true)
    {
        $this->setFechaInicio($fechaInicio);
        $this->setFechaFin($fechaFin);

        $red = $this->getTipoRedes();
        $id_red = $this->issetVar($red,"id",1);
        $profundidad = $this->issetVar($red,"profundidad",0);

        $afiliadosEnLaRed=array();
        $afiliadosActivos=array();

        $usuario=new $this->afiliado;
        $usuario->getAfiliadosDebajoDe($id,$id_red,"RED",0,$profundidad);
        $afiliadosEnLaRed = $usuario->getIdAfiliadosRed();

        foreach ($afiliadosEnLaRed as $afiliado){

            $Activado = $this->isActivedAfiliado($afiliado);

            if($Activado==$status){
                $query = "SELECT
							 	a.id,
							 	a.username usuario,
							 	b.nombre nombre,
							 	b.apellido apellido,
							 	a.email
							FROM
								users a,
								user_profiles b
							WHERE
								a.id=b.user_id
								AND b.id_tipo_usuario=2
								AND a.id=$afiliado";
                $q=$this->db->query($query);

                $afiliado=$q->result();
                array_push($afiliadosActivos,$afiliado);
            }/* if: $Activado */
        }/* foreach: $afiliadosEnLaRed */

        return $afiliadosActivos;
    }

    private function getTipoRedes()
    {
        $q = $this->db->query('SELECT * FROM tipo_red');
        $red = $q->result();
        return $q;
    }

    /** <? TEST ?>
     *	last time : 2017-08-05
     *	recent author : qcmarcel
     *  #TEST: ($parametro){
     */

    private function test() {
        return;
        $where = " AND u.created BETWEEN '$fechaInicio' AND '$fechaFin 23:59:59'";
        foreach ($afiliados[$lvl] as $afiliado){
            $activoAfiliado = $this->isActivedAfiliado($afiliado);
        }
        $this->descontarBilleteraCiclo($id, $id_venta, $monto);
        $negocio = isset($Afiliado[0]->red) ? $Afiliado[0]->red : false;
        if ($negocio==$negocioSponsor) return array($sponsor);
        $isMax = ($negocio<$negocioSponsor);
        $subquery ="AND lider = $padre ";
    }

    private function setRedesEstimadas($id, $red, $estimados)
    {
        $dato_ciclos = $this->isLiderenRed($id, $red);
        $ciclos = sizeof($dato_ciclos);

        if ($ciclos < $estimados) {
            $index =1;
            for ($i = 0; $i < $estimados - $ciclos; $i++) {
                $ciclo = $this->nueva_red($id, $red);
                $this->insertRedDerived($id, $red,$ciclo);
                $index++;
            }
            log_message('DEV',"CICLOS RECUPERADOS : $index");
        }
    }

    private function estimarCiclosCompletos($id_usuario, $id_red, $fechaInicio, $fechaFin)
    {
        return $this->estimarCiclos($id_usuario,$id_red,$fechaInicio,$fechaFin,true);
    }

    private function isRepeatedValueBitcoin($value)
    {
        $query = "SELECT * FROM ticket WHERE amount = $value";
        $q = $this->db->query($query);
        $q = $q->result();

        return $q;
    }


     function getValueTicketAuto()
    {
        $bitcoin_value = $this->getBitcoinValue();

        $value = $bitcoin_value;
        $limit = 2000;
        $min_rand = $bitcoin_value - $limit;
        $max_rand = $bitcoin_value + $limit;

        $isRepeated = true;
        $iteration = 10;
        $counter = 0;
        $iterate = true;
        while ($iterate) {
            $value = rand($min_rand, $max_rand);
            $isRepeated = $this->isRepeatedValueBitcoin($value);
            $iterate = $isRepeated;
            if(!$iterate)
                $iterate = $counter < $iteration;
            $counter++;
        }
        log_message('DEV',"auto val btc:: $value");
        return $value;
    }

    private function getCoinmarket()
    {
        $q = $this->db->query("SELECT * FROM coinmarketcap");
        $q = $q->result();

        if (!$q)
            return false;

        return $q[0];
    }

    function getBitcoinValue()
    {
        $getcwd = getcwd();
        $localBitcoin = 3854.6;

        return $localBitcoin;#TODO: activar plan

        $islocalEnv = stripos($getcwd, "/var/www")!==false;
        $islocalEnv |= stripos($getcwd, ":")!==false;

        if($islocalEnv){
            log_message('DEV',"bitcoin value for LOCAL enviroment :: $localBitcoin");
            return $localBitcoin;
        }

        $this->bitcoinCap->getLatest();
        $bitcoin = $this->bitcoinCap->getData();

        if(!isset($bitcoin["data"])){
            $bitcoinCap = new $this->model_coinmarketcap(1);
            $bitcoinCap->getLatest();
            $bitcoin= $bitcoinCap->getData();
        }

        $bitcoin_value = $bitcoin["data"][0]["quote"]["USD"]["price"];
        log_message('DEV',"bitcoin----> ".json_encode($bitcoin_value));

        return $bitcoin_value;
    }

    private function getLadosDebajo($debajo_de, $red_vip)
    {
        $query = "SELECT count(*) lados FROM afiliar
                        WHERE debajo_de = $debajo_de AND id_red = $red_vip";
        $q = $this->db->query($query);
        $result = $q->result();
        $lado = $result ? $result[0]->lados : 0;
        return $lado;
    }

    private function getRedTodo($id_usuario, $red = 1)
    {
        $limit = false;
        $nivel = 1;
        $red_todo = array();
        while (!$limit):
            $this->getAfiliadosBy($id_usuario, $nivel, "RED", "", 2, $red);
            $afiliados = $this->getAfiliados();
            $json = json_encode($afiliados);
            log_message('DEV', " Nivel $nivel : $json ");
            if (!$afiliados):
                $limit = true;
                break;
            endif;

            $afiliados = implode(",", $afiliados);
            array_push($red_todo, $afiliados);
            $nivel++;
        endwhile;
        return $red_todo;
    }

    private function getMontoMercanciaRed($red_afiliados, $inicio, $fin, $tipo = false, $item = false, $where = "")
    {
        $total = 0;
        foreach ($red_afiliados as $n => $afiliados):
            $nivel = $n + 1;
            $ventas = $this->getVentaMercancia($afiliados, $inicio, $fin, $tipo, $item, $where);

            if (!$ventas)
                continue;

            $monto = 0;
            foreach ($ventas as $venta) :
                $monto += $venta->costo;
            endforeach;

            $total += $monto;
            log_message('DEV', " Nivel $nivel : $afiliados -> $monto");
        endforeach;
        return $total;
    }

    private function isCondicionesRango($id_rango, $valor = 0,$estimar = false)
    {
        $cumple=false;

        $where = "id >= $id_rango OR orden >= $id_rango";

        if($estimar)
            $where = "id > $id_rango OR orden > $id_rango";

        $condiciones = $this->getTitulo(false, $where);

        if(!$condiciones)
            return false;

        foreach ($condiciones as $condicion) {

            $regla = $condicion->valor;
            $reglaMonto = $valor - $regla;
            $isRegla = ($reglaMonto >= 0);

            $id_rango = $condicion->id;
            log_message('DEV', " Rango ? $id_rango : $valor >= $regla ? [[ $isRegla ]]");

            if (!$isRegla)
                break;

            log_message('DEV', " Rango estimated :: $id_rango");
            $cumple = $id_rango;

        }
        return $cumple;
    }


}
