<form method="POST" id="proveedor" class="smart-form">
	<input type="text" class="hide" value="1" name="ser"/>
	<fieldset>
		<legend>Personal Info del proveedor</legend>
		<div class="row">
			<section class="col col-6">
				<label class="input"> <i class="icon-prepend fa fa-user"></i> <input
					required type="text" name="nombre" id="nombre" placeholder="Name">
				</label>
			</section>
			<section class="col col-6">
				<label class="input"> <i class="icon-prepend fa fa-user"></i> <input
					required type="text" name="apellido" id="apellido"
					placeholder="Lastname">
				</label>
			</section>
			<section id="correo1" class="col col-6">
				<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
					<input id="email" required type="email" name="email"
					placeholder="Email">
				</label>
			</section>
		</div>
		<div class="row">
			<div id="tel1" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<section class="col col-6">
					<label class="input"> <i class="icon-prepend fa fa-phone"></i> <input
						required name="fijo[]" placeholder="(99) 99-99-99-99" type="tel"
						pattern="[0-9]{7,50}"
						title="Por favor digite un numero of telefono valido">
					</label>
				</section>
				<section class="col col-6">
					<label class="input"> <i class="icon-prepend fa fa-mobile"></i> <input
						required name="movil[]" placeholder="(999) 99-99-99-99-99"
						type="tel" pattern="[0-9]{7,50}"
						title="Por favor digite un numero of telefono valido">
					</label>
				</section>
			</div>
			<section class="col col-6">
				<button type="button" onclick="agregar1('1')"
					class="btn btn-primary">
					&nbsp;Add <i class="fa fa-mobile"></i>&nbsp;
				</button>
				<button type="button" onclick="agregar1('2')"
					class="btn btn-primary">
					&nbsp;Add <i class="fa fa-phone"></i>&nbsp;
				</button>
			</section>
		</div>
	</fieldset>
	<fieldset>
		<legend>Settings del proveedor</legend>
		<section class="col col-6">
			<label class="select">Selecciona the tipo of proveedor 
			<select id="tipo_proveedor" required name="tipo_proveedor">
					<option value="2">Servicio</option>
			</select>
			</label>
		</section>
		<section class="col col-6">
			<label class="select">Selecciona the empresa <select id="empresa"
				required name="empresa">
								<?php foreach ( $empresa as $key ) {
									echo '<option value="' . $key->id_empresa . '">' . $key->nombre . '</option>';
								} ?>
							</select>
			</label> <a href="#" onclick="new_empresa()">Add empresa <i
				class="fa fa-plus"></i></a>
		</section>
		<section class="col col-6">
			<label class="input">Comisión por producto <input required
				type="text" name="comision" placeholder="%">
			</label>
		</section>
	</fieldset>
	<fieldset>
		<legend>Dirección del proveedor</legend>
		<div id="dir" class="row">
			<section class="col col-6">
				<label class="input"> Street Address <input required
					type="text" name="calle">
				</label>
			</section>
			<section id="colonia" class="col col-6">
				<label class="input"> Ciudad <input type="text" name="colonia">
				</label>
			</section>
			<section id="municipio" class="col col-6">
				<label class="input"> Provincia <input type="text" name="municipio">
				</label>
			</section>
			<section class="col col-6">
				<label class="input"> ZIPCODE <input required
					onkeyup="codpos()" type="text" id="cp" name="cp">
				</label>
			</section>
			<section class="col col-6">
				Country <label class="select"> <select id="pais" required name="pais"
					onChange="ImpuestosPais()">
						<option value="-" selected>-- Seleciona un pais --</option>
													<?php foreach ( $pais as $key ) { ?>
													<option value="<?=$key->Code?>">
														<?=$key->Name?>
													</option>
													<?php }?>
												</select>
				</label>
			</section>
		</div>
	</fieldset>
	<fieldset>
		<legend>Datos fiscales del proveedor</legend>
		<div class="row">
			<section class="col col-6">
				<label class="input">Razón social <input required type="text"
					name="razon">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">CURP <input required type="text" name="curp">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">RFC <input required type="text" name="rfc">
				</label>
			</section>
			<section class="col col-6">
				Regimen fiscal <label class="select"> <select class="custom-scroll"
					name="regimen">
											<?php foreach ($regimen as $key){?>
											<option value="<?=$key->id_regimen?>">
												<?=$key->abreviatura." ".$key->descripcion?></option>
											<?php }?>
										</select>
				</label>
			</section>
			<section class="col col-6">
				Zona <label class="select"> <select class="custom-scroll"
					name="zona">
								<?php foreach ($zona as $key){?>
								<option value="<?=$key->id_zona?>">
									<?=$key->descripcion?></option>
									<?php }?>
								</select>
				</label>
			</section>
		</div>
		<div class="row">
			<div id="cuenta" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<section class="col col-6">
					Banco <label class="select"> <select class="custom-scroll"
						name="banco[]" id="banco" required>
												<?php foreach ($bancos as $key){?>
												<option value="<?=$key->id_banco ?>">
													<?=$key->descripcion?></option>
												<?php }?>
											</select>
					</label>
				</section>
				<section class="col col-6">
					<label class="input">Cuenta <input id="cuenta" required
						name="Cuenta[]" placeholder="02112612345678901" type="text">
					</label>
				</section>
			</div>
			<section class="col col-6">
				<button type="button" onclick="agregar_cuenta()"
					class="btn btn-primary">&nbsp;Add cuenta &nbsp;</button>
			</section>
		</div>
	</fieldset>
	<fieldset>
		<legend>Datos of cobro</legend>
		<div class="row">
			<section class="col col-6">
				<label class="input">Condiciones of pago <input required type="text"
					name="condicion_pago">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Tiempo promedio of entrega <input required
					type="text" name="promedio_entrega" placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Tiempo of entrega of documentación <input
					required type="text" name="promedio_entrega_documentacion"
					placeholder="En días">
				</label>
			</section>
		</div>
	</fieldset>
	<fieldset>
		<legend>Credito</legend>
		<div class="row">
			<section class="col col-6">
				<label class="input">Plazo of pago <input required type="number"
					min="0" name="plazo_pago" placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Plazo of suspención <input required
					type="number" min="0" name="plazo_suspencion" placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Plazo of suspención of firma <input required
					type="number" min="0" name="plazo_suspencion_firma"
					placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Interes moratorio <input required type="number"
					min="0" name="interes_moratorio" placeholder="En %">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Día of corte <input required type="number"
					min="0" name="dia_corte" placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="input">Día of pago <input required type="number"
					min="0" name="dia_pago" placeholder="En días">
				</label>
			</section>
			<section class="col col-6">
				<label class="select">Impuesto <select name="impuesto" id="impuesto">
									<?php foreach ($paisImpuesto as $key){?>
									<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>
									<?php }?>
								</select>
				</label>
			</section>
			<section class="col col-6">
				Credito autorizado
				<div class="inline-group">
					<label class="radio"> <input type="radio" value="1"
						name="credito_autorizado" checked> <i></i>Si
					</label> <label class="radio"> <input type="radio" value="0"
						name="credito_autorizado"> <i></i>No
					</label>
				</div>
			</section>
			<section class="col col-6">
				Credito suspendido
				<div class="inline-group">
					<label class="radio"> <input type="radio" value="1"
						name="credito_suspendido"> <i></i>Si
					</label> <label class="radio"> <input type="radio" value="0"
						name="credito_suspendido" checked> <i></i>No
					</label>
				</div>
			</section>
		</div>
	</fieldset>

