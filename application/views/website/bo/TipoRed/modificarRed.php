<div id="spinner-div"></div>
<div style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; margin-right: 0px; margin-left: 0px; padding-bottom: 3rem;"
     class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form class="smart-form" action="/bo/tipo_red/actualizar_red" method="POST" id="redes" role="form">

            <div class="row">
                <div class="form-group">

                    <input type="text" class="hide" name="id" value='<?= $id_red; ?>'>

                    <label for="">Name</label>
                    <input type="text" class="form-control" required name="nombre"
                           value='<?= $datosDeRed[0]->nombre; ?>'>

                    <label for="">Description</label>
                    <textarea class="form-control" rows="5" required
                              name="descripcion"><?= $datosDeRed[0]->descripcion; ?></textarea>
                </div>
                <div class="form-group">
                    <div class="col-md-5"><br>
                        <p>Note: If the Network first line/spillover limit is infinite enter 0.</p><br><label
                                style="margin: 1rem;" class="input"><i class="icon-prepend fa fa-arrow-right"></i><input
                                    type="number" class="form-control" name="frontal" size="30" min="0"
                                    placeholder="frontalidad" value="<?= $datosDeRed[0]->frontal; ?>" required>
                        </label>
                        <label style="margin: 1rem;" class="input"><i class="icon-prepend fa fa-arrow-down"></i><input
                                    type="number" class="form-control" name="profundidad" size="30" min="0"
                                    placeholder="profundidad" value="<?= $datosDeRed[0]->profundidad; ?>" required>
                        </label>
                    </div>
                    <div class="col-md-1"><br></div>
                    <div class="col-md-5"><br>
                        <p>Choose Commissions Setting</p><br><label class="radio">
                            <input type="radio" name="tipo_plan"
                                   value="BIN" <?= ($datosDeRed[0]->plan == "BIN") ? 'checked' : '' ?>
                                   placeholder="binary"> <i></i>Binary</label><!-- <label class="radio">
	        <input type="radio" name="tipo_plan" value="BEQ" <? /*($datosDeRed[0]->plan=="BEQ") ? 'checked' : '' */ ?> placeholder="tipo of plan">
	        <i></i>Binario Equilibrado</label>--><label class="radio">
                            <input type="radio" name="tipo_plan"
                                   value="MAT" <?= ($datosDeRed[0]->plan == "MAT") ? 'checked' : '' ?>
                                   placeholder="matrix"> <i></i>Matrix</label>
                        <label class="radio">
                            <input type="radio" name="tipo_plan"
                                   value="UNI" <?= ($datosDeRed[0]->plan == "UNI") ? 'checked' : '' ?>
                                   placeholder="one level"> <i></i>One level</label></div>

                </div>
            </div>
            <div class="row">
                <div class="form-group"><br>
                    <p>Value of commission Point</p><br>
                    <div class="col-md-5">
                        <label style="margin: 1rem;" class="input"><i class="icon-prepend fa fa-dollar"></i><input
                                    type="number" min="1" class="form-control" name="valor_punto" size="10"
                                    placeholder="Valor del Punto" value="<?= $datosDeRed[0]->valor_punto; ?>" required>
                        </label></div>
                </div>
            </div>


            <div class="row pull-right">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>


        </form>
    </div>
</div>

<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">
    $("#redes").submit(function (event) {
        event.preventDefault();
        setiniciarSpinner();
        enviar_datos();
    });

    function enviar_datos() {
        $.ajax({
            type: "POST",
            url: "/bo/tipo_red/actualizar_red",
            data: $('#redes').serialize()
        }).done(function (msg) {
            FinalizarSpinner();
            bootbox.dialog({
                message: msg,
                title: 'ATENCION',
                buttons: {
                    success: {
                        label: "Accept",
                        className: "btn-success",
                        callback: function () {
                            location.href = "/bo/tipo_red/mostrar_redes";

                        }
                    }
                }
            })
        });//fin Done ajax
    }

</script>
