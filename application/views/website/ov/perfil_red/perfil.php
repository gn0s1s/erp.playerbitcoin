<!-- MAIN CONTENT -->
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
				<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
				<span> > 
				<a href="/ov/networkProfile">Profile</a>
				>
					Personal information
				</span>
			</h1>
		</div>
	</div>
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">
			<!-- new  COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
					<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

					-->
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Personal information</h2>

					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						<!-- widget content -->
						<div id="contentForm" class="widget-body no-padding">
							<form method="POST" action="/perfil_red/actualizar" id="checkout-form" class="smart-form" novalidate="novalidate">
								<fieldset id="pswd">
                              <?php
                echo "<h4 style='color: green; margin-bottom: 1rem;'>".$this->session->flashdata('success')."</h4>";
              ?>
								</fieldset>
								<fieldset>
								<legend>Personal information</legend>
									<div class="row">
										<section class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-user"></i>
												<input required type="text" value="<?=$usuario[0]->nombre?>" id="nombre" name="nombre" placeholder="Name">
											</label>
										</section>
										<section class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-user"></i>
												<input required type="text" value="<?=$usuario[0]->apellido?>" id="apellido" name="apellido" placeholder="Surname">
											</label>
										</section>

										<section class="col col-3 hide">
											<label class="input"> <i class="icon-prepend fa fa-calendar"></i>
												<input required id="datepicker" value="<?=$usuario[0]->nacimiento?>" type="text" name="nacimiento" placeholder="Birthdate">
											</label>
										</section>
										<section class="col col-3">
											<label id="correo" class="input"><i class="icon-prepend fa fa-envelope-o"></i>
												<input required value="<?=$usuario[0]->email?>" id="email" type="email" name="email" placeholder="Email" onkeyup="use_mail()">
												<b class="tooltip tooltip-top-left"> Enter an email</b>
											</label>
										</section><section class="col col-3">
                                            <?php $secret = substr($usuario[0]->keyword,-3);?>
                                            <label class="input"> <i class="icon-prepend fa fa-qrcode"></i>
                                                <input required type="text" readonly onclick="changeSecret()"
                                                       value="Secret: [...<?=$secret;?>] Click here for Reset">
                                            </label>
                                        </section>
									</div>
									<div class="row">
									<div id="tel" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<?php
									$telFijo=0;
									$telMovil=0;
									foreach ($telefonos as $num)
									{
										if($num->tipo=="Fijo")
										{?>
										<section id='tel_default_<?php echo $telFijo?>' class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-phone"></i>
												<input value="<?=$num->numero?>" id="fijo" type="tel" name="fijo[]" placeholder="(99) 99-99-99-99" >
												<b class="tooltip tooltip-top-left"> Landline</b>
												<?php if($telFijo>0){?>
												<a style="cursor: pointer;color: red;" onclick="delete_telefono('default_<?php echo $telFijo?>')">Remove <i class="fa fa-minus"></i></a>
												<?php }?>
											</label>
										</section>
									<?php $telFijo++;}else{?>
										<section id='tel_default_<?php echo $telMovil?>' class="col col-3">
											<label class="input"> <i class="icon-prepend fa fa-mobile"></i>
												<input value="<?=$num->numero?>" id="movil" type="tel" name="movil[]" placeholder="(999) 99-99-99-99-99">
												<b class="tooltip tooltip-top-left"> Mobile phone</b>
												<?php if($telMovil>0){?>
												<a style="cursor: pointer;color: red;" onclick="delete_telefono('default_<?php echo $telMovil?>')">Remove <i class="fa fa-minus"></i></a>
												<?php }?>
											</label>
										</section>

									<?php $telMovil++;}}?>
									</div>
									<section class="col col-3">
										<button type="button" onclick="agregar('1')" class="btn btn-primary">
											&nbsp;Add <i class="fa fa-mobile"></i>&nbsp;
										</button>
										<button type="button" onclick="agregar('2')" class="btn btn-primary">
											&nbsp;Add <i class="fa fa-phone"></i>&nbsp;
										</button>
									</section>
									</div>
								</fieldset>
								<fieldset>
								<legend>Address</legend>
									<div id="dir" class="row">
										<section id="estado" class="col col-2">
											<label class="input">
												State
												<input type="text" name="estado" value="<?=$dir[0]->estado?>">
											</label>
										</section>
										<section id="municipio" class="col col-2">
											<label class="input">
												Municipality
												<input type="text" name="municipio" value="<?=$dir[0]->municipio?>">
											</label>
										</section>
										<section id="colonia" class="col col-2">
											<label class="input">
												Colony
												<input type="text" name="colonia" value="<?=$dir[0]->colonia?>">
											</label>
										</section>
										<section class="col col-2">
											<label class="input">
												Home address
												<input required type="text" name="calle" value="<?=$dir[0]->calle?>">
											</label>
										</section>
										<section class="col col-2">
											<label class="input">
												Postal Code
												<input required type="text" id="cp" name="cp" value="<?=$dir[0]->cp?>">
											</label>
										</section>
									</div>
								</fieldset>
								<fieldset class="hide">
									<legend>Tax data</legend>
									<div class="row">
										<section class="col col-4">
											<label class="input">
												<input placeholder="Tax Identification" type="text" value="<?=$usuario[0]->keyword?>" name="rfc" id="keyword">
											</label>
										</section>
										<section class="col col-4">
											<label class="select">
												<select id="tipo_fiscal" required name="tipo_fiscal">
												<?php foreach ($tipo_fiscal as $key){if($usuario[0]->id_fiscal==$key->id){?>

													<option selected value="<?=$key->id?>">
														<?=$key->descripcion?>
													</option>
													<?php }else{?>
													<option value="<?=$key->id?>">
														<?=$key->descripcion?>
													</option>
												<?php }}?>
												</select>
											</label>
										</section>
									</div>
								</fieldset>
								<fieldset>
								<legend>Bank data</legend>
									<div class="row">
										<section class="col col-3">Name of owner
											<label class="input"><i class="icon-prepend fa fa-user"></i>												
												<input required type="text" name="c_titular" value="<?=$cuenta[0]->titular?>">
											</label>
										</section>											
										<section class="col col-3">
											Country of the Account
											<label class="select">
												<select id="" required name="c_pais">
												<?php foreach ($pais as $key){													
													if($cuenta[0]->pais==$key->Code){?>

													  <option selected value="<?=$key->Code?>">
														<?=$key->Name?>
													</option>
													<?php }else{?>
													<option value="<?=$key->Code?>">
														<?=$key->Name?>
													</option>
												<?php }}?>
												</select>
											</label>
										</section>										
										<section id="" class="col col-3">Account number
											<label class="input"><i class="icon-prepend fa fa-credit-card"></i>													
												<input type="text" name="c_cuenta" value="<?=$cuenta[0]->cuenta?>">
											</label>
										</section>
										<section id="" class="col col-3">Name of the bank
											<label class="input"><i class="icon-prepend fa fa-bank"></i>												
												<input type="text" name="c_banco" value="<?=$cuenta[0]->banco?>">
											</label>
										</section>
									</div>
									<div class="row">
										<section id="colonia" class="col col-3">SWIFT Code
											<label class="input"><i class="icon-prepend fa fa-sort-numeric-desc"></i>												
												<input type="text" name="c_swift" value="<?=$cuenta[0]->swift?>">
											</label>
										</section>	
										<section id="municipio" class="col col-3">ABA/IBAN/OTHER
											<label class="input"><i class="icon-prepend fa fa-sort-numeric-desc"></i>												
												<input type="text" name="c_otro" value="<?=$cuenta[0]->otro?>">
											</label>
										</section>
										<section id="municipio" class="col col-3">KEY
											<label class="input"><i class="icon-prepend fa fa-sort-numeric-desc"></i>												
												<input type="text" name="c_clabe" value="<?=$cuenta[0]->clabe?>">
											</label>
										</section>
										<section class="col col-3">
											<label class="input">
												Postal address
												<input required type="number" name="c_postal" value="<?=$cuenta[0]->dir_postal?>">
											</label>
										</section>										
									</div>
								</fieldset>
								<fieldset>
									<legend>Statistics</legend>
									<div class="row">
										<section class="col col-3">Civil status
											<label class="select">
												<select name="civil">
												<?php foreach ($civil as $key)
												{
													if($key->id_edo_civil==$usuario[0]->id_edo_civil)
														echo '<option selected value="'.$key->id_edo_civil.'">'.$key->descripcion.'</option>';
													else
													echo '<option value="'.$key->id_edo_civil.'">'.$key->descripcion.'</option>';

												}?>
												</select>
											</label>
										</section>
										<section class="col col-3">Age
											<label class="input"><i class="icon-prepend fa fa-child"></i>
												<input readonly type="text" value="<?=$edad?>" placeholder="Edad">
											</label>
										</section>
										<section class="col col-2">Gender&nbsp;
											<div class="inline-group">
												<?php
												foreach ($sexo as $value)
												{
													if($usuario[0]->sexo==$value->descripcion)
													{
												?>
														<label class="radio">
														<input checked="checked" type="radio" value="<?=$value->id_sexo?>" name="sexo" placeholder="Gender">
														<i></i><?=$value->descripcion?></label>
													<?php }
													else
													{
														?>
														<label class="radio">
														<input type="radio" value="<?=$value->id_sexo?>" name="sexo" placeholder="Gender">
														<i></i><?=$value->descripcion?></label>
													<?php }
												}?>
												</div>
										</section>
										<section class="col col-2">Educational level&nbsp;
											<div class="inline-group">
												<?php
												foreach ($estudios as $value)
												{
													if($usuario[0]->id_estudio==$value->id_estudio)
													{
												?>
														<label class="radio">
														<input checked="checked" type="radio" value="<?=$value->id_estudio?>" name="estudios">
														<i></i><?=$value->descripcion?></label>
													<?php }
													else
													{
														?>
														<label class="radio">
														<input type="radio" value="<?=$value->id_estudio?>" name="estudios">
														<i></i><?=$value->descripcion?></label>
													<?php }
												}?>
												</div>
										</section>
										<section class="col col-2">Occupation&nbsp;
											<div class="inline-group">
												<?php
												foreach ($ocupacion as $value)
												{
													if($usuario[0]->id_ocupacion==$value->id_ocupacion)
													{
												?>
														<label class="radio">
														<input checked="checked" type="radio" value="<?=$value->id_ocupacion?>" name="ocupacion">
														<i></i><?=$value->descripcion?></label>
													<?php }
													else
													{
														?>
														<label class="radio">
														<input type="radio" value="<?=$value->id_ocupacion?>" name="ocupacion">
														<i></i><?=$value->descripcion?></label>
													<?php }
												}?>
												</div>
										</section>
										<section class="col col-2">Dedicated time&nbsp;
											<div class="inline-group">
												<?php
												foreach ($tiempo_dedicado as $value)
												{
													if($usuario[0]->id_tiempo_dedicado==$value->id_tiempo_dedicado)
													{
												?>
														<label class="radio">
														<input checked="checked" type="radio" value="<?=$value->id_tiempo_dedicado?>" name="tiempo_dedicado">
														<i></i><?=$value->descripcion?></label>
													<?php }
													else
													{
														?>
														<label class="radio">
														<input type="radio" value="<?=$value->id_tiempo_dedicado?>" name="tiempo_dedicado">
														<i></i><?=$value->descripcion?></label>
													<?php }}?>
												</div>
										</section>
									</div>
								</fieldset>
								<fieldset class="hidden">
								<legend>Virtual office</legend>
									<div class="row">
										<section class="col col-3">
											<label class="input">
												Background color
												<input type="color" name="bg_color" value="<?=$usuario[0]->bg_color?>">
											</label>
										</section>
										<section class="col col-3">
											<label class="input">
												Primary button color
												<input type="color" name="color_1" value="<?=$usuario[0]->btn_1_color?>">
											</label>
										</section>
										<section class="col col-3">
											<label class="input">
												Secondary button color
												<input type="color" name="color_2" value="<?=$usuario[0]->btn_2_color?>">
											</label>
										</section>
									</div>
								</fieldset>
								<footer>
									<button type="button" onclick="actualizar()" class="btn btn-success">
										Update
									</button>
								</footer>
							</form>
						 </div>
						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
		<!-- END ROW -->
	</section>
	<!-- end widget grid -->
