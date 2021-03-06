<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>&gt; <a href="/bo/configuracion/">Settings</a> &gt; <a
                            href="/bo/configuracion/tipoRed">Networks</a> &gt; Add Network</span></h1>
        </div>
    </div>
    <section id="widget-grid" class=""><!-- START ROW -->
        <div class="row"><!-- new  COL START -->
            <article class="col-sm-12 col-md-12 col-lg-12"><!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false"><!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

					--> <!-- widget div-->
                    <div>
                        <fieldset id="pswd">
                            <form class="smart-form" action="" method="POST" role="form" id="nueva">
                                <!-- /bo/tipo_red/guardar_red -->
                                <legend>Create Network</legend>
                                <br>
                                <div class="form-group" style="width: 20rem;">
                                    <label style="margin: 1rem;" class="input"><i
                                                class="icon-prepend fa fa-check-circle-o"></i><input type="text"
                                                                                                     class="form-control"
                                                                                                     name="nombre"
                                                                                                     size="30"
                                                                                                     placeholder="Name"
                                                                                                     required>
                                    </label>
                                    <label class="textarea">
                                        <textarea style="margin-left: 1rem;" rows="6" class="custom-scroll"
                                                  name="descripcion" size="30" required
                                                  placeholder="Descripcion"></textarea>
                                    </label><br>
                                    <p>Note: If the Network first line/spillover limit is infinite enter 0.</p>
                                    <br><label style="margin: 1rem;" class="input"><i
                                                class="icon-prepend fa fa-arrow-right"></i><input type="number"
                                                                                                  class="form-control"
                                                                                                  name="frontal"
                                                                                                  size="30" min="0"
                                                                                                  placeholder="vector limit"
                                                                                                  required>
                                    </label>
                                    <label style="margin: 1rem;" class="input"><i
                                                class="icon-prepend fa fa-arrow-down"></i><input type="number"
                                                                                                 class="form-control"
                                                                                                 name="profundidad"
                                                                                                 size="30" min="0"
                                                                                                 placeholder="spillover"
                                                                                                 required>
                                    </label><br>
                                    <p>Choose Commissions Setting</p><br><label class="radio">
                                        <input type="radio" name="tipo_plan" value="BIN"
                                               placeholder="tipo of plan"><i></i>Binary</label><!-- <label class="radio">
	        <input type="radio" name="tipo_plan" value="BEQ" placeholder="tipo of plan">
	        <i></i>Binario Equilibrado</label>- --><label class="radio">
                                        <input type="radio" name="tipo_plan" value="MAT" checked
                                               placeholder="tipo of plan"><i></i>Matrix</label>
                                    <label class="radio">
                                        <input type="radio" name="tipo_plan" value="UNI"
                                               placeholder="tipo of plan"><i></i>One level</label><br>
                                    <p>Value of commission point</p><br><label style="margin: 1rem;" class="input"><i
                                                class="icon-prepend fa fa-dollar"></i><input type="number"
                                                                                             class="form-control"
                                                                                             min="1" name="valor_punto"
                                                                                             size="30"
                                                                                             placeholder="USD"
                                                                                             required>
                                    </label>
                                    <button style="margin: 1rem;margin-bottom: 4rem;" type="submit"
                                            class="btn btn-success">Submit
                                    </button>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
        </div>
        </article>
    </section>
</div>

<script type="text/javascript">
    $("#nueva").submit(function (event) {
        event.preventDefault();
        iniciarSpinner();
        enviar();
    });

    function enviar() {
        $.ajax({
            type: "POST",
            url: "/bo/tipo_red/guardar_red",
            data: $('#nueva').serialize()
        }).done(function (msg) {
            bootbox.dialog({
                message: msg,
                title: 'Attention',
                buttons: {
                    success: {
                        label: "Accept",
                        className: "btn-success",
                        callback: function () {
                            location.href = "/bo/tipo_red/mostrar_redes";
                            FinalizarSpinner();
                        }
                    }
                }
            })
        });//fin Done ajax

    }
</script>
