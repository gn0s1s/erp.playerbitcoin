<form id="form_ciudad" method="post" class="smart-form">
	<fieldset>
		<legend>Datos of Departamento</legend>
		<div class="row">
			<section class="col col-6">
				Country<label class="select">
					<select id="pais" required name="pais">'
						<?php foreach ($paises as $key){?>'
							<option value="<?=$key->Code?>"><?=$key->Name?></option>
						<?php }?>
					</select>
				</label>
			</section>
			
			<section class="col col-6">
				<label class="input">Nombre of departamento
					<input required type="text" name="nombre" placeholder="Nombre of departamento">
				</label>
			</section>
			
		</div>
	</fieldset>
</form>

