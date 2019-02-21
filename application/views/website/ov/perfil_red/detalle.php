<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<?= '<img alt="'.$usuario[0]->nombre.'" src="'.$img_perfil.'" style="height:10em">'; ?>
			</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<div class="demo-icon-font">Country
					<?= '<img class="flag flag-'.strtolower($pais).'" >';?>
               	</div>
		
				<div class="row">Id: <b><?=$_POST["id"];?></b></div>
		
		<?php 
		$direccion = $dir[0]->calle.' '.
					$dir[0]->colonia.' '.
					$dir[0]->municipio.' '.
					$dir[0]->estado.' '.
					$dir[0]->cp;
		
		if(!$puntos)
			$puntos = 0;
		else if($puntos[0]->puntos == 0)
			$puntos = $puntos[0]->puntosu;
		else  $puntos = $puntos[0]->puntos;
		
		if(!$compras)
			$compras=0;
		else if($compras[0]->compras== 0)
				$compras= $compras[0]->comprast;
		else $compras= $compras[0]->compras;
					
		?>
		
		<div class="row">Username: <b><?=$username;?></b></div>
		<div class="row">Name: <b><?=$usuario[0]->nombre;?></b></div>
		<div class="row">Surname: <b><?=$usuario[0]->apellido;?></b></div>
		<div class="row">Birthdate: <b><?=$usuario[0]->nacimiento;?></b></div>
		<div class="row">Age: <b><?=$edad[0]->edad;?></b></div>
		<div class="row">Email: <b><?=$usuario[0]->email;?></b></div>
		<div class="row">Address: <b><?=$direccion;?></b></div>
		<div class="row">Fixed telephone(s): 
		<?php foreach ($telefonos as $key)
		{
			if($key->tipo=='Fijo')
			{
				echo "<b>".$key->numero."</b><br />";
			}
		} ?>
		</div>
		<div class="row">Mobile phone(s): 
		<?php foreach ($telefonos as $key)
		{
			if($key->tipo=='Móvil')
			{
				echo "<b>".$key->numero."</b><br />";
			}
		} ?>
		</div><br>
		<div class="row">Purchases: $ <b><?=number_format($compras,2);?></b></div>
		<div class="row">Commissions: $ <b><?=number_format($comision+$bonos,2);?></b></div>
		<div class="row">Points of commissions:  <b><?=number_format($puntos,2);?></b></div>
		<?php		
		
		if($titulo!=NULL){
		
			echo '<ul id="sparks" class="">
					<li class="sparks-info">
					 <h5>Ranking<span class="txt-color-yellow"><i class="fa fa-trophy fa-2x"></i>'.$titulo.'</span></h5>
					 <div class="sparkline txt-color-yellow hidden-mobile hidden-md hidden-sm"></div>
					</li>
				</ul>';
				
		} ?>
		</div></div></div>