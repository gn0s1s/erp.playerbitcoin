<div class="row fuelux">
		<div id="myWizard_r" class="wizard wizard_r">
			<ul class="steps">
				<li data-target="#step1_r" class="active">
					<span class="badge badge-info">1</span>Datos del registro<span class="chevron"></span>
				</li>
				<li data-target="#step2_r">
					<span class="badge">2</span>Personal Info<span class="chevron"></span>
				</li>
				<li data-target="#step3_r">
					<span class="badge">3</span>Selección del plan<span class="chevron"></span>
				</li>
				<li id="paso4_r" data-target="#step4_r">
					<span class="badge">4</span>Método of pago<span class="chevron"></span>
				</li>
			</ul>
			<div id="acciones_r" class="actions">
				<button type="button" class="final btn btn-sm btn-primary btn-prev">
					<i class="fa fa-arrow-left"></i>Anterior
				</button>
				<button type="button" class="final btn btn-sm btn-success btn-next" data-last="Afiliar">
					Siguiente<i class="fa fa-arrow-right"></i>
				</button>
			</div>
		</div>
		<div class="step-content">
			<div class="form-horizontal" id="fuelux-wizard" >
				<div class="step-pane active" id="step1_r">
					<form id="register_red" class="smart-form">
						<fieldset>
							<legend>Information of cuenta</legend>
							<section id="usuario_r" class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-user"></i>
								<input id="username_r" onkeyup="use_username_r()" required type="text" name="username" placeholder="Username">
								</label>
							</section>
							<section id="correo_r" class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-envelope-o"></i>
								<input id="email_r" onkeyup="use_mail_r()" required type="email" name="email" placeholder="Email">
								</label></section><section class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-lock"></i>
								<input id="password_r" required type="password" name="password" placeholder="Password">
								</label>
							</section>
							<section class="col col-6">
								<label class="input"><i class="icon-prepend fa fa-lock"></i>
									<input id="confirm_password_r" required type="password" name="confirm_password" placeholder="Repite Password">
								</label>
							</section>
						</fieldset>
					</form>
				</div>
				<div class="step-pane" id="step2_r">
					<form method="POST" action="/perfil_red/afiliar_nuevo_r/+id" id="afiliar_red" class="smart-form" novalidate="novalidate">
						<fieldset>
							<legend>Personal Info del afiliado</legend>
							<div class="row">
								<section class="col col-6">
									<label class="input"><i class="icon-prepend fa fa-user"></i>
									<input id="nombre_r" required type="text" name="nombre" placeholder="Name">
									<input required type="hidden" id="id" name="afiliados" value="+id">
									//<input id="mail_important" required type="hidden" name="mail_important" value="">
									<input id="lado" required type="hidden" name="lado" value="+lado">
									<input type="hidden" name="tipo_plan" id="tipo_plan_r">
									</label>
								</section>
								<section class="col col-6">
									<label class="input"><i class="icon-prepend fa fa-user"></i>
									<input id="apellido_r" required type="text" name="apellido" placeholder="Lastname">
									</label>
								</section>
								<section class="col col-6">
									<label class="input"><i class="icon-append fa fa-calendar"></i>
									<input required id="datepicker_r" type="date" min="1920-12-31" max="1996-12-31" name="nacimiento" placeholder="Birthdate">
									</label>
								</section>
								<section class="col col-6" id="key_red">
									<label id="key_2" class="input"> <i class="icon-prepend fa fa-barcode"></i>
										<input id="keyword_red" onkeyup="check_keyword_red()" placeholder="RFC o CURP" type="text" name="keyword">
									</label>
								</section>
							</div>

							<div class="row">
								<div id="tel_red" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<section class="col col-6">
										<label class="input"><i class="icon-prepend fa fa-phone"></i>
											<input required name="fijo[]" placeholder="(99) 99-99-99-99" type="tel">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"><i class="icon-prepend fa fa-mobile"></i>
											<input required name="movil[]" placeholder="(999) 99-99-99-99-99" type="tel">
										</label>
									</section>
								</div>
								<section class="col col-12">
									<button type="button" onclick="agregar_red(1)" class="btn btn-primary">&nbsp;Add <i class="fa fa-mobile"></i>&nbsp;</button>&nbsp;
									<button type="button" onclick="agregar_red(2)" class="btn btn-primary">&nbsp;Add <i class="fa fa-phone"></i>&nbsp;</button>
								</section>
							</div>
						</fieldset>
						<fieldset>
							<legend>Datos co-aplicante</legend>
							<div class="row">
								<section class="col col-4">
									<label class="input">
										<input placeholder="Name" type="text" name="nombre_co">
									</label>
								</section>
								<section class="col col-4">
									<label class="input"> 
										<input placeholder="Lastname" type="text" name="apellido_co">
									</label>
								</section>
								<section class="col col-4" id="key_red_co">
									<label id="key_3" class="input"> <i class=" icon-prepend fa fa-barcode"></i>
										<input onkeyup="check_keyword_red_co()" placeholder="RFC o CURP" type="text" name="keyword_co" id="keyword_red_co">
									</label>
								</section>
							</div>
						</fieldset>
						<fieldset>
							<legend>Configuración del afiliado</legend>
								<section class="col col-12">
									<label class="toggle">
									<input type="checkbox" checked="checked" name="sponsor">
									<i data-swchoff-text="No" data-swchon-text="Si"></i>I'm Sponsor</label>
									<small>if I'm Sponsor, i can get direct earnings from this member</small>
								</section>
						</fieldset>
						<fieldset>
							<legend>Home Adress</legend>
							<div id="dir_red" class="row">
								<section class="col col-6">
									<label class="input">Street Address
									<input required type="text" name="calle">
									</label>
								</section>
								<section id="colonia_red" class="col col-6">
									<label class="input">Ciudad
									<input type="text" name="colonia" >
									</label>
								</section>
								<section id="municipio_red" class="col col-6">
									<label class="input">Provincia
									<input type="text" name="municipio" >
									</label>
								</section>
								<section class="col col-6">
									<label class="input">ZIPCODE
										<input required onkeyup="codpos_red()" type="text" id="cp_red" name="cp">
									</label>
								</section>
								<section class="col col-6">Country
									<label class="select">
										<select id="pais_red" required name="pais"><?foreach ($pais as $key){?>
											<option value="<?=$key->Code?>"><?=$key->Name?></option><?}?>
										</select>
									</label>
								</section>
							</div>
						</fieldset>
						<fieldset>
							<legend>Statistics</legend>
							<div class="row">
								<section class="col col-6">Civil Status
									<label class="select">
									<select name="civil"><?foreach ($civil as $key){?>
									<option value="<?=$key->id_edo_civil?>"><?=$key->descripcion?></option><?}?>
									</select>
									</label>
								</section>
								<section class="col col-6">Sexo&nbsp;
									<div class="inline-group"><?foreach ($sexo as $value){?>
									<label class="radio">
									<input <?=($value->id_sexo==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_sexo?>" name="sexo" placeholder="sexo"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-12">schooling&nbsp;
									<div class="inline-group"><?foreach ($estudios as $value){?>
									<label class="radio">
									<input <?=($value->id_estudio==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_estudio?>" name="estudios"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-6">Occupation&nbsp;
									<div class="inline-group"><?foreach ($ocupacion as $value){?>
									<label class="radio">
									<input <?=($value->id_ocupacion==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_ocupacion?>" name="ocupacion"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
								<section class="col col-6">Working time&nbsp;
									<div class="inline-group"><?foreach ($tiempo_dedicado as $value){?>
									<label class="radio">
									<input <?=($value->id_tiempo_dedicado==1) ? "checked" : " " ?> type="radio" value="<?=$value->id_tiempo_dedicado?>" name="tiempo_dedicado"><i></i><?=$value->descripcion?>
									</label><?}?>
									</div>
								</section>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="step-pane" id="step3_r">
					<div class="row">
						<br />
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="row">
					        <div id="planuno_r" class="col-xs-12 col-sm-6 col-md-6">
					            <div class="panel panel-success pricing-big">
					                <div class="panel-heading">
					                    <h3 class="panel-title">
					                       <i class="fa fa-plane"></i> Plan despegue</h3>
					                </div>
					                <div class="panel-body no-padding text-align-center">
					                    <div class="the-price">
					                        <h1>
					                            <strong>$85.25 USD</strong></h1>
					                    </div>
										<div class="price-features">
											<ul class="list-unstyled text-left">
									          	<li><h1><i class="fa fa-check text-success"></i> <strong>8%</strong> of ganancia</h1></li>
									        	<li><h1><i class="fa fa-check text-success"></i> <strong>60</strong> Points of commissions</h1></li>
									        	<li><i class="fa fa-check text-success"></i> <strong>1</strong> Aloe Detox (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>1</strong> Vita Live (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>1</strong> Linea Gala</li>
									        </ul>
										</div>
					                </div>
					                <div class="panel-footer text-align-center">
					                    <a id="plan1_r" href="#" class="btn btn-primary btn-block" role="button">Seleccionar</span></a>
					                </div>
					            </div>
					        </div>
					        <div id="plandos_r" class="col-xs-12 col-sm-6 col-md-6">
					            <div class="panel panel-teal pricing-big">
					            	
					                <div class="panel-heading">
					                    <h3 class="panel-title">
					                        <i class="fa fa-bar-chart-o"></i> Plan avance</h3>
					                </div>
					                <div class="panel-body no-padding text-align-center">
					                    <div class="the-price">
					                        <h1>
					                            <strong>$164.00 USD</strong></h1>
					                    </div>
										<div class="price-features">
											<ul class="list-unstyled text-left">
									          	<li><h1><i class="fa fa-check text-success"></i> <strong>10%</strong> of ganancia</h1></li>
									        	<li><h1><i class="fa fa-check text-success"></i> <strong>135</strong> Points of commissions</h1></li>
									        	<li><i class="fa fa-check text-success"></i> <strong>4</strong> Aloe Detox (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>3</strong> Vita Live (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>1</strong> Linea Gala</li>
									        </ul>
										</div>
					                </div>
					                <div class="panel-footer text-align-center">
					                    <a id="plan2_r" href="#" class="btn btn-primary btn-block" role="button">Seleccionar</span></a>
					                </div>
					            </div>
					        </div>
					        
					        <div id="plantres_r" class="col-xs-12 col-sm-6 col-md-6">
					            <div class="panel panel-primary pricing-big">
					            	<img src="/template/img/ribbon.png" class="ribbon" alt="">
					                <div class="panel-heading">
					                    <h3 class="panel-title">
					                        <i class="fa fa-suitcase"></i> Plan empresarial</h3>
					                </div>
					                <div class="panel-body no-padding text-align-center">
					                    <div class="the-price">
					                        <h1>
					                            <strong>$454.25 USD</strong></h1>
					                    </div>
										<div class="price-features">
											<ul class="list-unstyled text-left">
									          	<li><h1><i class="fa fa-check text-success"></i> <strong>12%</strong> of ganancia</h1></li>
									        	<li><h1><i class="fa fa-check text-success"></i> <strong>420</strong> Points of commissions</h1></li>
									        	<li><i class="fa fa-check text-success"></i> <strong>10</strong> Aloe Detox (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>9</strong> Vita Live (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>4</strong> Linea Gala</li>
									        </ul>
										</div>
					               </div>
					                <div class="panel-footer text-align-center">
					                    <a id="plan3_r" href="#" class="btn btn-primary btn-block" role="button">Seleccionar</span></a>
					                </div>
					            </div>
					        </div>
					        <div id="plancuatro_r" class="col-xs-12 col-sm-6 col-md-6">
					            <div class="panel panel-darken pricing-big">
					                <div class="panel-heading">
					                    <h3 class="panel-title">
					                        <i class="fa fa-signal"></i> <i class="fa fa-male"></i> Plan inversionista</h3>
					                </div>
					                <div class="panel-body no-padding text-align-center">
					                    <div class="the-price">
					                        <h1>
					                            <strong>$920.00 USD</strong></h1>
					                    </div>
										<div class="price-features">
											<ul class="list-unstyled text-left">
									          	<li><h1><i class="fa fa-check text-success"></i> <strong>15%</strong> of ganancia</h1></li>
									        	<li><h1><i class="fa fa-check text-success"></i> <strong>850</strong> Points of commissions</h1></li>
									        	<li><i class="fa fa-check text-success"></i> <strong>20</strong> Aloe Detox (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>20</strong> Vita Live (6 pack)</li>
									        	<li><i class="fa fa-check text-success"></i> <strong>8</strong> Linea Gala</li>
									        	<li><i class="fa fa-check text-success"></i> <small>Podrás modificar the cantidad of producto que tengan the mismo precio que no insida on the valor & puntaje del plan</small></li>
									        </ul>
										</div>
					                </div>
					                <div class="panel-footer text-align-center">
					                    <a id="plan4_r" href="#" class="btn btn-primary btn-block" role="button">Seleccionar</span></a>
					                </div>
					            </div>
					        </div>		    	
			    		</div>
			    		<br />
			    		<a id="remove_step_r" href="#" class="btn btn-primary btn-block" role="button">Purchase the plan después</span></a>
			    	</div>
				</div>
				<div class="step-pane" id="step4_r">
				</div>

			</div>
		</div>
		</div>
		<script>
		$("#datepicker_r").datepicker({
					dateFormat:"yy-mm-dd",
					defaultDate: "1970-01-01",});

		$('.wizard_r').on('finished.fu.wizard', function (e, data) {
	  		var ids = new  Array(
			"#nombre_r",
		 	"#apellido_r",
		 	"#datepicker_r",
		 	"#cp_red",
		 	"#username_r",
		 	"#email_r",
		 	"#password_r",
		 	"#confirm_password_r"
		 	
			 );
			var mensajes = new  Array(
				"Por favor ingresa tu nombre",
			 	"Por favor ingresa tu apellido",
			 	"Por favor ingresa tu Birthdate",
			 	"Por favor ingresa tu ZIPCODE",
			 	"Por favor ingresa un nombre of usuario",
			 	"Por favor ingresa un correo",
			 	"Por favor ingresa una Password",
			 	"Por favor confirma tu Password"
			 );

			var idss=new Array(
				"#username_r"
			);
			var mensajess=new Array(
				"El nombre of usuario no puede contener espacios on blanco"
			);
			var validacion_=valida_espacios(idss,mensajess);
			var validacion=valida_vacios(ids,mensajes);
			if(validacion&&validacion_)
			{
				var id=$("#id").val();
				$.ajax({
                       url:"/auth/register",
                       data:$("#register_red").serialize(),
                       type:"POST",
                       success:function(response){

                       }//Fin success register
                    });//Fin ajax register
					var email=$("#email_r").val();
					$("#afiliar_red").append("<input value='"+email+"' type='hidden' name='mail_important'>");
					$.ajax({
                       url:"/ov/perfil_red/afiliar_nuevo_r/"+id,
                       data:$("#afiliar_red").serialize(),
                       type:"POST",
                       success:function(response){
							bootbox.dialog({
								message: response,
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
                       } //Fin success Profile
                    });//Fin ajax Profile
			}//Fin ajaxs
		else
		{
			$.smallBox({
		      title: "<h1>Attention</h1>",
		      content: "<h3>Por favor reviza que todos los datos estén correctos</h3>",
		      color: "#C46A69",
		      icon : "fa fa-warning fadeInLeft animated",
		      timeout: 4000
		    });
		}
	    
	  });

$("#remove_step_r").click(function(event) {

	$("#tipo_plan_r").attr("name","tipo_plan");
	$('.wizard_r').wizard('selectedItem', {
			step: 4
		});
	$( "#step4_r" ).slideUp();
	$( "#step4_r" ).remove();
	$( "#paso4_r" ).slideUp();
	$( "#paso4_r" ).remove();
	$( this ).slideUp();
	$( this ).remove();
});
$("#plan1_r").click(function(event) {
$("#tipo_plan_r").attr("value","1");
$("#planuno_r").addClass('packselected');
$("#plandos_r").removeClass('packselected');
$("#plantres_r").removeClass('packselected');
$("#plancuatro_r").removeClass('packselected');
});
$("#plan2_r").click(function(event) {
	$("#tipo_plan_r").attr("value","2");
	$("#planuno_r").removeClass('packselected');
	$("#plandos_r").addClass('packselected');
	$("#plantres_r").removeClass('packselected');
	$("#plancuatro_r").removeClass('packselected');
});
$("#plan3_r").click(function(event) {
	$("#tipo_plan_r").attr("value","3");
	$("#planuno_r").removeClass('packselected');
	$("#plandos_r").removeClass('packselected');
	$("#plantres_r").addClass('packselected');
	$("#plancuatro_r").removeClass('packselected');
});
$("#plan4_r").click(function(event) {
	$("#tipo_plan_r").attr("value","4");
	$("#planuno_r").removeClass('packselected');
	$("#plandos_r").removeClass('packselected');
	$("#plantres_r").removeClass('packselected');
	$("#plancuatro_r").addClass('packselected');
});
}
		
		</script>
		
		
