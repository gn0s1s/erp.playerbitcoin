<div id="spinner-div"></div>
				<form id="edit" class="smart-form" role="form" >
	                <fieldset>
		                <div class="contenidoBotones">
							<div class="row col-xs-10 col-sm-8 col-md-8 col-lg-8" style="margin: 4rem;">
								<div class="">
										<section>
												<div>
														<fieldset>
															<label class="label">Account E-mail</label>
															<label class="input">
																<input name="email" value="<?php echo $user[0]->email?>" id="email" maxlength="60" size="30" required type="email">
															</label>
															<label class="txt-color-red"></label>
															<br>
															<label class="label">choose the Almacén al que pertenece</label>
															<label class="select">
																<select name="id_cedi" id="id_cedi" required>
																<?php foreach ($almacenes as $cedi) {
																	if ($cedi->id_cedi==$user[0]->cedi){?>
																	<option selected value="<?php echo $cedi->id_cedi; ?>">
																		<?php echo $cedi->nombre; ?>
																	</option>
																<?php }else {?>	
																	<option value="<?php echo $cedi->id_cedi; ?>">
																		<?php echo $cedi->nombre; ?>
																	</option>
																<?php }
																}?>
																</select>
															</label>
															<br>
															<label class="label">Country</label>
															<label class="select">
																	<select name="id_pais" id="id_pais" required>
																		<?php foreach ($paises as $pais) {
																			if($pais->Code!='AAA'){
																			if ($pais->Code == $user[0]->id_pais){?>
																			<option selected value="<?php echo $pais->Code; ?>">
																				<?php echo $pais->Name; ?>
																			</option>
																		<?php }else {?>
																			<option value="<?php echo $pais->Code; ?>">
																				<?php echo $pais->Name; ?>
																			</option>
																		<?php }}
																		}?>
																	</select>
															</label>
														</fieldset>
														<input type="text" class="hide" value="<?php echo $user[0]->id; ?>" name="id">													
														<fieldset>
														<label class="label">IDN</label>
														<label class="input">
															<input name="dni" value="<?php echo $user[0]->dni?>" id="dni" maxlength="60" size="30" type="text" required>
														</label>
														<label class="txt-color-red"></label>
														<br>
														<label class="label">Name</label>
														<label class="input">
															<input name="nombre" value="<?php echo $user[0]->nombre?>" id="username" maxlength="60" size="30" type="text" required>
														</label>
														<label class="txt-color-red"></label>
														<br>
														<label class="label">Apellido(s)</label>
														<label class="input">
															<input name="apellido" value="<?php echo $user[0]->apellido?>" id="username" maxlength="60" size="30" type="text" required>
														</label>
														<label class="txt-color-red"></label>
														<br>
														<label class="label">Teléfono (fijo o Movil)</label>
														<label class="input">
															<input name="telefono" value="<?php echo $user[0]->telefono?>" id="telefono" maxlength="60" size="30" type="tel" pattern="[0-9-(-)---+]+" required>
														</label>
														<label class="txt-color-red"></label>
														</fieldset>
												
													</div>
												</section>
											</div>
										</div>
									</div>
								</fieldset>
							<footer>
								<input type="submit" class="btn btn-success" value="Actualizar" />
							</footer>
						</form>

<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">

$( "#edit" ).submit(function( event ) {
	event.preventDefault();	
	enviar();
});

function enviar() {
	setiniciarSpinner();
	 $.ajax({
							type: "POST",
							url: "/bo/usuarios/actualizarAlmacen",
							data: $('#edit').serialize()
						})
						.done(function( msg ) {
							
									bootbox.dialog({
										//location.href="/bo/usuarios/listarCedi";
										message: msg,
										title: "Attention",
										buttons: {
											success: {
											label: "Ok!",
											className: "btn-success",
											callback: function() {
												FinalizarSpinner();
												location.href="/bo/usuarios/listarAlmacen";
												}
											}
										}
									});
						});//fin Done ajax
		
}
</script>

