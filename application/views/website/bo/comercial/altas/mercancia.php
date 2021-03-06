
<!-- MAIN CONTENT -->
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
				
				<!-- PAGE HEADER -->
				<i class="fa-fw fa fa-pencil-square-o"></i> 
				<a href="/bo/comercial">Commercial</a>
				<!--<span>>
					<a href="/bo/admin">Módulo Directives</a>
				</span>-->
				<span>>
					Mercancia
				</span>
			</h1>
		</div>
	</div>
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">
			<!-- nueva COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false"	>
					
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Mercancia</h2>				
						
					</header>

					<!-- widget div-->
					<div>
						<div class="widget-body">
							<form method="POST" enctype="multipart/form-data"  action="/bo/admin/new_mercancia" id="mercancia" class="smart-form" novalidate="novalidate">
								<fieldset>
									<legend>Alta of mercancía</legend>
									<section class="col col-3" id="b_persona">
										Tipo of mercancia
										<label class="select">
											<select onchange="formulario()" name="tipo_mercancia" id="tipo_mercancia" required name="">
												<option value="1">Producto</option>
												<option value="2">Servicio</option>
												<option value="3">Promocion</option>
											</select>
										</label>
									</section>
								</fieldset>
								<fieldset>
									<legend>Datos <span id="tipo_mercancia_txt">del producto</span></legend>
									<div id="form_mercancia">
										<div class="row">
											<fieldset>
												<section class="col col-2">
													<label class="input">
														Nombre
														<input required type="text" id="nombre_p" name="nombre">
													</label>
												</section>
												<section class="col col-2">
													<label class="input">
														Concepto
														<input required type="text" id="concepto" name="concepto">
													</label>
												</section>
												<section class="col col-2">
													<label class="input">
														Marca
														<input type="text" name="marca" id="marca">
													</label>
												</section>
												<section class="col col-2">
													<label class="input">
														Código of barras
														<input type="text" name="codigo_barras" id="codigo_barras">
													</label>
												</section>
												<section class="col col-3">Grupo
													<label class="select">
														<select name="grupo">
															<?php foreach ($grupo as $key)
															{?>
															<option value="<?=$key->id_grupo?>"><?=$key->descripcion?></option>	
															<?php }?>
														</select>
													</label>
													<a href="#" onclick="new_grupo()">Add grupo <i class="fa fa-plus"></i></a>
													<a href="#" class="pull-right" onclick="kill_grupo()">Eliminar grupo <i class="fa fa-minus"></i></a>
												</section>
												<div>
													<section style="padding-left: 0px;" class="col col-6">
														Descripcion
														<textarea name="descripcion" style="max-width: 96%" id="mymarkdown"></textarea>
													</section>
													<section id="imagenes" class="col col-6">
														<label class="label">Imágen</label>
														<div class="input input-file">
															<span class="button">
																<input id="img" name="img[]" onchange="this.parentNode.nextSibling.value = this.value" type="file" multiple>Buscar</span><input id="imagen_mr" placeholder="Add alguna imágen" readonly="" type="text">
															</div>
															<small>Para cargar múltiples archivos, presione the tecla ctrl & sin soltar selecione sus archivos.<br /><cite title="Source Title">Para ver los archivos que va a cargar, deje the puntero sobre the boton of "Buscar"</cite></small>
														</section>
													</div>
												</fieldset>
												<fieldset>
													<legend>Fisicos</legend>
													<section class="col col-2">
														<label class="input">
															Peso
															<input required type="number" min="0" name="peso" id="peso">
														</label>
													</section>
													<section id="colonia" class="col col-2">
														<label class="input">
															Alto
															<input type="number" min="0" name="alto" id="alto">
														</label>
													</section>
													<section id="municipio" class="col col-2">
														<label class="input">
															Ancho
															<input type="number" min="0" name="ancho" id="ancho">
														</label>
													</section>
													<section id="municipio" class="col col-2">
														<label class="input">
															Profundidad
															<input type="number" min="0" name="profundidad" id="profundidad">
														</label>
													</section>
													<section id="municipio" class="col col-2">
														<label class="input">
															Diametro
															<input type="number" min="0" name="diametro" id="diametro">
														</label>
													</section>
												</fieldset>
												<fieldset id="moneda_field">
													<legend>Moneda & Country</legend>
													<section class="col col-2">
														<label class="input">
															Cantidad mínima of venta
															<input type="text" name="min_venta" id="min_venta">
														</label>
													</section>
													<section class="col col-2">
														<label class="input">
															Cantidad máxima of venta
															<input type="text" name="max_venta" id="max_venta">
														</label>
													</section>
													<section class="col col-2">
														<label class="input">
															Costo real
															<input type="text" name="real" id="real">
														</label>
													</section>
													<section class="col col-2">
														<label class="input">
															Costo distribuidores
															<input type="text" name="costo" id="costo">
														</label>
													</section>
													<section class="col col-2">
														<label class="input">
															Costo publico
															<input type="text" name="costo_publico" id="costo_publico">
														</label>
													</section>
													<section class="col col-2">
														<label class="input">
															Tiempo mínimo of entrega
															<input placeholder="En días" type="text" name="entrega" id="entrega">
														</label>
													</section>
													
													<section class="col col-3">Proveedor
														<label class="select">
															<select name="proveedor">
																<?php foreach ($proveedores as $key)
																{?>
																<option value="<?=$key->id_usuario?>"><?=$key->nombre." ".$key->apellido?> comisión: %<?=$key->comision?></option>	
																<?php }?>
															</select>
														</label>
													</section>
													
													<section class="col col-3">Country of the mercancía
														<label class="select">
															<select id="pais" required name="pais">
																<?php foreach ($pais as $key)
																{?>
																<option value="<?=$key->Code?>">
																	<?=$key->Name?>
																</option>
																<?php }?>
															</select>
														</label>
													</section>
													<section class="col col-3">
														<label class="input">
															Points of commissions
															<input type="number" min="1" max="" name="puntos_com" id="puntos_com">
														</label>
													</section>
													<section class="col col-3">Impuesto
														<label class="select">
															<select name="id_impuesto[]">
																<?php foreach ($impuesto as $key)
																{?>
																<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>	
																<?php }?>
															</select>
														</label>
														<a href="#" onclick="add_impuesto()">Add impuesto<i class="fa fa-plus"></i></a>
														<a href="#" clas="pull-right" onclick="new_impuesto()">Nuevo impuesto<i class="fa fa-plus"></i></a>
														<a href="#" onclick="kill_impuesto()">Eliminar impuesto<i class="fa fa-plus"></i></a>
													</section>
												</fieldset>
												<fieldset>
													<legend>Extra</legend>
													<section class="col col-2">Requiere instalación
														<div class="inline-group">
															<label class="radio">
																<input type="radio" value="1" name="instalacion" checked="">
																<i></i>Si</label>
																<label class="radio">
																	<input type="radio" value="0" name="instalacion">
																	<i></i>No</label>
																</div>
															</section>
															<section class="col col-2">Requiere especificación
																<div class="inline-group">
																	<label class="radio">
																		<input type="radio" value="1" name="especificacion" checked="">
																		<i></i>Si</label>
																		<label class="radio">
																			<input type="radio" value="0" name="especificacion">
																			<i></i>No</label>
																		</div>
																	</section>
																	<section class="col col-2">Requiere producción
																		<div class="inline-group">
																			<label class="radio">
																				<input type="radio" value="1" name="produccion" checked="">
																				<i></i>Si</label>
																				<label class="radio">
																					<input type="radio" value="0" name="produccion">
																					<i></i>No</label>
																				</div>
																			</section>
																			<section class="col col-2">Producto of importación
																				<div class="inline-group">
																					<label class="radio">
																						<input type="radio" value="1" name="importacion" checked="">
																						<i></i>Si</label>
																						<label class="radio">
																							<input type="radio" value="0" name="importacion">
																							<i></i>No</label>
																						</div>
																					</section>
																					<section class="col col-2">Producto of sobrepedido
																						<div class="inline-group">
																							<label class="radio">
																								<input type="radio" value="1" name="sobrepedido" checked="">
																								<i></i>Si</label>
																								<label class="radio">
																									<input type="radio" value="0" name="sobrepedido">
																									<i></i>No</label>
																								</div>
																							</section>
																						</fieldset>
																					</div>
																				</div>
																			</fieldset>
																			<footer>
																				<button type="submit" class="btn btn-primary">
																					Agregar
																				</button>
																			</footer>
																		</form>
																	</div>

																</div>
																<!-- end widget div -->
															
														</article>
														<!-- END COL -->
													</div>

												</section>
												<!-- end widget grid -->
											</div>
											<!-- END MAIN CONTENT -->
											<script src="/template/js/plugin/dropzone/dropzone.min.js"></script>
											<script src="/template/js/plugin/markdown/markdown.min.js"></script>
											<script src="/template/js/plugin/markdown/to-markdown.min.js"></script>
											<script src="/template/js/plugin/markdown/bootstrap-markdown.min.js"></script>
											<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
											<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
											<script src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
											<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
											<script src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
											<script src="/template/js/validacion.js"></script>
											<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {
	
	$("#mymarkdown").markdown({
		autofocus:false,
		savable:false
	})


	/* BASIC ;*/
	var responsiveHelper_dt_basic = undefined;
	var responsiveHelper_datatable_fixed_column = undefined;
	var responsiveHelper_datatable_col_reorder = undefined;
	var responsiveHelper_datatable_tabletools = undefined;

	var breakpointDefinition = {
		tablet : 1024,
		phone : 480
	};

	$('#dt_basic').dataTable({
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
		"t"+
		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
		"autoWidth" : true,
		"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = nueva ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */

	/* BASIC ;*/
	var responsiveHelper_dt_basic = undefined;
	var responsiveHelper_datatable_fixed_column = undefined;
	var responsiveHelper_datatable_col_reorder = undefined;
	var responsiveHelper_datatable_tabletools = undefined;

	var breakpointDefinition = {
		tablet : 1024,
		phone : 480
	};

	$('#dt_basic_paquete').dataTable({
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
		"t"+
		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
		"autoWidth" : true,
		"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = nueva ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */

	pageSetUp();

})
function nueva_user()
{
	var ids=nuevaArray(
		"#username",
		"#email",
		"#password",
		"#confirm_password",
		"#nombre",
		"#apellido",
		"#datepicker",
		"#pais",
		"#cp",
		"#calle"
		);
	var mensajes=nuevaArray(
		"Por favor ingresa un nombre of usuario",
		"Por favor ingresa un correo electronico",
		"Por favor ingresa una Password",
		"Por favor confirma the Password",
		"Por favor ingresa tu nombre",
		"Por favor ingresa tu apellido",
		"Por favor ingresa tu fecha of nacimineto",
		"Por favor especifica tu pais",
		"Por favor ingresa tu codigo postal",
		"Por favor ingresa tu dirección"
		);
	var id_ml=nuevaArray(
		"#email"
		);
	var eml_mns=nuevaArray(
		"Por favor ingrese un correo valido"
		);
	var psswrds=nuevaArray(
		"#password",
		"#confirm_password"
		);
	var id_tamano=Array(
		"#cp"
		);
	var mensaje_tamano=Array(
		"El codigo postal debe of tener al menos 5 caracteres"
		);
	var tamano_min=Array(
		"5"
		);
	var tamano_max=Array(
		"10"
		);
	var id_fecha=Array(
		"#datepicker"
		);
	var mensaje_fecha=Array(
		"El formato of al fecha es incorrecto (aaaa-mm-dd)"
		);
	var ids_esp=nuevaArray(
		"#username"
		);
	var mensajes_esp=nuevaArray(
		"El nombre of usuario no puede contener espacios on blanco"
		);
	
	var validacion=valida_vacios(ids,mensajes);
	var val_espacios=valida_espacios(ids_esp,mensajes_esp);
	var val_email=valida_correo(id_ml,eml_mns);
	var val_psswrds=valida_psswrds(psswrds,"Las Passwords deben of ser iguales");
	var val_tamano=valida_tamano(id_tamano,tamano_min,tamano_max,mensaje_tamano);
	var val_fecha=valida_fecha(id_fecha,mensaje_fecha);
	if(val_espacios&&validacion&&val_email&&val_psswrds&&val_tamano&&val_fecha)
	{
		$("#afiliar").remove();
		$("#footer").append('<div class="progress progress-sm progress-striped active"><div id="progress" class="progress-bar bg-color-darken"  role="progressbar" style=""></div></div>');
		$.ajax({
			type: "POST",
			url: "/auth/register",
			data: $('#register').serialize()
		})
		.done(function( msg ) {
			$("#progress").attr('style','width: 40%');
			var email=$("#email").val();
			$("#checkout-form").append("<input value='"+email+"' type='hidden' name='mail_important'>");
			$.ajax({
				type: "POST",
				url: "/bo/admin/new_user",
				data: $('#checkout-form').serialize()
			})
			.done(function( msg ) {
				$("#progress").attr('style','width: 100%');
				bootbox.dialog({
					message: "El usuario ha sido registrado",
					title: "Attention",
					buttons: {
						success: {
							label: "Ok!",
							className: "btn-success",
							callback: function() {
								location.href="";
							}
						}
					}
				});
			});
		});
	}	
}

function codpos()
{
	var pais = $("#pais").val();
	if(pais=="MEX")
	{
		var cp=$("#cp").val();
		$.ajax({
			type: "POST",
			url: "/bo/admin/cp",
			data: {cp: cp},
		})
		.done(function( msg )
		{
			$("#colonia").remove();
			$("#municipio").remove();
			$("#estado").remove();
			$("#dir").append(msg);
		})
	}
}
function codpos_proveedor()
{
	var pais = $("#pais_proveedor").val();
	if(pais=="MEX")
	{
		var cp=$("#cp_proveedor").val();
		$.ajax({
			type: "POST",
			url: "/bo/admin/cp_proveedor",
			data: {cp: cp},
		})
		.done(function( msg )
		{
			$("#colonia_proveedor").remove();
			$("#municipio_proveedor").remove();
			$("#estado_proveedor").remove();
			$("#dir_proveedor").append(msg);
		})
	}
}
function use_username()
{
	$("#msg_usuario").remove();
	var username=$("#username").val();

	$.ajax({
		type: "POST",
		url: "/bo/admin/use_username",
		data: {username: username},
	})
	.done(function( msg )
	{
		$("#usuario").append("<p id='msg_usuario'>"+msg+"</msg>");
	});
}
function use_mail()
{
	$("#msg_correo").remove();
	var mail=$("#email").val();
	$.ajax({
		type: "POST",
		url: "/bo/admin/use_mail",
		data: {mail: mail},
	})
	.done(function( msg )
	{
		$("#correo").append("<p id='msg_correo'>"+msg+"</msg>")
	});
}
function agregar(tipo)
{
	if(tipo==1)
	{
		$("#tel").append("<section class='col col-3'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	}
	else
	{
		$("#tel").append("<section class='col col-3'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	}
}
function agregar1(tipo)
{
	if(tipo==1)
	{
		$("#tel1").append("<section class='col col-3'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	}
	else
	{
		$("#tel1").append("<section class='col col-3'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label></section>");
	}
}
$(function()
{
	$( "#datepicker" ).datepicker({
		changeMonth: true,
		numberOfMonths: 2,
		dateFormat:"yy-mm-dd",
		defaultDate: "1970-01-01",
		changeYear: true
	});
});
$(function()
{
	$( "#datepicker1" ).datepicker({
		changeMonth: true,
		numberOfMonths: 2,
		dateFormat:"yy-mm-dd",
		defaultDate: "1970-01-01",
		changeYear: true
	});
});
function formulario()
{
	var tipo=$("#tipo_mercancia").val();
	if(tipo==1)
	{
		$("#tipo_mercancia_txt").empty();
		$("#tipo_mercancia_txt").append("del producto");
		$("#form_mercancia").empty();
		$("#form_mercancia").append('<div class="row">'
			+'<fieldset>'
			+'<section class="col col-2">'
			+'<label class="input">Nombre'
			+'<input required type="text" id="nombre_p" name="nombre">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Concepto'
			+'<input required type="text" id="concepto" name="concepto">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Marca'
			+'<input type="text" name="marca" id="marca">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Código of barras'
			+'<input type="text" name="codigo_barras">'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">Grupo'
			+'<label class="select">'
			+'<select name="grupo">'
			+'<?php foreach ($grupo as $key){?>'
			+'<option value="<?=$key->id_grupo?>"><?=$key->descripcion?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'<a href="#" onclick="new_grupo()">Add grupo <i class="fa fa-plus"></i></a>'
			+'<a href="#" class="pull-right" onclick="kill_grupo()">Eliminar grupo <i class="fa fa-minus"></i></a>'
			+'</section>'
			+'<div>'
			+'<section style="padding-left: 0px;" class="col col-6">Descripcion'
			+'<textarea name="descripcion" style="max-width: 96%" id="mymarkdown"></textarea>'
			+'</section>'
			+'<section id="imagenes" class="col col-6">'
			+'<label class="label">Imágen</label>'
			+'<div class="input input-file"><span class="button"><input id="img" name="img[]" onchange="this.parentNode.nextSibling.value=this.value" type="file" multiple>Buscar</span>'
			+'<input id="imagen_mr" placeholder="Add alguna imágen" readonly="" type="text">'
			+'</div>'
			+'<small>Para cargar múltiples archivos, presione the tecla ctrl & sin soltar selecione sus archivos.<br/><cite title="Source Title">Para ver los archivos que va a cargar, deje the puntero sobre the boton of "Buscar"</cite></small>'
			+'</section>'
			+'</div>'
			+'</fieldset>'
			+'<fieldset>'
			+'<legend>Fisicos</legend>'
			+'<section class="col col-2">'
			+'<label class="input">Peso'
			+'<input required type="number" min="0" name="peso">'
			+'</label>'
			+'</section>'
			+'<section id="colonia" class="col col-2">'
			+'<label class="input">Alto'
			+'<input type="number" min="0" name="alto">'
			+'</label>'
			+'</section>'
			+'<section id="municipio" class="col col-2">'
			+'<label class="input">Ancho'
			+'<input type="number" min="0" name="ancho">'
			+'</label>'
			+'</section>'
			+'<section id="municipio" class="col col-2">'
			+'<label class="input">Profundidad'
			+'<input type="number" min="0" name="profundidad">'
			+'</label>'
			+'</section>'
			+'<section id="municipio" class="col col-2">'
			+'<label class="input">Diametro'
			+'<input type="number" min="0" name="diametro">'
			+'</label>'
			+'</section>'
			+'</fieldset>'
			+'<fieldset id="moneda_field">'
			+'<legend>Moneda & Country</legend>'
			+'<section class="col col-2">'
			+'<label class="input">Cantidad mínima of venta'
			+'<input type="text" name="min_venta">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Cantidad máxima of venta'
			+'<input type="text" name="max_venta">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Costo distribuidores'
			+'<input type="text" name="costo">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">'
			+'Costo real'
			+'<input type="text" name="real" >'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Costo publico'
			+'<input type="text" name="costo_publico">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">'
			+'Tiempo mínimo of entrega'
			+'<input placeholder="En días" type="text" name="entrega" >'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">Impuesto'
			+'<label class="select">'
			+'<select name="id_impuesto[]">'
			+'<?php foreach ($impuesto as $key){?>'
			+'<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>'	
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'<a href="#" onclick="add_impuesto()">Add impuesto<i class="fa fa-plus"></i></a>'
			+'<a href="#" clas="pull-right" onclick="new_impuesto()">Nuevo impuesto<i class="fa fa-plus"></i></a>'
			+'<a href="#" onclick="kill_impuesto()">Eliminar impuesto<i class="fa fa-plus"></i></a>'
			+'</section>'
			+'<section class="col col-3">Proveedor'
			+'<label class="select">'
			+'<select name="proveedor">'
			+'<?php foreach ($proveedores as $key){?>'
			+'<option value="<?=$key->id_usuario?>">'
			+'<?=$key->nombre." ".$key->apellido?> comisión: %'
			+'<?=$key->comision?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">Country del producto'
			+'<label class="select">'
			+'<select id="pais" required name="pais">'
			+'<?php foreach ($pais as $key){?>'
			+'<option value="<?=$key->Code?>">'
			+'<?=$key->Name?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">'
			+'<label class="input">'
			+'Points of commissions'
			+'<input type="number" min="1" max="" name="puntos_com" id="puntos_com">'
			+'</label>'
			+'</section>'
			+'</fieldset>'
			+'<fieldset>'
			+'<legend>Extra</legend>'
			+'<section class="col col-2">Requiere instalación'
			+'<div class="inline-group">'
			+'<label class="radio">'
			+'<input type="radio" value="1" name="instalacion" checked=""><i></i>Si</label>'
			+'<label class="radio">'
			+'<input type="radio" value="0" name="instalacion"><i></i>No</label>'
			+'</div>'
			+'</section>'
			+'<section class="col col-2">Requiere especificación'
			+'<div class="inline-group">'
			+'<label class="radio">'
			+'<input type="radio" value="1" name="especificacion" checked=""><i></i>Si</label>'
			+'<label class="radio">'
			+'<input type="radio" value="0" name="especificacion"><i></i>No</label>'
			+'</div>'
			+'</section>'
			+'<section class="col col-2">Requiere producción'
			+'<div class="inline-group">'
			+'<label class="radio">'
			+'<input type="radio" value="1" name="produccion" checked=""><i></i>Si</label>'
			+'<label class="radio">'
			+'<input type="radio" value="0" name="produccion"><i></i>No</label>'
			+'</div>'
			+'</section>'
			+'<section class="col col-2">Producto of importación'
			+'<div class="inline-group">'
			+'<label class="radio">'
			+'<input type="radio" value="1" name="importacion" checked=""><i></i>Si</label>'
			+'<label class="radio">'
			+'<input type="radio" value="0" name="importacion"><i></i>No</label>'
			+'</div>'
			+'</section>'
			+'<section class="col col-2">Producto of sobrepedido'
			+'<div class="inline-group">'
			+'<label class="radio">'
			+'<input type="radio" value="1" name="sobrepedido" checked=""><i></i>Si</label>'
			+'<label class="radio">'
			+'<input type="radio" value="0" name="sobrepedido"><i></i>No</label>'
			+'</div>'
			+'</section>'
			+'</fieldset>'
			+'</div>');

$("#mymarkdown").markdown({
	autofocus:false,
	savable:false
})
}
if(tipo==2)
{
	$("#tipo_mercancia_txt").empty();
	$("#tipo_mercancia_txt").append("del servicio");
	$("#form_mercancia").empty();
	$("#form_mercancia").append('<div class="row">'
		+'<fieldset>'
		+'<section class="col col-2">'
		+'<label class="input">Nombre'
		+'<input required type="text" id="nombre_s" name="nombre">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Concepto'
		+'<input required type="text" id="concepto" name="concepto">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Fecha of inicio'
		+'<input required type="text" name="fecha_inicio" id="startdate" /> </label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Fecha of termino'
		+'<input type="text" name="fecha_fin" id="finishdate" /> </label>'
		+'</section>'
		+'<div>'
		+'<section style="padding-left: 0px;" class="col col-6">Descripcion'
		+'<textarea name="descripcion" style="max-width: 96%" id="mymarkdown"></textarea>'
		+'</section>'
		+'<section id="imagenes" class="col col-6">'
		+'<label class="label">Imágen</label>'
		+'<div class="input input-file"><span class="button"><input id="img" name="img[]" onchange="this.parentNode.nextSibling.value=this.value" type="file" multiple>Buscar</span>'
		+'<input id="imagen_mr" placeholder="Add alguna imágen" readonly="" type="text">'
		+'</div><small>Para cargar múltiples archivos, presione the tecla ctrl & sin soltar selecione sus archivos.<br/><cite title="Source Title">Para ver los archivos que va a cargar, deje the puntero sobre the boton of "Buscar"</cite></small>'
		+'</section>'
		+'</div>'
		+'</fieldset>'
		+'<fieldset id="moneda_field">'
		+'<legend>Moneda & Country</legend>'
		+'<section class="col col-2">'
		+'<label class="input">'
		+'Costo real'
		+'<input type="text" name="real" id="real">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Costo distribuidores'
		+'<input type="text" name="costo" id="costo">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Costo publico'
		+'<input type="text" name="costo_publico" id="costo_publico">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">'
		+'Tiempo mínimo of entrega'
		+'<input placeholder="En días" type="text" name="entrega" id="entrega">'
		+'</label>'
		+'</section>'
		+'<section class="col col-3">Impuesto'
		+'<label class="select">'
		+'<select name="id_impuesto[]">'
		+'<?php foreach ($impuesto as $key){?>'
		+'<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>'	
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'<a href="#" onclick="add_impuesto()">Add impuesto<i class="fa fa-plus"></i></a>'
		+'<a href="#" clas="pull-right" onclick="new_impuesto()">Nuevo impuesto<i class="fa fa-plus"></i></a>'
		+'<a href="#" onclick="kill_impuesto()">Eliminar impuesto<i class="fa fa-plus"></i></a>'
		+'</section>'
		+'<section class="col col-3">Proveedor'
		+'<label class="select">'
		+'<select name="proveedor">'
		+'<?php foreach ($proveedores as $key){?>'
		+'<option value="<?=$key->id_usuario?>">'
		+'<?=$key->nombre." ".$key->apellido?> comisión: %'
		+'<?=$key->comision?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-3">Country del producto'
		+'<label class="select">'
		+'<select id="pais" required name="pais">'
		+'<?php foreach ($pais as $key){?>'
		+'<option value="<?=$key->Code?>">'
		+'<?=$key->Name?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-3">'
		+'<label class="input">'
		+'Points of commissions'
		+'<input type="number" min="1" max="" name="puntos_com" id="puntos_com">'
		+'</label>'
		+'</section>'
		+'</fieldset>'
		+'</div>'
		);

$("#mymarkdown").markdown({
	autofocus:false,
	savable:false
})

		// START AND FINISH DATE
		$('#startdate').datepicker({
			changeMonth: true,
			numberOfMonths: 2,
			dateFormat:"yy-mm-dd",
			changeYear: true,
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
			}
		});

		$('#finishdate').datepicker({
			changeMonth: true,
			numberOfMonths: 2,
			dateFormat:"yy-mm-dd",
			changeYear: true,
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#startdate').datepicker('option', 'maxDate', selectedDate);
			}
		});
	}
	if(tipo==3)
	{
		$("#tipo_mercancia_txt").empty();
		$("#tipo_mercancia_txt").append("de the promoción");
		$("#form_mercancia").empty();
		$("#form_mercancia").append('<div class="row">'
			+'<fieldset>'
			+'<section class="col col-3">Tipo of promocion'
			+'<label class="select">'
			+'<select onchange="tipo_promo()" id="tipo" name="tipo">'
			+'<?php foreach ($promo as $key){?>'
			+'<option value="<?=$key->id_promo?>">'
			+'<?=$key->descripcion?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'</fieldset><fieldset>'
			+'<legend>Datos <span id="tipo_mercancia_txt">del producto</span></legend>'
			+'<section class="col col-2">'
			+'<label class="input">Nombre'
			+'<input type="text" name="nombre" id="nombre_pr">'
			+'</label>'
			+'</section>'
			+'<div id="tipo_promo">'

			+'<section class="col col-2">'
			+'<label class="input"><span id="labelextra">Descuento del paquete</span>'
			+'<input id="precio_promo" type="text" name="descuento">'
			+'</label>'
			+'</section><div class="row"><br /></div>'
			+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="prods">'
			+'<section class="col col-8">productos'
			+'<label class="select">'
			+'<select class="custom-scroll"  name="producto[]">'
			+'<option value="0">None</option>'
			+'<?php foreach ($producto as $key){?>'
			+'<option value="<?=$key->id_mercancia?>">'
			+'<?=$key->nombre?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-4">'
			+'<label class="input">Cantidad of productos'
			+'<input type="number" min="1" name="n_productos[]" id="prod_qty">'
			+'</label>'
			+'</section>'
			+'</div>'
			+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="servs">'
			+'<section class="col col-8">Services'
			+'<label class="select">'
			+'<select class="custom-scroll" name="servicio[]">'
			+'<option value="0">None</option>'
			+'<?php foreach ($servicio as $key){?>'
			+'<option value="<?=$key->id_mercancia?>">'
			+'<?=$key->nombre?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-4">'
			+'<label class="input">Cantidad of servicios'
			+'<input type="number" min="1" name="n_servicios[]" id="serv_qty">'
			+'</label>'
			+'</section>'
			+'</div>'
			+'</div>'
			+'</fieldset>'
			+'<div id="agregar" class=" text-center row"><a onclick="new_product()">Add producto <i class="fa fa-plus"></i></a>  <a  onclick="new_service()">Add servicio <i class="fa fa-plus"></i></a></div>'
			+'<div id="moneda"><fieldset id="moneda_field">'
			+'<legend>Moneda & Country</legend>'
			+'<section class="col col-2">'
			+'<label class="input">'
			+'Costo real'
			+'<input type="text" name="real" id="real">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Costo distribuidores'
			+'<input type="text" name="costo" id="costo">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">Costo publico'
			+'<input type="text" name="costo_publico" id="costo_publico">'
			+'</label>'
			+'</section>'
			+'<section class="col col-2">'
			+'<label class="input">'
			+'Tiempo mínimo of entrega'
			+'<input placeholder="En días" type="text" name="entrega" id="entrega">'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">Impuesto'
			+'<label class="select">'
			+'<select name="id_impuesto[]">'
			+'<?php foreach ($impuesto as $key){?>'
			+'<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>'	
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'<a href="#" onclick="add_impuesto()">Add impuesto<i class="fa fa-plus"></i></a>'
			+'<a href="#" clas="pull-right" onclick="new_impuesto()">Nuevo impuesto<i class="fa fa-plus"></i></a>'
			+'<a href="#" onclick="kill_impuesto()">Eliminar impuesto<i class="fa fa-plus"></i></a>'
			+'</section>'
			+'<section class="col col-3">Proveedor'
			+'<label class="select">'
			+'<select name="proveedor">'
			+'<?php foreach ($proveedores as $key){?>'
			+'<option value="<?=$key->id_usuario?>">'
			+'<?=$key->nombre." ".$key->apellido?> comisión: %'
			+'<?=$key->comision?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">Country del producto'
			+'<label class="select">'
			+'<select id="pais" required name="pais">'
			+'<?php foreach ($pais as $key){?>'
			+'<option value="<?=$key->Code?>">'
			+'<?=$key->Name?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-3">'
			+'<label class="input">'
			+'Points of commissions'
			+'<input type="number" min="1" max="" name="puntos_com" id="puntos_com">'
			+'</label>'
			+'</section>'
			+'</fieldset></div>'
			+'<div>'
			+'<section style="padding-left: 0px;" class="col col-6">Descripcion'
			+'<textarea name="descripcion" style="max-width: 96%" id="mymarkdown"></textarea>'
			+'</section>'
			+'<section id="imagenes" class="col col-6">'
			+'<label class="label">Imágen</label>'
			+'<div class="input input-file"><span class="button"><input id="img" name="img[]" onchange="this.parentNode.nextSibling.value=this.value" type="file" multiple>Buscar</span>'
			+'<input id="imagen_mr" placeholder="Add alguna imágen" readonly="" type="text">'
			+'</div><small>Para cargar múltiples archivos, presione the tecla ctrl & sin soltar selecione sus archivos.<br/><cite title="Source Title">Para ver los archivos que va a cargar, deje the puntero sobre the boton of "Buscar"</cite></small>'
			+'</section>'
			+'</div>'
			+'</fieldset>'
			+'</div>');

$("#mymarkdown").markdown({
	autofocus:false,
	savable:false
})
}
}
function tipo_promo()
{
	var tipo=$("#tipo").val();
	if(tipo==1)
	{
		$("#tipo_promo").empty();
		$("#labelextra").empty();
		$("#labelextra").append('Descuento del paquete');
		$('#agregar').append('<a onclick="new_product()">Add producto <i class="fa fa-plus"></i></a>  <a  onclick="new_service()">Add servicio <i class="fa fa-plus"></i></a>');
		$('#precio_promo').attr('name','descuento')
		$("#tipo_promo").append('<section class="col col-2">'
			+'<label class="input"><span id="labelextra">Descuento del paquete</span>'
			+'<input id="precio_promo" type="text" name="descuento">'
			+'</label>'
			+'</section><div class="row"><br /></div>'
			+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="prods">'
			+'<section class="col col-8">productos'
			+'<label class="select">'
			+'<select class="custom-scroll"  name="producto[]">'
			+'<option value="0">None</option>'
			+'<?php foreach ($producto as $key){?>'
			+'<option value="<?=$key->id_mercancia?>">'
			+'<?=$key->nombre?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-4">'
			+'<label class="input">Cantidad of productos'
			+'<input type="number" min="1" name="n_productos[]" id="prod_qty">'
			+'</label>'
			+'</section>'
			+'</div>'
			+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="servs">'
			+'<section class="col col-8">Services'
			+'<label class="select">'
			+'<select class="custom-scroll" name="servicio[]">'
			+'<option value="0">None</option>'
			+'<?php foreach ($servicio as $key){?>'
			+'<option value="<?=$key->id_mercancia?>">'
			+'<?=$key->nombre?></option>'
			+'<?php }?>'
			+'</select>'
			+'</label>'
			+'</section>'
			+'<section class="col col-4">'
			+'<label class="input">Cantidad of servicios'
			+'<input type="number" min="1" name="n_servicios[]" id="serv_qty">'
			+'</label>'
			+'</section>'
			+'</div>');
$("#moneda").append('<fieldset id="moneda_field"> '
	+'<legend>Moneda & Country</legend>'
	+'<section class="col col-2">'
	+'<label class="input">'
	+'Costo real'
	+'<input type="text" name="real" id="real">'
	+'</label>'
	+'</section>'
	+'<section class="col col-2">'
	+'<label class="input">Costo distribuidores'
	+'<input type="text" name="costo" id="costo">'
	+'</label>'
	+'</section>'
	+'<section class="col col-2">'
	+'<label class="input">Costo publico'
	+'<input type="text" name="costo_publico" id="costo_publico">'
	+'</label>'
	+'</section>'
	+'<section class="col col-2">'
	+'<label class="input">'
	+'Tiempo mínimo of entrega'
	+'<input placeholder="En días" type="text" name="entrega" id="entrega">'
	+'</label>'
	+'</section>'
	+'<section class="col col-3">Impuesto'
	+'<label class="select">'
	+'<select name="id_impuesto[]">'
	+'<?php foreach ($impuesto as $key){?>'
	+'<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>'	
	+'<?php }?>'
	+'</select>'
	+'</label>'
	+'<a href="#" onclick="add_impuesto()">Add impuesto<i class="fa fa-plus"></i></a>'
	+'<a href="#" clas="pull-right" onclick="new_impuesto()">Nuevo impuesto<i class="fa fa-plus"></i></a>'
	+'<a href="#" onclick="kill_impuesto()">Eliminar impuesto<i class="fa fa-plus"></i></a>'
	+'</section>'
	+'<section class="col col-3">Proveedor'
	+'<label class="select">'
	+'<select name="proveedor">'
	+'<?php foreach ($proveedores as $key){?>'
	+'<option value="<?=$key->id_usuario?>">'
	+'<?=$key->nombre." ".$key->apellido?> comisión: %'
	+'<?=$key->comision?></option>'
	+'<?php }?>'
	+'</select>'
	+'</label>'
	+'</section>'
	+'<section class="col col-3">Country del producto'
	+'<label class="select">'
	+'<select id="pais" required name="pais">'
	+'<?php foreach ($pais as $key){?>'
	+'<option value="<?=$key->Code?>">'
	+'<?=$key->Name?></option>'
	+'<?php }?>'
	+'</select>'
	+'</label>'
	+'</section>'
	+'<section class="col col-3">'
	+'<label class="input">'
	+'Points of commissions'
	+'<input type="number" min="1" max="" name="puntos_com" id="puntos_com">'
	+'</label>'
	+'</section>'
	+'</fieldset>');
}
if(tipo==2)
{
	$('#precio_promo').attr('name','extra');
	$("#moneda").empty();
	$("#labelextra").empty();
	$("#labelextra").append('Costo extra por regalo');
	$("#tipo_promo").empty();
	$('#agregar').empty();
	$("#tipo_promo").append('<section class="col col-3">Mercancia'
		+'<label class="select">'
		+'<select class="custom-scroll"  name="mercancia">'
		+'<?php foreach ($producto as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'<?php foreach ($servicio as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'<?php foreach ($combinado as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Cantidad of mercancia'
		+'<input type="number" min="1" name="n_mercancia" id="n_mercancia">'
		+'</label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Fecha of inicio'
		+'<input required type="text" name="fecha_inicio" id="startdate" /> </label>'
		+'</section>'
		+'<section class="col col-2">'
		+'<label class="input">Fecha of termino'
		+'<input type="text" name="fecha_fin" id="finishdate" /> </label>'
		+'</section>');

$('#startdate').datepicker({
	changeMonth: true,
	numberOfMonths: 2,
	dateFormat:"yy-mm-dd",
	changeYear: true,
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	onSelect : function(selectedDate) {
		$('#finishdate').datepicker('option', 'minDate', selectedDate);
	}
});

$('#finishdate').datepicker({
	changeMonth: true,
	numberOfMonths: 2,
	dateFormat:"yy-mm-dd",
	changeYear: true,
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	onSelect : function(selectedDate) {
		$('#startdate').datepicker('option', 'maxDate', selectedDate);
	}
});
}
}
function nueva_product()
{
	$('#prods').append('<section class="col col-8">productos'
		+'<label class="select">'
		+'<select class="custom-scroll"  name="producto[]">'
		+'<option value="0">None</option>'
		+'<?php foreach ($producto as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-4">'
		+'<label class="input">Cantidad of productos'
		+'<input type="number" min="1" name="n_productos[]">'
		+'</label>'
		+'</section>');
}
function nueva_service()
{
	$('#servs').append('<section class="col col-8">Services'
		+'<label class="select">'
		+'<select class="custom-scroll" name="servicio[]">'
		+'<option value="0">None</option>'
		+'<?php foreach ($servicio as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-4">'
		+'<label class="input">Cantidad of servicios'
		+'<input type="number" min="1" name="n_servicios[]">'
		+'</label>'
		+'</section>');
}
function nueva_grupo()
{
	bootbox.dialog({
		message: "<label>Nombre del grupo</label><input id='desc'  type='text'/>",
		title: 'Add grupo',
		buttons: {
			success: {
				label: "Agregar",
				className: "btn-success",
				callback: function() {
					var desc=$("#desc").val()
					$.ajax({
						type: "POST",
						url: "/bo/admin/new_grupo",
						data: {descripcion: desc},
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: "El grupo fue añadido con exito",
							title: 'Attention',
							buttons: {
								success: {
									label: "Ok",
									className: "btn-success",
									callback: function() {

									}
								}
							}
						})
						location.href='';
					})
				}
			}
		}
	})
}
function kill_grupo()
{
	bootbox.dialog({
		message: "<form class='smart-form'><label class='select text-center'><select id='imp_sel'><?php foreach($grupo as $grp){?><option value='<?=$grp->id_grupo?>'><?=$grp->descripcion?></option><?php }?></select></label></form>",
		title: 'Eliminar grupo',
		buttons: {
			success: {
				label: "Eliminar",
				className: "btn-danger",
				callback: function() {
					var id_g=$("#imp_sel").val()
					$.ajax({
						type: "POST",
						url: "/bo/admin/kill_grupo",
						data: {id: id_g},
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: "El grupo fue eliminado con exito",
							title: 'Attention',
							buttons: {
								success: {
									label: "Ok",
									className: "btn-success",
									callback: function() {

									}
								}
							}
						})
						location.href='';
					})
				}
			}
		}
	})
}
function nueva_impuesto()
{
	bootbox.dialog({
		message: "<label>Nombre del impuesto</label><input id='desc'  type='text'/><br>br><label>Porcentaje of impuesto</label><input id='porc'  type='text'/>",
		title: 'Add grupo',
		buttons: {
			success: {
				label: "Agregar",
				className: "btn-success",
				callback: function() {
					var desc=$("#desc").val()
					var porc=$("#porc").val()
					$.ajax({
						type: "POST",
						url: "/bo/admin/new_impuesto",
						data: {descripcion: desc, porcentaje: porc},
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: "El impuesto fue añadido con exito",
							title: 'Attention',
							buttons: {
								success: {
									label: "Ok",
									className: "btn-success",
									callback: function() {

									}
								}
							}
						})
						location.href='';
					})
				}
			}
		}
	})
}
function kill_impuesto()
{
	bootbox.dialog({
		message: "<form class='smart-form'><label class='select text-center'><select id='imp_sel'><?php foreach($impuesto as $imp){?><option value='<?=$imp->id_impuesto?>'><?=$imp->descripcion?> (<?=$imp->porcentaje?> %)</option><?php }?></select></label></form>",
		title: 'Eliminar grupo',
		buttons: {
			success: {
				label: "Eliminar",
				className: "btn-danger",
				callback: function() {
					var id_g=$("#imp_sel").val()
					$.ajax({
						type: "POST",
						url: "/bo/admin/kill_impuesto",
						data: {id: id_g},
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: "El impuesto fue eliminado con exito",
							title: 'Attention',
							buttons: {
								success: {
									label: "Ok",
									className: "btn-success",
									callback: function() {

									}
								}
							}
						})
						location.href='';
					})
				}
			}
		}
	})
}
function dato_pais(codigo,nombre)
{

	$.ajax({
		type: "POST",
		url: "/bo/admin/dato_pais",
		data: {pais: codigo},
	})
	.done(function( msg )
	{
		bootbox.dialog({
			message: msg,
			title: nombre,
			buttons: {
				success: {
					label: "Accept",
					className: "btn-success",
					callback: function() {

						$.ajax({
							type: "POST",
							url: "/bo/admin/actualiza_pais",
							data: $("#"+codigo).serialize(),
						})
						.done(function( msg )
						{
							bootbox.dialog({
								message: "Se han actualizado los cambios",
								title: nombre,
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
					label: "Cancel!",
					className: "btn-danger",
					callback: function() {

					}
				}
			}
		})
	});
}
function dato_pais_multiple()
{
	/*bootbox.dialog({
		message: "Espere mientras se procesan los datos",
		title: "Espere",
		timeOut : 1000,
	})//fin done ajax*/

$.ajax({
	type: "POST",
	url: "/bo/admin/dato_pais_multiple",
	data: $("#multiple_pais").serialize(),
})
.done(function( msg )
{
	bootbox.dialog({
		message: msg,
		title: "Editar",
		buttons: {
			success: {
				label: "Accept",
				className: "btn-success",
				callback: function() {
				/*bootbox.dialog({
					message: "Espere mientras se procesan los datos",
					title: "Espere",
				})//fin done ajax*/
	$.each( $('.pais_check:checked'), function( i, val ) {

				  	//alert($(val).val());
				  	$.ajax({
				  		type: "POST",
				  		url: "/bo/admin/actualiza_pais",
				  		data: $("#"+$(val).val()).serialize(),
				  	})
				  	.done(function( msg )
				  	{
					});//Fin callback bootbox

				  });
	bootbox.dialog({
		message: "The changes have been made successfully",
		title: "Prueba",
		buttons: {
			success: {
				label: "Accept",
				className: "btn-success",
				callback: function() {
				}
			}
		}
					})//fin done ajax
}
},
danger: {
	label: "Cancel!",
	className: "btn-danger",
	callback: function() {

	}
}
}
})
});
}
function editar(id_merc)
{
	$.ajax({
		type: "POST",
		url: "/bo/admin/edit_merc",
		data: {id: id_merc},
	})
	.done(function( msg )
	{
		bootbox.dialog({
			message: msg,
			title: 'Edicion',
			buttons: {
				success: {
					label: "Accept",
					className: "btn-success",
					callback: function() {
						$("#update_merc").submit();
					}
				}
			}
	})//fin done ajax
	});//Fin callback bootbox
}
function eliminar(id)
{
	bootbox.dialog({
		message: "Confirm removing (this action cannot undo)",
		title: "Eliminar",
		buttons: {
			success: {
				label: "Accept",
				className: "btn-success",
				callback: function() {

					$.ajax({
						type: "POST",
						url: "/bo/admin/del_merc",
						data: {id: id},
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: "Merchandise has been removed",
							title: 'Alerta',
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
				label: "Cancel!",
				className: "btn-danger",
				callback: function() {

				}
			}
		}
	})
}
function estatus(tipo,id)
{
	if (tipo==1){
		bootbox.dialog({
			message: "Confirme que desea activar on carrito of compra",
			title: "Activar",
			buttons: {
				success: {
					label: "Accept",
					className: "btn-success",
					callback: function() {

						$.ajax({
							type: "POST",
							url: "/bo/admin/estado_mercancia",
							data: {tipo: tipo, id: id},
						})
						.done(function( msg )
						{
							bootbox.dialog({
								message: "Se ha activado the producto",
								title: 'Activar',
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
					label: "Cancel!",
					className: "btn-danger",
					callback: function() {

					}
				}
			}
		})
	}
	else
	{
		bootbox.dialog({
			message: "Confirme que desea desactivar on carrito of compra",
			title: "Descativar",
			buttons: {
				success: {
					label: "Accept",
					className: "btn-success",
					callback: function() {

						$.ajax({
							type: "POST",
							url: "/bo/admin/estado_mercancia",
							data: {tipo: tipo, id: id},
						})
						.done(function( msg )
						{
							bootbox.dialog({
								message: "Se ha desactivado the producto",
								title: 'Descativar',
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
					label: "Cancel!",
					className: "btn-danger",
					callback: function() {

					}
				}
			}
		})
	}
}
function nueva_empresa()
{
	bootbox.dialog({
		message: '<form id="form_empresa" method="post" action="/bo/admin/new_empresa" class="smart-form">'
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
		title: "Editar",
		buttons: {
			submit: {
				label: "Accept",
				className: "btn-success",
				callback: function() {

					$.ajax({
						type: "POST",
						url: "/bo/admin/new_empresa",
						data: $("#form_empresa").serialize(),
					})
					.done(function( msg )
					{
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
				label: "Cancel!",
				className: "btn-danger",
				callback: function() {

				}
			}
		}
	})
}
function agregar_cuenta()
{
	
	$("#cuenta").append('<section class="col col-3">'
		+'<label class="input">CLABE'
		+'<input required name="clabe[]" placeholder="02112312345678901" type="text">'
		+'</label>'
		+'</section>');
}
function check_keyword()
{
	$("#msg_key").remove();
	$("#key_").append('<i id="ajax_" class="icon-append fa fa-spinner fa-spin"></i>');

	var keyword=$("#keyword").val();
	$.ajax({
		type: "POST",
		url: "/ov/perfil_red/use_keyword",
		data: {keyword: keyword},
	})
	.done(function( msg )
	{
		$("#msg_key").remove();
		$("#key").append("<p id='msg_key'>"+msg+"</msg>");
		$("#ajax_").remove();
	});
}
function check_keyword_co()
{
	$("#msg_key_co").remove();
	$("#key_1").append('<i id="ajax_1" class="icon-append fa fa-spinner fa-spin"></i>');
	var keyword=$("#keyword_co").val();
	$.ajax({
		type: "POST",
		url: "/ov/perfil_red/use_keyword",
		data: {keyword: keyword},
	})
	.done(function( msg )
	{
		$("#msg_key_co").remove();
		$("#key_co").append("<p id='msg_key_co'>"+msg+"</msg>");
		$("#ajax_1").remove();
	});
}
function nueva_proveedor()
{
	$.ajax({
		type: "POST",
		url: "/auth/register",
		data: $('#register1').serialize()
	})
	.done(function( msg ) {
		var email=$("#email1").val();
		$("#proveedor").append("<input value='"+email+"' type='hidden' name='mail_important'>");
		$.ajax({
			type: "POST",
			url: "/bo/admin/new_proveedor",
			data: $('#proveedor').serialize()
		})
		.done(function( msg ) {
			bootbox.dialog({
				message: "Se ha afiliado al usuario",
				title: "Attention",
				buttons: {
					success: {
						label: "Ok!",
						className: "btn-success",
						callback: function() {
							location.href="";
						}
					}
				}
			});
		});
	});
}
function use_username1()
{
	$("#msg_usuario1").remove();
	var username=$("#username1").val();
	$.ajax({
		type: "POST",
		url: "/bo/admin/use_username",
		data: {username: username},
	})
	.done(function( msg )
	{
		$("#usuario1").append("<p id='msg_usuario1'>"+msg+"</msg>")
	});
}
function use_mail1()
{
	$("#msg_correo1").remove();
	var mail=$("#email1").val();
	$.ajax({
		type: "POST",
		url: "/bo/admin/use_mail",
		data: {mail: mail},
	})
	.done(function( msg )
	{
		$("#correo1").append("<p id='msg_correo1'>"+msg+"</msg>")
	});
}
function add_impuesto()
{
	var code=	'<section class="col col-3">Impuesto'
	+'<label class="select">'
	+'<select name="id_impuesto[]">'
	<?php foreach ($impuesto as $key)
	{
		echo "+'<option value=".$key->id_impuesto.">".$key->descripcion." ".$key->porcentaje."%"."</option>'";
	}?>
	+'</select>'
	+'</label>'
	+'</section>';
	$("#moneda_field").append(code);
}
function add_impuesto_boot()
{
	var code=	'<section class="col col-6">Impuesto'
	+'<label class="select">'
	+'<select name="id_impuesto[]">'
	<?php foreach ($impuesto as $key)
	{
		echo "+'<option value=".$key->id_impuesto.">".$key->descripcion." ".$key->porcentaje."%"."</option>'";
	}?>
	+'</select>'
	+'</label>'
	+'</section>';
	$("#moneda_field_boot").append(code);
}
$("#mercancia").submit(function(event){
	var tipo_merc=$("#tipo_mercancia").val();
	if(tipo_merc==1)
	{
		var ids=Array(
			"#nombre_p",
			"#concepto",
			"#marca",
			"#codigo_barras",
			"#mymarkdown",
			"#min_venta",
			"#max_venta",
			"#real",
			"#costo",
			"#costo_publico",
			"#entrega",
			"#puntos_com"
			);
		var mensajes=Array(
			"Por favor ingresa un nombre del producto",
			"Por favor ingresa un concepto",
			"Por favor ingresa una marca",
			"Por favor ingres the codigo of barras",
			"Por favor intriduzca una descripcion",
			"Por favor especifica the canidad minima of venta",
			"Por favor especifica the cantidad maxima of venta",
			"Por favor especifica the costo real",
			"Por favor especifica the costo",
			"Por favor especifica the costo publico",
			"Por favor epsecifica un tiempo of entrega",
			"Por favor especifica the cantidad of Points of commissions"
			);
		var id_entero=Array(
			"#codigo_barras",
			"#min_venta",
			"#max_venta",
			"#entrega"
			);
		var mensaje_entero=Array(
			"El codigo of barras debe contener solo numeros",
			"El minimo of venta debe of ser entero",
			"El maximo of venta debe of ser entero",
			"El tiempo of entrega debe of ser un numero entero"
			);
		var ids_esp=Array(
			"#codigo_barras"
			);
		var mensajes_esp=Array(
			"El codigo of barras no puede contener espacios on blanco"
			);
		var id_dec=Array(
			"#peso",
			"#alto",
			"#ancho",
			"#profundidad",
			"#diametro",
			"#real",
			"#costo",
			"#costo_publico"
			);
		var msj_dec=Array(
			"El peso debe of ser un numero",
			"El alto debe of ser un numero",
			"El anchp debe of ser un numero",
			"La profundidad debe of ser un numero",
			"El diametro debe of ser un numero",
			"El costo real debe of ser un numero",
			"El costo debe of ser un numero",
			"El costo_publico debe of ser un numero"
			);

		var validacion=valida_vacios(ids,mensajes);
		var val_espacios=valida_espacios(ids_esp,mensajes_esp);
		var val_email=true;
		var val_psswrds=true;
		var val_tamano=true;
		var val_entero=valida_entero(id_entero,mensaje_entero);
		var val_fecha=true;
		//var val_decimales=valida_decimales(id_dec, msj_dec);	

	}
	if(tipo_merc==2)
	{
		var ids=nuevaArray(
			"#nombre_s",
			"#concepto",
			"#startdate",
			"#finishdate",
			"#mymarkdown",
			"#real",
			"#costo",
			"#costo_publico",
			"#entrega",
			"#puntos_com"
			);
		var mensajes=nuevaArray(
			"Por favor ingresa un nombre del producto",
			"Por favor ingresa un concepto",
			"Por favor ingrese the fecha of inicio",
			"Por favor especifica the fecha of finalizacion",
			"Por favor intriduzca una descripcion",
			"Por favor especifica the costo real",
			"Por favor especifica the costo",
			"Por favor especifica the costo publico",
			"Por favor epsecifica un tiempo of entrega",
			"Por favor especifica the cantidad of Points of commissions"
			);
		var id_entero=Array(
			"#entrega"
			);
		var mensaje_entero=Array(
			"El tiempo of entrega debe of ser un numero entero"
			);
		var id_fecha=Array(
			"#startdate",
			"#finishdate"
			);
		var mensaje_fecha=Array(
			"El formato of the fecha es incorrecto (aaaa-mm-dd)",
			"El formato of the fecha es incorrecto (aaaa-mm-dd)"
			);
		
		var id_dec=nuevaArray(
			"#real",
			"#costo",
			"#costo_publico"
			);
		var msj_dec=nuevaArray(
			"El costo real debe of ser un numero",
			"El costo debe of ser un numero",
			"El costo_publico debe of ser un numero"
			);
		//var val_decimales=valida_decimales(id_dec, msj_dec);
		var validacion=valida_vacios(ids,mensajes);
		var val_espacios=true;
		var val_email=true;
		var val_psswrds=true;
		var val_tamano=true;
		var val_entero=valida_entero(id_entero,mensaje_entero);
		var val_fecha=valida_fecha(id_fecha,mensaje_fecha);
		
	}
	if(tipo_merc==3)
	{
		var tipo_promo=("#tipo").val();
		if(tipo_promo==1)
		{
			var ids=nuevaArray(
				"#nombre_pr",
				"#prod_qty",
				"#serv_qty",
				"#precio_promo",
				"#real",
				"#costo",
				"#costo_publico",
				"#entrega",
				"#puntos_com"
				);
			var mensajes=nuevaArray(
				"Por favor ingresa un nombre del producto",
				"Por favor especifica the cantidad of producto",
				"Por favor especifica the cantidad of servicio",
				"Por favor especifica the descuento of the promocion",
				"Por favor intriduzca una descripcion",
				"Por favor especifica the costo real",
				"Por favor especifica the costo",
				"Por favor especifica the costo publico",
				"Por favor epsecifica un tiempo of entrega",
				"Por favor especifica the cantidad of Points of commissions"
				);
			var id_entero=Array(
				"#entrega",
				"prod_qty",
				"serv_qty"
				);
			var mensaje_entero=Array(
				"El tiempo of entrega debe of ser un numero entero",
				"La cantidad of prducto debe ser un numero entero",
				"La cantidad of servcio debe ser un numero entero"
				);
			
			var id_dec=nuevaArray(
				"#real",
				"#costo",
				"#costo_publico"
				);
			var msj_dec=nuevaArray(
				"El costo real debe of ser un numero",
				"El costo debe of ser un numero",
				"El costo_publico debe of ser un numero"
				);
			//var val_decimales=valida_decimales(id_dec, msj_dec);
			var validacion=valida_vacios(ids,mensajes);
			var val_espacios=true;
			var val_email=true;
			var val_psswrds=true;
			var val_tamano=true;
			var val_entero=valida_entero(id_entero,mensaje_entero);
			var val_fecha=true;
		}
		if(tipo_promo==2)
		{
			var ids=nuevaArray(
				"#nombre_pr",
				"#n_mercancia",
				"#startdate",
				"#finishdate",
				"#precio_promo"
				);
			var mensajes=nuevaArray(
				"Por favor ingresa un nombre of the promocion",
				"Por favor ingresa the cantidad of mercancia",
				"Por favor ingrese the fecha of inicio",
				"Por favor especifica the fecha of finalizacion",
				"Por favor especifica the descuento of the promocion"
				);
			var id_entero=Array(
				"#n_mercancia"
				);
			var mensaje_entero=Array(
				"La cantidad of mercancia debe ser un numero entero"
				);
			var id_fecha=Array(
				"#startdate",
				"#finishdate"
				);
			var mensaje_fecha=Array(
				"El formato of the fecha es incorrecto (aaaa-mm-dd)",
				"El formato of the fecha es incorrecto (aaaa-mm-dd)"
				);
			
			//var val_decimales=valida_decimales(id_dec, msj_dec);
			var validacion=valida_vacios(ids,mensajes);
			var val_espacios=true;
			var val_email=true;
			var val_psswrds=true;
			var val_tamano=true;
			var val_entero=valida_entero(id_entero,mensaje_entero);
			var val_fecha=valida_fecha(id_fecha,mensaje_fecha);
		}
	}

		//AQUII MAS CODIGO AMIGO JAJA


		if(val_espacios&&validacion&&val_email&&val_psswrds&&val_entero&&val_tamano&&val_fecha)
		{

			return;
		}

		event.preventDefault();

	});

function nueva_pack()
{
	bootbox.dialog({
		message: '<form id="form_paquete" method="post" action="/bo/admin/alta_paquete" class="smart-form">'
		+'<div class="row">'
		+'<fieldset>'
		+'<legend>Datos del paquete</legend>'
		+'<section class="col col-12">Tipo del paquete'
		+'<label class="select">'
		+'<select class="custom-scroll"  name="tipo_paquete">'
		+'<?php foreach ($tipo_paquete as $key){?>'
		+'<option value="<?=$key->id_tipo?>">'
		+'<?=$key->descripcion?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-6">'
		+'<label class="input">Nombre'
		+'<input type="text" name="nombre" id="nombre_pr">'
		+'</label>'
		+'</section>'
		+'<div id="tipo_promo">'
		+'<section class="col col-6">'
		+'<label class="input"><span id="labelextra">Precio</span>'
		+'<input id="precio_promo" type="text" name="precio">'
		+'</label>'
		+'</section>'
		+'<section class="col col-6">'
		+'<label class="input"><span id="labelextra">Points of commissions</span>'
		+'<input id="precio_promo" type="text" name="puntos">'
		+'</label>'
		+'</section>'
		+'<section class="col col-12">Visibilidad'
		+'<label class="select">'
		+'<select class="custom-scroll"  name="visible">'
		+'<option value="1">Affiliate</option>'
		+'<option value="2">Deposits</option>'
		+'<option value="3">Affiliate & Deposito</option>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<div class="row"><br /></div>'
		+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="prods">'
		+'<section class="col col-10">productos'
		+'<label class="select">'
		+'<select class="custom-scroll"  name="producto[]">'
		+'<option value="0">None</option>'
		+'<?php foreach ($producto as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-10">'
		+'<label class="input">Cantidad of productos'
		+'<input type="number" min="1" name="n_productos[]" id="prod_qty">'
		+'</label>'
		+'</section>'
		+'</div>'
		+'<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="servs">'
		+'<section class="col col-10">Services'
		+'<label class="select">'
		+'<select class="custom-scroll" name="servicio[]">'
		+'<option value="0">None</option>'
		+'<?php foreach ($servicio as $key){?>'
		+'<option value="<?=$key->id_mercancia?>">'
		+'<?=$key->nombre?></option>'
		+'<?php }?>'
		+'</select>'
		+'</label>'
		+'</section>'
		+'<section class="col col-10">'
		+'<label class="input">Cantidad of servicios'
		+'<input type="number" min="1" name="n_servicios[]" id="serv_qty">'
		+'</label>'
		+'</section>'
		+'</div>'
		+'</div>'
		+'</fieldset>'
		+'<div id="agregar" class=" text-center row"><a onclick="new_product()">Add producto <i class="fa fa-plus"></i></a>  <a  onclick="new_service()">Add servicio <i class="fa fa-plus"></i></a></div>'
		+'</fieldset>'
		+'</div>'
		+'</form>',
		title: "Add paquete",
		buttons: {
			submit: {
				label: "Accept",
				className: "btn-success",
				callback: function() {

					$.ajax({
						type: "POST",
						url: "/bo/admin/alta_paquete",
						data: $("#form_paquete").serialize(),
					})
					.done(function( msg )
					{
						bootbox.dialog({
							message: msg,
							title: 'Paquete',
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
				label: "Cancel!",
				className: "btn-danger",
				callback: function() {

				}
			}
		}
	})
}
</script>

