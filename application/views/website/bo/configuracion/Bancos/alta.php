			<!-- MAIN CONTENT -->
			<div id="content" >
				<div class="row">
					<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
						<h1 class="page-title txt-color-blueDark">
						<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
							<span>
								> <a href="/bo/configuracion/"> Configuración</a> 
								> <a href="/bo/configuracion/formaspago"> Formas of Pago</a> 
								> <a href="/bo/bancos/index"> Bancos </a>
								> Alta
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
				<div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false"
          data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-sortable="false"
          data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-collapsed="false">
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						<!-- widget content -->
						<div class="widget-body no-padding smart-form">
                <fieldset>
                  <div class="contenidoBotones">
					<div class="row">
							<div class="">
										<div>
											<div class="widget-body">
												<form id="add-event-form" class="smart-form col-sm-6 col-md-6 col-lg-6">
													<fieldset>
														<div class="form-group">
															<b>Nombre of Banco</b>
															<input class="form-control"  id="banco" name="banco" type="text" placeholder="Nombre del banco">
														</div><br>
														<div class="form-group">
															<label class="label"><b>Pais</b></label> 
															<label class="select">
																<select name="pais" id="pais" required>
																	<option value="0">Selecciona the pais</option>
																	<?php foreach ($paices as $pais) {?>
																		<option value="<?php echo $pais->Code; ?>"><?php echo $pais->Name; ?></option>
																	<?php } ?>
																</select>
															</label>
														</div>
														<br>
														<div class="form-group">
															<b>N° of Cuenta</b>
															<input class="form-control"  id="cuenta" name="cuenta"  type="number" placeholder="Numero of cuenta" onChange="validarSiNumero(this.value);">
														</div><br>
														<div class="form-group">
															Titular
															<input class="form-control"  id="otro" name="otro" type="text">
														</div><br>
														<div class="form-group">
															Código SWIFT
															<input class="form-control"  id="swift" name="swift" type="text">
														</div><br>														
														<div class="form-group">
															CLABE
															<input class="form-control"  id="clabe" name="clabe" type="number" onChange="validarSiNumero(this.value);">
														</div><br>
														<div class="form-group">
															Dirección postal
															<input class="form-control"  id="dir_postal" name="dir_postal" type="text">
														</div><br>
													</fieldset>
													
													<button style="margin-left: 3rem;" class="btn btn-success" type="button" id="new_evento" onclick="agregar_banco()" >
														Add Banco
													</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
				</fieldset>
				</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
			</article>
			<!-- END COL -->
		</div>
		</section>
				<div class="row">         
			        <!-- a blank row to get started -->
			        <div class="col-sm-12">
			            <br />
			            <br />
			        </div>
		        </div>
			</div>
    			
			<!-- END MAIN CONTENT -->

<style>
.link
{
	margin: 0.5rem;
}
.minh
{
	padding: 50px;
}
.link a:hover
{
	text-decoration: none !important;
}
</style>
<script type="text/javascript">
function ValidarVacio(banco, pais, cuenta){
	if(banco == ''){
		alert('Campo Nombre of bank es requerido');
		return false;
	}else if(pais == '0'){
		alert('Seleciona un pais');
		return false;
	}else if(cuenta == ''){
		
		alert('Campo Numero of Cuenta es requerido');
		return false;
	}else{
		return true;
	}
	
}
function agregar_banco()
{
	
	var banco= $("#banco").val();
	var cuenta = $("#cuenta").val();
	var pais = $("#pais").val();
	var clabe = $("#clabe").val();
	var swift = $("#swift").val();
	var otro = $("#otro").val();
	var dir_postal = $("#dir_postal").val();
	
	if(ValidarVacio(banco, pais, cuenta)){
		iniciarSpinner();
		$.ajax({
			 data:{
				 banco: banco,
				 pais: pais,
				 cuenta: cuenta,
				 clabe: clabe,
				 swift:swift,
				 otro:otro,
				 dir_postal:dir_postal
				},
	         type: "post",
	         url: "nuevo_banco",
	         success: function(msg){
	        	 bootbox.dialog({
						message: msg,
						title: 'Congratulations',
						buttons: {
							success: {
							label: "Accept",
							className: "btn-success",
							callback: function() {
								location.href="listar";
								FinalizarSpinner();
								}
							}
						}
					})//fin done ajax
	             
	         }
		});
	}
}
function validarSiNumero(numero){
    if (!/^([0-9])*$/.test(numero)){
      alert("El valor " + numero + " no es un número");
      
    }
  }
</script>			