</div>
<!-- END MAIN CONTENT -->
<!-- PAGE RELATED PLUGIN(S) -->
<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {

	pageSetUp();
});
function actualizar()
{
	$
	var ids = new  Array(
		"#nombre",
	 	"#apellido",
	 	"#datepicker",
	 	//"#cp"
	 	"#keyword"
	 );
	var mensajes = new  Array(
		"Please enter your name",
	 	"Please enter your surname",
	 	"Please enter your birthdate",
	 	//"Por favor ingresa tu ZIPCODE"
	 	"Please enter your tax ID"
	 );

	var validacion=valida_vacios(ids,mensajes);
	if(validacion)
	{
		$.ajax({
		type: "POST",
		url: "/ov/perfil_red/actualizar",
		data: $('#checkout-form').serialize()
		})
		.done(function( msg ) {
		bootbox.dialog({
					message: msg,
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
	}
}
function changepswd()
{

		$.ajax({
			type: "POST",
			url: "/auth/change_password",
			data: $('#pswd').serialize()
		})
		.done(function( msg ) {
			bootbox.dialog({
						message: "Are you sure you want to change the password?",
						title: "Attention",
						buttons: {
							success: {
							label: "Ok!",
							className: "btn-success",
							callback: function() {
								location.href="/auth/change_password";
								}
							}
						}
					});
			});
}
function codpos()
{
	var pais = $("#pais").val();
	if(pais=="MEX")
	{
		var cp=$("#cp").val();
		$.ajax({
			type: "POST",
			url: "/ov/perfil_red/cp",
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
var id=0;
function agregar(tipo)
{
	if(tipo==1)
	{
		$("#tel").append("<section id='tel_"+id+"' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-mobile'></i><input type='tel' name='movil[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono("+id+")'>Remove <i class='fa fa-minus'></i></a></section>");
	}
	else
	{
		$("#tel").append("<section id='tel_"+id+"' class='col col-3'><label class='input'> <i class='icon-prepend fa fa-phone'></i><input type='tel' name='fijo[]' placeholder='(999) 99-99-99-99-99'></label><a style='cursor: pointer;color: red;' onclick='delete_telefono("+id+")'>Remove <i class='fa fa-minus'></i></a></section>");
	}

	id++;
}
function delete_telefono(id){
	$("#tel_"+id+"").remove();	
}

$(function()
 {
 	a = new  Date();
	año = a.getFullYear()-18;
	$( "#datepicker" ).datepicker({
	changeMonth: true,
	numberOfMonths: 2,
	maxDate: año+"-12-31",
	dateFormat:"yy-mm-dd",
	changeYear: true,
	yearRange: "-99:+0",
	});
});
 
 function use_mail()
 {
 	$("#msg_correo").remove();
 	var mail=$("#email").val();

 	$.ajax({
 		type: "POST",
 		url: "/ov/perfil_red/use_mail_editar_perfil",
 		data: {mail: mail},
 	})
 	.done(function( msg )
 	{
 		$("#correo").append("<p id='msg_correo'>"+msg+"</msg>")
 	});
 }

function changeSecret() {
    $.ajax({
        type: "POST",
        url: "/ov/perfil_red/changeSecret",
        data: {},
    }).done(function (msg) {
        bootbox.dialog({
            message: msg,
            title: "Attention",
            buttons: {
                success: {
                    label: "Ok!",
                    className: "btn-success",
                    callback: function () {
                        location.href = "/ov/networkProfile/profile";
                    }
                }
            }
        });
    });
}
</script>
