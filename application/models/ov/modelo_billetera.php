<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class modelo_billetera extends CI_Model
{
	function get_psr($id)
	{
        $query = "SELECT * FROM
                    venta v,
                     cross_venta_mercancia c,
                     items i,
                     mercancia m 
                    -- ,comision_pasivo s
                    WHERE i.id = m.id 
                        -- AND s.reference = c.id_venta
                        AND m.id = c.id_mercancia 
                        AND c.id_venta = v.id_venta
                        AND i.categoria = 2
                        AND v.id_user in ($id)
                        AND id_estatus = 'ACT'
                    ORDER BY v.fecha";

        $q=$this->db->query($query);
        $result = $q->result();

        log_message('DEV',"psr ($id) :: ".json_encode($result));
        return $result;
	}

    function get_estatus($id)
    {
        $q=$this->db->query('SELECT * from billetera where id_user='.$id);
        return $q->result();
    }

	function crea_pswd($id)
	{
		$pswd=strlen($_POST['password']).strrev($_POST['password']);
		$pswd=md5($pswd).$pswd;
		$pswd=md5(strrev($pswd));
		$this->db->query('update billetera set pswd="'.$pswd.'", estatus="ACT" where id_user='.$id);
	}
	function iniciar($id)
	{
		$pswd=strlen($_POST['password']).strrev($_POST['password']);
		$pswd=md5($pswd).$pswd;
		$pswd=md5(strrev($pswd));

		$q=$this->db->query('select * from billetera where pswd="'.$pswd.'" and id_user='.$id);
		return $q->result();
	}
	function sesion($id)
	{
		$q=$this->db->query('select activo from billetera where id_user='.$id);
		return $q->result();
	}
	function activar($id)
	{
		$q=$this->db->query('update billetera set activo="Si" where id_user='.$id);
	}
	function desactivar($id)
	{
		$q=$this->db->query('update billetera set activo="No" where id_user='.$id);
	}
	
	function get_historial_cuenta($id)
	{
		$q=$this->db->query('SELECT
								date_format(h.fecha,"%Y-%m-01") as fecha,		
								-- b.nombre bono,
								(select sum(valor)
									from comision 
									where id_afiliado='.$id.' 
										and date_format(fecha,"%Y-%m-01") = date_format(h.fecha,"%Y-%m-01")) comision , 
								sum(c.valor) bono
							FROM comision_bono_historial h , comision_bono c, bono b
							WHERE 
								c.id_usuario='.$id.'
								and b.id = c.id_bono 
								and c.id_bono_historial = h.id
								-- and o.fecha = h.fecha
							GROUP BY 
								month(h.fecha)
							ORDER BY h.fecha desc');
		return $q->result();
	}
	
	function get_historial($id)
	{
		$q=$this->db->query('select * from cobro where id_user='.$id.'  and (id_estatus=4 or id_estatus=5)  order by fecha');
		return $q->result();
	}
	function get_monto($id)
	{
		$q=$this->db->query('select sum(monto) as monto from cobro where id_user='.$id.' and id_estatus=4');
		return $q->result();
	}
	
	function get_comisiones($id,$id_red){
		$q=$this->db->query('SELECT t.id as red, sum(c.puntos) as puntos,sum(c.valor) as valor,t.nombre as nombre FROM comision c,tipo_red t 
		where (c.id_red=t.id) and(t.id='.$id_red.') and c.id_afiliado='.$id.'');
		return $q->result();
	}
	
	function get_total_comisiones_afiliado($id){
		
		$q=$this->db->query('SELECT sum(valor)as valor FROM comision where id_afiliado='.$id.';');
		$comisiones=$q->result();
		return $comisiones[0]->valor;
	}
	
	function get_total_comisiones_afiliado_Range($id,$inicio,$fin){
	
		$q=$this->db->query('SELECT sum(valor)as valor FROM comision where id_afiliado='.$id.' and fecha between "'.$inicio.' 00:00:00" and "'.$fin.' 23:59:59"');
		$comisiones=$q->result();
		return $comisiones[0]->valor;
	}
	
	function get_comisiones_mes($id,$id_red,$fecha){
		$q=$this->db->query('SELECT sum(c.puntos) as puntos,sum(c.valor) as valor,t.nombre as nombre FROM comision c,tipo_red t 
		where (c.id_red=t.id) and(t.id='.$id_red.') and c.id_afiliado='.$id.' and MONTH("'.$fecha.'")=MONTH(fecha)');
		return $q->result();
	}
	
	function get_comisiones_Range($id,$id_red,$inicio, $fin){
		$q=$this->db->query('SELECT sum(c.puntos) as puntos,sum(c.valor) as valor,t.nombre as nombre FROM comision c,tipo_red t 
		where (c.id_red=t.id) and(t.id='.$id_red.') and c.id_afiliado='.$id.' and c.fecha between "'.$inicio.' 00:00:00" and "'.$fin.' 23:59:59"');
		return $q->result();
	}
	
	function get_cobro($id)
	{
		$q=$this->db->query('select * from cobro where  id_estatus != 4 and id_user='.$id);
		return $q->result();
	}
	
	function get_cobros_total($id)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus=2 and id_user='.$id);
		return $q->result();
	}
	
	function get_cobros_total_afiliado($id)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus=2 and id_user='.$id);
		$cobros=$q->result();
		return $cobros[0]->monto;
	}
	
	function get_cobros_pendientes_total_afiliado($id)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus!=2 and id_user='.$id);
		$cobros=$q->result();
		return $cobros[0]->monto;
	}
	
	function get_cobros_afiliado_mes($id,$fecha)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus=2 and month("'.$fecha.'")=month(fecha_pago) and id_user='.$id);
		return $q->result();
	}
	
	function get_cobros_total_Range($id,$inicio,$fin)
	{
		$q=$this->db->query('SELECT round(sum(monto),2) as monto FROM cobro where  id_estatus=2 and fecha_pago between "'.$inicio.' 00:00:00" and "'.$fin.' 23:59:59" and id_user='.$id);
		return $q->result();
	}
	
	function get_cobros_afiliado_mes_pendientes($id,$fecha)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus!=2 and month("'.$fecha.'")=month(fecha_pago) and id_user='.$id);
		$cobros=$q->result();
		return $cobros[0]->monto;
	}
	
	function get_cobros_pendientes_total_afiliado_Range($id,$inicio,$fin)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus!=2 and fecha_pago between "'.$inicio.' 00:00:00" and "'.$fin.' 23:59:59" and id_user='.$id);
		$cobros=$q->result();
		return $cobros[0]->monto;
	}
	
	function get_cobros_afiliado_mes_actual($id)
	{
		$q=$this->db->query('SELECT sum(monto)as monto FROM cobro where  id_estatus=2 and month(now())=month(fecha_pago) and id_user='.$id);
		return $q->result();
	}
	
	function datable($id)
	{
		$q=$this->db->query('select * ,
							(select descripcion from cat_metodo_cobro MP where C.id_metodo=MP.id_metodo) metodo, 
							(select descripcion from cat_estatus CE where CE.id_estatus=C.id_estatus) estado 
							from cobro C where id_user='.$id.' and (id_estatus = 3 or id_estatus = 2) order by fecha');
		return $q->result();
	}
	function get_metodo()
	{
		$q=$this->db->query('select * from cat_metodo_cobro');
		return $q->result();
	}
	function cobrar($id,$cuenta,$titular,$banco,$clabe)
	{
        $cpais = $_POST['cpais'];
        $cswift = $_POST['cswift'];
        $cotro = $_POST['cotro'];
        $cpostal = $_POST['cpostal'];
        $cobro = $_POST['cobro'];

        $dato_cobro = array(
            "id_user" => $id,
            "id_metodo" => "1",
            "id_estatus" => "3",
            "monto" => $cobro,
            "cuenta" => $cuenta,
            "titular" => $titular,
            "banco" => $banco,
            "clabe" => $clabe,
            "pais" => $cpais,
            "swift" => $cswift,
            "otro" => $cotro,
            "postal" => $cpostal,
        );

        if(isset($_POST['wallet']))
            $dato_cobro["address"] = $_POST['wallet'];

        $this->db->insert("cobro", $dato_cobro);
		
	}
	
	function anosCobro($id){
		$q = $this->db->query("select YEAR(fecha) as año from cobro where id_user='$id' group by año");
		return $q->result();
	}
	
	function ValorImpuestos($id, $pais){
		$mes = date("m");
		$q = $this->db->query("select ci.id_impuesto, ci.descripcion, sum(v.costo * (ci.porcentaje/100)) as impuesto 
					from venta v, cross_venta_mercancia cvm, cross_merc_impuesto cmi, cat_impuesto ci, mercancia m 
					where v.id_venta = cvm.id_venta and  cmi.id_mercancia = cvm.id_mercancia and cmi.id_impuesto = ci.id_impuesto and v.id_user = ".$id." and cvm.id_mercancia = m.sku and m.pais = ci.id_pais and ci.estatus = 'ACT' and Month(v.fecha) = '".$mes."' and m.pais = '".$pais."'");
		return $q->result();
	}
	
	function ValorRetenciones(){
		$q = $this->db->query("SELECT * FROM cat_retenciones_historial where year(now())=ano and month(now())=mes");
		$retenciones_regis = $q->result();
		$retenciones = array();
		foreach ($retenciones_regis as $retencion){
		/*	$valor=0;
			if($retencion->duracion == 'ANO'){
				$valor = $retencion->porcentaje / 12;
			}elseif ($retencion->duracion == 'SEM'){
				$valor = $retencion->porcentaje / 4; 
			}elseif ($retencion->duracion == 'MES'){
				$valor = $retencion->porcentaje ; 
			}elseif ($retencion->duracion == 'DIA'){
				$valor = $retencion->porcentaje * 30;
			}*/
			$retencion_cobrar = array('id' => $retencion->id,
									'descripcion' => $retencion->descripcion,
									'valor'   => $retencion->valor);
			$retenciones[] = $retencion_cobrar;
		}
		return $retenciones;
	}
	
	function ValorRetencionesTotales($id){
		$q = $this->db->query("SELECT created FROM users where id=".$id.";");
		$fecha_creacion = $q->result();
		$q = $this->db->query("SELECT descripcion,sum(valor) as valor FROM cat_retenciones_historial  
								where '".$fecha_creacion[0]->created."'<=str_to_date(concat(ano,'-',mes,'-','28'),'%Y-%m-%d') group by descripcion");
		$retenciones_regis = $q->result();
		$retenciones = array();
		foreach ($retenciones_regis as $retencion){
			/*	$valor=0;
				if($retencion->duracion == 'ANO'){
				$valor = $retencion->porcentaje / 12;
				}elseif ($retencion->duracion == 'SEM'){
				$valor = $retencion->porcentaje / 4;
				}elseif ($retencion->duracion == 'MES'){
				$valor = $retencion->porcentaje ;
				}elseif ($retencion->duracion == 'DIA'){
				$valor = $retencion->porcentaje * 30;
				}*/
			$retencion_cobrar = array(
					'descripcion' => $retencion->descripcion,
					'valor'   => $retencion->valor);
			$retenciones[] = $retencion_cobrar;
		}
		return $retenciones;
	}
	
	function ValorRetencionesTotalesAfiliado($id){
		$q = $this->db->query("SELECT created FROM users where id=".$id);
		$fecha_creacion = $q->result();

		$q = $this->db->query("SELECT sum(valor) as valor FROM cat_retenciones_historial  where month('".$fecha_creacion[0]->created."')
										<=mes and year('".$fecha_creacion[0]->created."')<=ano");
		$retenciones = $q->result();
		return $retenciones[0]->valor;
	}
	
	
	function ValorRetenciones_historial($fecha,$id){
		$q = $this->db->query("SELECT created FROM users where id=".$id);
		$fecha_creacion = $q->result();
		
		$q = $this->db->query("SELECT * FROM cat_retenciones_historial where year('".$fecha."')=ano and month('".$fecha."')=mes and month('".$fecha_creacion[0]->created."')
										<=mes and year('".$fecha_creacion[0]->created."')<=ano");
		$retenciones_regis = $q->result();
		$retenciones = array();
		foreach ($retenciones_regis as $retencion){

			$retencion_cobrar = array('id' => $retencion->id,
					'descripcion' => $retencion->descripcion,
					'valor'   => $retencion->valor);
			$retenciones[] = $retencion_cobrar;
		}
		return $retenciones;
	}
	
	function ValorRetencionesTotales_Range($id, $inicio,$fin){
		
		$q = $this->db->query("SELECT created FROM users where id=".$id);
		$fecha_creacion = $q->result();
		
		$q = $this->db->query("SELECT * FROM cat_retenciones_historial 
				where concat(ano,'-',mes) > '".date("Y-m", strtotime($inicio))."' 
				and concat(ano,'-',mes) <= '".date("Y-m", strtotime($fin))."' 
				and month('".$fecha_creacion[0]->created."')<=mes 
				and year('".$fecha_creacion[0]->created."')<=ano");
		
		$retenciones_regis = $q->result();
		$retenciones = array();
		foreach ($retenciones_regis as $retencion){

			$retencion_cobrar = array('id' => $retencion->id,
					'descripcion' => $retencion->descripcion,
					'valor'   => $retencion->valor);
			$retenciones[] = $retencion_cobrar;
		}
		return $retenciones;
	}
	
	function PagosClientes()
	{
		$q=$this->db->query('select * ,
							(select descripcion from cat_metodo_cobro MP where C.id_metodo=MP.id_metodo) metodo,
							(select descripcion from cat_estatus CE where CE.id_estatus=C.id_estatus) estado
							from cobro C where id_estatus = 3 order by fecha');
		return $q->result();
	}
	
	function añosPagos(){
		$q = $this->db->query("select YEAR(fecha) as año from cobro group by año");
		return $q->result();
	}
	
	function comisionWebPersonal($id_afiliado, $fecha){
		$q=$this->db->query('select sum(valor) as valor from comision_web_personal where MONTH(fecha) = MONTH("'.$fecha.'") and id_afiliado ='.$id_afiliado.'');
		return $q->result();
	}
	
	function get_comisiones_web_personal($id_afiliado){
		$q=$this->db->query('select sum(valor) as valor from comision_web_personal where id_afiliado ='.$id_afiliado.'');
		return $q->result();
	}
	
	function get_historial_cuenta_web_personal($id)
	{
		$q=$this->db->query('SELECT  DATE_FORMAT(fecha,"%Y-%m-01") as fecha, sum(valor) as valor FROM comision_web_personal where id_afiliado = "'.$id.'" group by MONTH(fecha)');
		return $q->result();
	}
	
	function getComisionDirectos($id_afiliado, $id_red)
	{
		$q = $this->db->query("select sum(c.puntos) as puntos, sum(c.valor) as valor
		from afiliar a, comision c, venta v
		where v.id_user = a.id_afiliado and v.id_venta = c.id_venta  and a.id_red = ".$id_red." and c.id_red = ".$id_red." and  c.id_afiliado = ".$id_afiliado." and  a.debajo_de = ".$id_afiliado);
	
		return $q->result();
	}
	
	function getComisionDirectosMes($id_afiliado, $id_red, $fecha)
	{
		$q = $this->db->query("select sum(c.puntos) as puntos, sum(c.valor) as valor
		from afiliar a, comision c, venta v
		where v.id_user = a.id_afiliado and v.id_venta = c.id_venta  and a.id_red = ".$id_red." and c.id_red = ".$id_red." and  c.id_afiliado = ".$id_afiliado." and  a.debajo_de = ".$id_afiliado." and (MONTH('".$fecha."') = MONTH(c.fecha))");
		return $q->result();
	}
	
	function getComisionDirectos_Range($id_afiliado, $id_red, $inicio, $fin)
	{
		$q = $this->db->query("select sum(c.puntos) as puntos, sum(c.valor) as valor
		from afiliar a, comision c, venta v
		where v.id_user = a.id_afiliado and v.id_venta = c.id_venta  and a.id_red = ".$id_red." and c.id_red = ".$id_red." and  c.id_afiliado = ".$id_afiliado." and  a.debajo_de = ".$id_afiliado." and c.fecha between '".$inicio." 00:00:00' and '".$fin." 23:59:59'");
		return $q->result();
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
	
	function get_transacciones_fecha($inicio,$fin){
		$q=$this->db->query("select t.id, u.username, concat(p.nombre,' ',p.apellido) as nombres , (case when (t.tipo = 'ADD') then 'plus' else 'minus' end) as tipo, t.descripcion , t.monto
from transaccion_billetera t, users u, user_profiles p
 where u.id = t.id_user and p.user_id = t.id_user and t.fecha between '".$inicio." 00:00:00' and '".$fin." 23:59:59' order by fecha ");
		$q2=$q->result();
	
		return $q2;
	}
	
	function get_ventas_comision_id($id){
		$q=$this->db->query("SELECT c.id_venta, v.fecha, t.nombre as red, c.id_afiliado,
								concat(p.nombre,' ',p.apellido) as nombres,
								(select group_concat(
										concat((
										select 
											item
										from items 
										where id = s.id_mercancia
											),' (',cantidad,') ')
									) 
									from cross_venta_mercancia s
									where s.id_venta = c.id_venta) as items,
								(select sum(costo_total) 
									from cross_venta_mercancia 
									where id_venta = c.id_venta) as total,
								(select sum(valor) from comision where id_venta = c.id_venta and id_afiliado =  c.id_afiliado) comision
							FROM comision c , user_profiles p , venta v, tipo_red t
							WHERE 	
								p.user_id = v.id_user
								and v.id_venta = c.id_venta
								and	t.id = c.id_red
								and c.id_afiliado = ".$id."
								-- and date_format(v.fecha,'%Y-%m') = ''
							GROUP BY c.id_venta
							ORDER BY c.id ;");
		$q2=$q->result();
	
		return $q2;
	}
	
	function get_ventas_comision_fecha($id,$fecha){
		$q=$this->db->query("SELECT c.id_venta, v.fecha, t.nombre as red, c.id_afiliado,
								concat(p.nombre,' ',p.apellido) as nombres,
								(select group_concat(
										concat((
										select
											item
										from items
										where id = s.id_mercancia
											),' (',cantidad,') ')
									)
									from cross_venta_mercancia s
									where s.id_venta = c.id_venta) as items,
								(select sum(costo_total)
									from cross_venta_mercancia
									where id_venta = c.id_venta) as total,
								c.valor as comision
							FROM comision c , user_profiles p , venta v, tipo_red t
							WHERE
								p.user_id = v.id_user
								and v.id_venta = c.id_venta
								and	t.id = c.id_red
								and c.id_afiliado = ".$id."
								and date_format(v.fecha,'%Y-%m') = '".date("Y-m", strtotime($fecha))."'
							GROUP BY c.id_venta
							ORDER BY c.id ;");
		$q2=$q->result();
	
		return $q2;
	}
	
	function get_transacciones_id($id){
        $query = "select 
                    id, fecha, 
                    (case when (tipo = 'ADD') 
                        then 'plus' 
                        else 'minus' end) tipo_add, tipo,
                     descripcion , monto
                    from 
                      transaccion_billetera 
                      where id_user = $id
                       and tipo != 'TKN'
                      order by fecha desc ";
        $q=$this->db->query($query);
		$q2=$q->result();
	
		return $q2;
	}
	
	function get_total_transacciones_id_fecha($id,$fecha){
		$q=$this->db->query("select tipo,sum(monto) as valor from transaccion_billetera where id_user = ".$id." and date_format(fecha,'%Y-%m') = '".date("Y-m", strtotime($fecha))."' group by tipo ");
		$q2=$q->result();
		
		return $this->set_transacciones_format($q2);
	}
	
	function get_total_transacciones_id_Range($id,$inicio,$fin){
		$q=$this->db->query("select tipo,sum(monto) as valor from transaccion_billetera where id_user = ".$id." and fecha between '".$inicio." 00:00:00' and '".$fin." 23:59:59' group by tipo ");
		$q2=$q->result();
		
		return $this->set_transacciones_format($q2);
	}
	
	function get_id_transaccion($id,$monto,$descripcion,$tipo){
		$q=$this->db->query("select max(id) as id from transaccion_billetera 
				where id_user = ".$id./*" and monto = ".number_format($monto,2)." and descripcion = '".$descripcion."'".*/" and tipo = '".$tipo."'");
		$q2=$q->result();
	
		return $q2[0]->id ;
	}
	
	function get_total_transacciones_id($id){
		$q=$this->db->query("select tipo,sum(monto) as valor from transaccion_billetera where id_user = ".$id." group by tipo ");
		$q2=$q->result();			
		
		return $this->set_transacciones_format($q2) ;
	}
	
	function kill_transaccion($id){
		$this->db->query("delete from transaccion_billetera where id = ".$id);
	
		return true ;
	}
	
	function get_total_transact_id($id){
		$q=$this->get_total_transacciones_id($id);
		$total_transact = 0;
		
		if ($q) {
			if ($q['add']) {
				$total_transact+=$q['add'];
			} 
			if ($q['sub']) {
				$total_transact-=$q['sub'];
			}
		}
		 
		return $total_transact;
	}
	
	function set_transacciones_format($q2){
		
		if(!$q2) {
			return array(
                'add'=>0 ,
                'sub'=>0
            );
		}
		
		$i=0;
		$add=null;
		$sub=null;
		
		foreach ($q2 as $key){
			switch ($key->tipo){
				case "ADD" : $add=$key->valor;break;
				case "SUB" : $sub=$key->valor;break;
			}
		}
		
		$q3=array(
				'add'=>$add ,
				'sub'=>$sub
		);
		
		return $q3;		
	}

    function newTransferMoney($id,$user,$token,$valor = 0)
    {
        $query = "UPDATE transaccion_billetera 
                    set monto = $valor,id_user = $user,tipo = 'TRN'
                    where invoice = $id and token = '$token'";
        $this->db->query($query);
    }

    function setTransferMoney($id,$token)
    {
        $query = "INSERT INTO 
                    transaccion_billetera (tipo,descripcion,token,invoice)
                    VALUES ('TKN','Transfer money','$token',$id)";
        $this->db->query($query);
    }


    function setTransferSuccess($id, $token, $id_user)
    {
        $query = "INSERT INTO transaccion_billetera
                    SELECT null,now(),t.invoice,'SUB',
                   concat('Transferred money to ID: ',t.id_user),
                    t.monto+(2),
                    t.token,t.id_user
                    FROM transaccion_billetera t
                    where t.id = $id 
                    and t.token = '$token' 
                    AND t.tipo = 'TRN'
                    AND t.id_user = $id_user";
        $this->db->query($query);

        $query = "UPDATE transaccion_billetera
                    set tipo = 'ADD',
                     descripcion = concat('Transferred money by ID: ',invoice)
                    where id = $id 
                    and token = '$token' 
                    AND tipo = 'TRN'
                    AND id_user = $id_user";
        $this->db->query($query);
    }

    function getTransferring($id, $token, $id_user)
    {
        $query = "SELECT * FROM transaccion_billetera
                    where id = $id 
                    and token = '$token' 
                    AND tipo = 'TRN'
                    AND id_user = $id_user";
        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }


}