</form>
<script type="text/javascript">
function nueva_empresa()
{
	bootbox.dialog({
		message: '<form id="form_empresa" method="post" class="smart-form">'
					+'<fieldset>'
						+'<legend>Information of cuenta</legend>'
						+'<section id="usuario" class="col col-6">'
							+'<label class="input">Razón social'
								+'<input required type="text" name="nombre" placeholder="Empresa">'
							+'</label>'
						+'</section>'
						+'<section id="usuario" class="col col-6">'
							+'<label class="input">Correo electrónico'
								+'<input required type="email" name="email">'
							+'</label>'
						+'</section>'
						+'<section id="usuario" class="col col-6">'
							+'<label class="input">Sítio web'
								+'<input required type="url" name="site">'
							+'</label>'
						+'</section>'
						+'<section class="col col-6">Regimen fiscal'
				            +'<label class="select">'
				                +'<select class="custom-scroll" name="regimen">'
				                    +'<?php foreach ($regimen as $key){?>'
				                        +'<option value="<?=$key->id_regimen?>">'
				                            +'<?=$key->abreviatura." ".$key->descripcion?></option>'
				                        +'<?php }?>'
				                +'</select>'
				            +'</label>'
				        +'</section>'
					+'</fieldset>'
					+'<fieldset>'
						+'<legend>Dirección of the empresa</legend>'
							+'<div id="dir" class="row">'
								+'<section class="col col-6">'
									+'Country'
									+'<label class="select">'
										+'<select id="pais" required name="pais">'
										+'<?php foreach ($pais as $key){?>'
											+'<option value="<?=$key->Code?>">'
												+'<?=$key->Name?>'
											+'</option>'
										+'<?php }?>'
										+'</select>'
									+'</label>'
								+'</section>'
								+'<section class="col col-6">'
									+'<label class="input">'
										+'ZIPCODE'
										+'<input required  type="text" id="cp" name="cp">'
									+'</label>'
								+'</section>'
								+'<section class="col col-6">'
									+'<label class="input">'
										+'Dirección domicilio'
										+'<input required type="text" name="calle">'
									+'</label>'
								+'</section>'
								+'<section class="col col-6">'
									+'<label class="input">'
										+'Número interior'
										+'<input required type="text" name="interior">'
									+'</label>'
								+'</section>'
								+'<section class="col col-6">'
									+'<label class="input">'
										+'Número exterior'
										+'<input required type="text" name="exterior">'
									+'</label>'
								+'</section>'
								+'<section id="colonia" class="col col-6">'
									+'<label class="input">'
										+'Ciudad'
										+'<input type="text" name="colonia" >'
									+'</label>'
								+'</section>'
								+'<section id="municipio" class="col col-6">'
									+'<label class="input">'
										+'Provincia'
										+'<input type="text" name="municipio" >'
									+'</label>'
								+'</section>'
							+'</div>'
						+'</fieldset>'
				+'</form>',
		title: "newEmpresa",
		buttons: {
			submit: {
			label: "Accept",
			className: "btn-success",
			callback: function() {

					$.ajax({
						type: "POST",
						url: "/bo/admin/new_empresa",
						data: $("#form_empresa").serialize()
					})
					.done(function( msg )
					{
						var empresa = JSON.parse(msg);	
						$("#empresa").append("<option value="+empresa['id']+">"+empresa['nombre']+"</option>");
						$("#empresa").val(empresa['id']);
						bootbox.dialog({
						message: "Se Added the empresa",
						title: 'Empresa',
						buttons: {
							success: {
							label: "Accept",
							className: "btn-success",
							callback: function() {
									}
								}
							}
						})//fin done ajax

					});//Fin callback bootbox

				}
			},
				danger: {
				label: "Cancel",
				className: "btn-danger",
				callback: function() {

					}
			}
		}
	})
}
</script>


