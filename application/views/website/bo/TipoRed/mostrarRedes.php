<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>> <a href="/bo/configuracion/">Settings</a> > <a href="/bo/configuracion/tipoRed">Networks</a> > List All</span>
            </h1>
        </div>
    </div><?php if ($this->session->flashdata('exito')) {
        echo '<div class="alert alert-success fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Exito</strong> ' . $this->session->flashdata('exito') . '
			</div>';
    }
    ?>
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
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <legend>Show Networks</legend>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody><?php foreach ($redes as $red) { ?>
                                    <tr>
                                    <td><?= $red->id; ?></td>
                                    <td><?= $red->nombre; ?></td>
                                    <td><?= $red->descripcion; ?></td>
                                    <td><a onclick="modificar(<?= $red->id; ?>)" href="#" title="Edit"><i
                                                    class="txt-color-blue fa fa-pencil fa-3x fa-3x"></i></a> <?php if ($red->estatus == 'ACT') { ?>
                                            <a style="cursor: pointer" title="Disable"
                                               onclick="estado('DES','<?php echo $red->id; ?>')"
                                               class="txt-color-green"><i
                                                        class="fa fa-check-square-o fa-3x"></i></a> <?php } else { ?> <a
                                                style="cursor: pointer" title="Enable"
                                                onclick="estado('ACT','<?php echo $red->id; ?>')"
                                                class="txt-color-green"><i
                                                    class="fa fa-square-o fa-3x"></i></a> <?php } ?> <a
                                                onclick="eliminar(<?= $red->id; ?>)" href="#" title="Delete"><i
                                                    class="txt-color-red fa fa-trash-o fa-3x "></i></a></td>
                                    </tr><?php } ?></tbody>
                            </table>
                        </div>
    </section>
</div>
<script type="text/javascript">
    function modificar(id_red) {

        $.ajax({
            type: "POST",
            url: "/bo/tipo_red/modificar_red",
            data: {id: id_red},
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: 'Attention !!!',
                })//fin done ajax
            });//Fin callback bootbox
    }

    function eliminar(id) {

        $.ajax({
            type: "POST",
            url: "/auth/show_dialog",
            data: {message: 'Sure you want to remove this Network ?'},
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: 'Attention !!!',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {

                                $.ajax({
                                    type: "POST",
                                    url: "/bo/admin/kill_tipo_red",
                                    data: {id: id}
                                })
                                    .done(function (msg) {
                                        bootbox.dialog({
                                            message: msg,
                                            title: 'Attention !!!',
                                            buttons: {
                                                success: {
                                                    label: "Accept",
                                                    className: "btn-success",
                                                    callback: function () {
                                                        location.href = "/bo/tipo_red/mostrar_redes";
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
                            callback: function () {

                            }
                        }
                    }
                })
            });
    }

    function estado(estatus, id) {

        $.ajax({
            type: "POST",
            url: "/bo/tipo_red/cambiar_estado",
            data: {
                id: id,
                estado: estatus
            },
        }).done(function (msg) {
            bootbox.dialog({
                message: msg,
                title: 'Attention !!!',
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

        })// Done ajax
    }

</script>

