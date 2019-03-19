<?php
$rangos = "";
foreach ($rangosActivos as $categoria) :
    $rangos .= '<option value="'.$categoria->id_rango.'">'.
                    $categoria->nombre_rango
                .'</option>';
endforeach;
?>
<script type="text/javascript">
    var i = 1;
    var j = 0;
</script>
<div id="spinner-div"></div>
<form id="bonos" action="/bo/bonos/nuevo_bono" method="POST" role="form" class="widget-body no-padding smart-form">
    <input id="id" class="form-control" name="id" style="width:200px; height:30px;" required="" type="hidden"
           value="<?= $bono[0]->id; ?>">
    <label style="margin: 1rem;" class="input"><i class="icon-prepend fa fa-check-circle-o"></i>
        <input id="nombre" class="form-control" name="nombre" style="width:200px; height:30px;" placeholder="Name"
               required="" type="text" value="<?= $bono[0]->nombre; ?>">
    </label>
    <label style="margin: 1rem;">
        <textarea id="mymarkdown" name="descripcion" class="form-control" name="desc" size="20" cols="20" rows="10"
                  placeholder="Descripción" type="text" required=""><?= $bono[0]->descripcion; ?></textarea>
    </label>
    <div style="margin: 1rem;">
        <h4>Frecuencia</h4>
        <label class="radio">
            <input value="UNI" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'UNI') echo 'checked=""'; ?>>
            <i></i>Unica</label>
        <label class="radio">
            <input value="ANO" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'ANO') echo 'checked=""'; ?>>
            <i></i>Anual</label>
        <label class="radio">
            <input value="MES" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'MES') echo 'checked=""'; ?>>
            <i></i>Mensual</label>
        <label class="radio">
            <input value="QUI" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'QUI') echo 'checked=""'; ?>>
            <i></i>Quincenal</label>
        <label class="radio">
            <input value="SEM" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'SEM') echo 'checked=""'; ?>>
            <i></i>Semanal</label>
        <label class="radio">
            <input value="DIA" name="frecuencia" placeholder="frecuencia"
                   type="radio" <?php if ($bono[0]->frecuencia == 'DIA') echo 'checked=""'; ?>>
            <i></i>Diario</label>
        <br><br>
        <label class="checkbox">
            <input name="plan" <?php if ($bono[0]->plan == "SI") echo "checked='checked'"; ?> type="checkbox">
            <i></i>Remanenete
        </label>
    </div>
    <div class="form-group">
        <div class="" id="cross_tipo_rango">
            <legend style="margin-left: 2em;">Parameters</legend>
            <br><br>
            <div class="row">
                <div class="col col-lg-3 col-xs-2">
                </div>
            </div>

            <div class="row" id="rango">
                <div class="row">
                    <div class="col col-lg-3 col-xs-2">
                    </div>
                    <div class="col col-lg-2 col-xs-2">
                        <a style="cursor: pointer;" onclick="add_rango()">
                            Add Parameter <i class="fa fa-plus"></i>
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col col-lg-2">
                    </div>
                    <div class="col col-xs-12 col-sm-12 col-lg-10">
                        <label class="select">Parameter Name
                            <select id="id_rango0" name="id_rango[]"
                                    style="max-width: 20rem;"
                                    onChange="set_rango($(this).val(),'rango0');">
                                <option value='0'>--- choose Parameter ---</option>
                                <?php
                                foreach ($rangosActivos as $categoria) : ?>
                                    <option value='<?=$categoria->id_rango;?>'>
                                        <?=$categoria->nombre_rango;?>
                                    </option>";
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </label>
                        <div id="rango0" style="margin: 1rem;">
                        </div>
                    </div>
                    <div class="col col-xs-12 col-sm-5 col-lg-3">
                    </div>
                </div>
            </div>
            <br>
            <fieldset style="margin: 2rem;">
                <legend>
                    Date settings
                </legend>
                <div class="row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Meses of Afiliacion</label>
                            <input class="form-control spinner-both"
                                   id="mesDesdeAfiliacion" name="mesDesdeAfiliacion"
                                   value="<?= $bono[0]->mes_desde_afiliacion; ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-2 col-lg-2">
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>Actived Months</label>
                            <input class="form-control spinner-both"
                                   id="mesDesdeActivacion" name="mesDesdeActivacion"
                                   value="<?= $bono[0]->mes_desde_activacion; ?>">
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>
            <label style="margin: 1rem;"><h2>Fecha of Activacion</h2></label>
            <div style="margin: 1rem;">
                <section class="col col-4">
                    <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                        <input required id="inicio"  type="text" name="inicio"
                               value="<?= $bono[0]->inicio; ?>" placeholder="Fecha of Inicio">
                    </label>
                </section>
                <section class="col col-4">
                    <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                        <input required id="fin" type="text" name="fin"
                               value="<?= $bono[0]->fin; ?>" placeholder="Fecha of Fin">
                    </label>
                </section>
                <br><br><br>
                <h4></h4>

                <legend>Calculated Commissions Por Nivel</legend>
                <br><br>
                <div class="row">
                    <div class="col col-lg-3 col-xs-2">
                    </div>
                </div>

                <div class="row" id="niveles">
                    <div class="row">
                        <div class="col col-lg-1 col-xs-2">
                        </div>
                        <div class="col col-lg-4 col-xs-2">
                            <a style="cursor: pointer;" onclick="add_nivel()"> Add Nivel <i class="fa fa-plus"></i></a>
                            <a style="cursor: pointer;" onclick="reload_niveles()">&nbsp&nbsp&nbsp<i
                                        style="font-size: 2rem;color: #60A917 !important;"
                                        class="fa fa-refresh"></i></a>
                        </div>

                    </div>
                    <?php
                    $contador = 0;
                    foreach ($valorNiveles as $nivel) {
                        ?>
                        <div id="nivel<?= $contador; ?>" class="row">
                            <div class="col col-lg-1">
                            </div>
                            <div class="col col-lg-1">
                                <span style="margin: 5rem;">Nivel</span>
                                <label style="margin: 0.2rem;" class="input"><i class="icon-prepend fa fa-sitemap"></i>
                                    <input class="form-control" style="width:100px; height:30px;"
                                           name="id_niveles_bonos[]" size="20" required type="number" readonly
                                           value="<?= $nivel->nivel; ?>">
                                </label>
                            </div>
                            <div class="col col-lg-1">
                            </div>
                            <div class="col col-lg-2">
                                <label style="margin: 0.2rem;" class="select">Repartir
                                    <select name="verticalidad_red[]" style="width: 10rem;">

                                        <?php
                                        if ($nivel->verticalidad == "ASC") {
                                            echo "<option value='ASC' selected>$ Hacia Arriba</option>
														  <option value='DESC'>$ Hacia Abajo</option>
														  <option value='PASC'>%(Puntos) Hacia Arriba</option>
														  <option value='RDESC'>%(Puntos)Residual Abajo</option>
														  ";
                                        } else if ($nivel->verticalidad == "DESC") {
                                            echo "<option value='ASC'>$ Hacia Arriba</option>
														  <option value='DESC' selected>$ Hacia Abajo</option>
														  <option value='PASC'>%(Puntos) Hacia Arriba</option>
														  <option value='RDESC'>%(Puntos)Residual Abajo</option>
														  ";
                                        } else if ($nivel->verticalidad == "PASC") {
                                            echo "<option value='ASC'>$ Hacia Arriba</option>
														  <option value='DESC'>$ Hacia Abajo</option>
														  <option value='PASC' selected>%(Puntos) Hacia Arriba</option>
														  <option value='RDESC'>%(Puntos)Residual Abajo</option>
														  ";
                                        } else if ($nivel->verticalidad == "RDESC") {
                                            echo "<option value='ASC'>$ Hacia Arriba</option>
														  <option value='DESC'>$ Hacia Abajo</option>
														  <option value='PASC'>%(Puntos) Hacia Arriba</option>
														  <option value='RDESC' selected>%(Puntos)Residual Abajo</option>
														  ";
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col col-lg-2">
                                <label style="margin: 0.2rem;" class="select">Condicion
                                    <select name="condicion_red[]" style="width: 10rem;">

                                        <?php
                                        if ($nivel->condicion_red == "RED")
                                            echo "<option value='RED' selected>Toda the Network</option>";
                                        else
                                            echo "<option value='RED'>Toda the Network</option>";

                                        if ($nivel->condicion_red == "DIRECTOS")
                                            echo "<option value='DIRECTOS' selected>Directos Afiliado</option>";
                                        else
                                            echo "<option value='DIRECTOS'>Directos Afiliado</option>";

                                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col col-lg-4">
                                <label style="margin: 2rem;" class="input"><i class="icon-prepend fa fa-money"></i>
                                    <input class="form-control" style="width:150px; height:30px;" name="valor[]"
                                           step="any" size="20" placeholder="Valor del Bono" required type="number"
                                           value="<?= $nivel->valor; ?>">
                                </label>
                                <?php if ($contador > 0) : ?>
                                    <a style="cursor: pointer;color: red;" onclick="delete_nivel(<?= $contador; ?>)">Eliminar
                                        Nivel <i class="fa fa-minus"></i></a>
                                <?php endif; ?>
                            </div>
                            <br>
                        </div>
                        <script type="text/javascript">j++;</script>
                        <?php $contador++;
                    } ?>

                    <div id="niveles">
                    </div>
                </div>
                <br>
                <button id="boton" style="margin: 1rem;margin-bottom: 4rem;" type="input" class="btn btn-success">
                    Update
                </button>


            </div>
        </div>
</form>

<script type="text/javascript">

    $('#inicio').datepicker({
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: "yy-mm-dd",
        changeYear: true,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function (selectedDate) {
            $('#fin').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#fin').datepicker({
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: "yy-mm-dd",
        changeYear: true,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onSelect: function (selectedDate) {
            $('#inicio').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $("#mesDesdeAfiliacion").spinner({
        min: 0,
        max: 36,
        step: 1,
        start: 1000,
        numberFormat: "C"
    });

    $("#mesDesdeActivacion").spinner({
        min: 0,
        max: 36,
        step: 1,
        start: 1000,
        numberFormat: "C"
    });

    pageSetUp();


    $("#mymarkdown").markdown({
        autofocus: false,
        savable: false
    })

    $("#bonos").submit(function (event) {
        event.preventDefault();
        if (validarRedes()) {
            setiniciarSpinner();
            enviar();
        }
    });

    function validarRedes() {
        if ($("#divRedes")[0]) {
            return true;
        }
        alert("Choose some range");
        return false;
    }

    function enviar() {
        enableButton();
        $.ajax({
            type: "POST",
            url: "/bo/bonos/actualizar_bono",
            data: $('#bonos').serialize()
        })
            .done(function (msg) {

                bootbox.dialog({
                    message: "Se ha Modificado the Bono." + msg,
                    title: 'Congratulations',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {

                                location.href = "/bo/bonos/listar";
                                FinalizarSpinner();
                            }
                        }
                    }
                })

            });//fin Done ajax

    }

    function add_rango() {
        var code = '<div id="' + i + '" class="row">'
            + '<div class="col col-lg-2">'
            + '</div>'
            + '<div class="col col-xs-12 col-sm-12 col-lg-10">'
            + '<label class="select">Parameter name'
            + '<select id="id_rango' + i + '" style="max-width: 20rem;" '
            + 'name="id_rango[]" onChange="set_rango($(this).val(),\'rango' + i + '\');">'
            + '<option value="0">--- choose Parameter ---</option>'
            + '<?php  echo $rangos; ?>'
            + '</select>'
            + '</label>'
            + '<div id="rango' + i + '" style="margin: 1rem;">'
            + '</div>'
            + '<a style="cursor: pointer;color: red;" onclick="delete_rango(' + i + ')">'
            + 'Delete Parameter<i class="fa fa-minus"></i></a>'
            + '</div>'
            + '</div>';
        $("#rango").append(code);
        i = i + 1
    }

    function add_nivel() {
        var code = '<div id="nivel' + j + '" class="row">'
            + '<div class="col col-lg-1">'
            + '</div>'
            + '<div class="col col-lg-2">'
            + '<span >Level</span>'
            + '<label style="margin: 0.2rem;" class="input">'
            + '<i class="icon-prepend fa fa-sitemap"></i>'
            + '<input class="form-control" style="width:100px; height:30px;" '
            + 'name="id_niveles_bonos[]" size="20" value="' + j + '" required="" '
            + 'type="number" min="1">'
            + '</label>'
            + ' </div>'
            + '<div class="col col-xs-12 col-sm-6 col-lg-2" id="v_condicion">'
            + '<label class="select">Commission side'
            + '<select name="verticalidad_red[]" style="width: 10rem;">'
            + '<option value="ASC">$ Upside</option>'
            + '<option value="DESC">$ Downside</option>'
            + '<option value="PASC">%(Points) Upside</option>'
            + '<option value="RDESC">%(Points) Downside</option>'
            + '</select>'
            + '</label>'
            + '</div>'
            + '<div class="col col-xs-12 col-sm-6 col-lg-2" id="tipo_condicion">'
            + '<label class="select">Parameter'
            + '<select name="condicion_red[]" style="width: 10rem;">'
            + '<option value="RED">All Referrals</option>'
            + '<option value="DIRECTOS">Only Sponsored</option>'
            + '</select>'
            + '</label>'
            + '</div>'
            + '<div class="col col-lg-4">'
            + '	<label class="input" style="margin: 2rem;">'
            + '<i class="icon-prepend fa fa-money"></i>'
            + '		<input class="form-control" style="width:150px; height:30px;" '
            + 'name="valor[]" size="20" placeholder="Valor del Bono" '
            + 'required="" type="number" step="any">'
            + '	</label>'
            + '<a style="cursor: pointer;color: red;" onclick="delete_nivel(' + j + ')">'
            + 'Eliminar Nivel <i class="fa fa-minus"></i></a>'
            + '</div>'
            + '</div>';
        $("#niveles").append(code);
        j = j + 1;
    }

    function delete_rango(id) {
        $("#" + id + "").remove();

    }

    function delete_nivel(id) {
        $("#nivel" + id + "").remove();
    }

    function reload_niveles() {
        var code = '<div class="row">'
            + '<div class="col col-lg-1 col-xs-1">'
            + '</div>'
            + '<div class="col col-lg-4 col-xs-2">'
            + '<a style="cursor: pointer;" onclick="add_nivel()"> Add Level '
            + '<i class="fa fa-plus"></i></a>'
            + '<a style="cursor: pointer;" onclick="reload_niveles()">&nbsp&nbsp&nbsp'
            + '<i style="font-size: 2rem;color: #60A917 !important;" class="fa fa-refresh"></i>'
            + '</a>'
            + '</div>'
            + '</div>'
            + '<div class="row">'
            + '<div class="col col-lg-1">'
            + '</div>'
            + '<div class="col col-lg-2">'
            + '<span style="margin: 2rem;">Level</span>'
            + '<label style="margin: 0.2rem;" class="input">'
            + '<i class="icon-prepend fa fa-sitemap"></i>'
            + '<input class="form-control" style="width:100px; height:30px;" '
            + 'name="id_niveles_bonos[]" size="20" value="0" required="" type="number" readonly>'
            + '</label>'
            + '</div>'
            + '<div class="col col-xs-12 col-sm-6 col-lg-2" id="v_condicion">'
            + '<label class="select">Commission Side'
            + '<select name="verticalidad_red[]" style="width: 10rem;">'
            + '<option value="ASC">$ Upside</option>'
            + '<option value="DESC">$ Downside</option>'
            + '<option value="PASC">%(Points) Upside</option>'
            + '<option value="RDESC">%(Points) Downside</option>'
            + '</select>'
            + '</label>'
            + '</div>'
            + '<div class="col col-xs-12 col-sm-6 col-lg-2" id="tipo_condicion">'
            + '<label class="select">Parameter'
            + '<select name="condicion_red[]" style="width: 10rem;">'
            + '<option value="RED">All Referrals</option>'
            + '<option value="DIRECTOS">Only Sponsored</option>'
            + '</select>'
            + '</label>'
            + '</div>'
            + '<div class="col col-lg-2">'
            + '<label style="margin: 2rem;" class="input"><i class="icon-prepend fa fa-money"></i>'
            + '<input class="form-control" style="width:150px; height:30px;" '
            + 'name="valor[]" size="20" placeholder="Valor del Bono" required="" '
            + 'type="number" step="any">'
            + '</label>'
            + '</div>'
            + '</div>'
            + '<div id="niveles">'
            + '</div>'
            + '</div>';
        $("#niveles").html(code);
        j = 1;
    }

    function set_rango(idRango, idDivRango) {

        var id_bono = <?= $bono[0]->id;?>;
        $.ajax({
            type: "POST",
            url: "/bo/bonos/set_Rango",
            data: {idRango: idRango,id_bono:id_bono}
        })
            .done(function (msg) {
                $('#' + idDivRango + '').html(msg);
            });
    }

    function cargar_niveles_red(idRed, idDivRango, idRangoDiv) {
        $.ajax({
            type: "POST",
            url: "/bo/bonos/set_Frontalidad_Profundidad_Red",
            data: {idRed: idRed, idRangoDiv: idRangoDiv}
        })
            .done(function (msg) {
                $('#' + idDivRango + '').html(msg);
            });
    }

    function cargar_mercancia_red(idRed, idDivRango, idRangoDiv) {
        $.ajax({
            type: "POST",
            url: "/bo/bonos/set_tipos_mercancia",
            data: {idRed: idRed, idRangoDiv: idRangoDiv}
        })
            .done(function (msg) {
                $('#' + idDivRango + '').html(msg);
            });
    }

    function set_mercancia(idTipoMercancia, idDivRango, idRedes, idRangoDiv) {

        $.ajax({
            type: "POST",
            url: "/bo/bonos/set_mercancia",
            data: {
                idTipoMercancia: idTipoMercancia,
                idRedes: idRedes, idRangoDiv: idRangoDiv
            }
        })
            .done(function (msg) {
                $('#' + idDivRango + '').html(msg);
            });

    }

    function enableButton() {
        $("#boton").prop("disabled", true);

    }

    function set_datos_rango_bono(idRango, idTipoRango, idDivRango) {

        $.ajax({
            type: "POST",
            url: "/bo/bonos/set_Rango",
            data: {idRango: idRango}
        })
            .done(function (msg) {
                $('#' + idDivRango + '').html(msg);

            });
    }

    function select_rango(idRango, idDivRango) {
        $('#id_' + idDivRango).val(idRango).change();

    }

    function select_redes_condiciones(idRango, idTipoRango, redes, condiciones1, condiciones2) {

        var myVar = setInterval(function () {
            $('#id_red' + idRango + "" + idTipoRango).val(redes).change()
            clearInterval(myVar);
            select_condicion1(idRango + "" + idTipoRango, condiciones1, condiciones2);
        }, 5000);

    }

    function select_condicion1(idCondicion, condiciones1, condiciones2) {
        var myVar = setInterval(function () {
            $('#id_condicion_1' + idCondicion).val(condiciones1).change()
            clearInterval(myVar);
            select_condicion2(idCondicion, condiciones2);
        }, 2000);
    }

    function select_condicion2(idCondicion, condiciones2) {
        var myVar = setInterval(function () {
            $('#id_condicion_2' + idCondicion).val(condiciones2).change()
            clearInterval(myVar);
        }, 2000);
    }

    <?php
    $contador = 0;
    foreach ($rangosBono as $rango):
    if($contador > 0): ?>
    add_rango();
    <?php endif;?>
    select_rango(<?= $rango->id_rango;?>, 'rango<?= $contador;?>');
    <?php
    $contador++;
    endforeach;

    foreach ($tipoRangosBono as $tipoRango){
    ?>

    var idRed = [];
    var idCondicion1 = [];
    var idCondicion2 = [];
    <?php
    foreach ($redCondicionesBono as $condicion):
    $isCondicionRango = $tipoRango->id_rango == $condicion->id_rango;
    $isTipoRango = $tipoRango->id_tipo_rango == $condicion->id_tipo_rango;
    if($isCondicionRango && $isTipoRango):?>
    idRed.push(<?= $condicion->id_red;?>);
    <?php endif;
    endforeach;

    foreach ($condicionesBono as $condicion):
    $isCondicionRango = $tipoRango->id_rango == $condicion->id_rango;
    $isTipoRango = $tipoRango->id_tipo_rango == $condicion->id_tipo_rango;
    if($isCondicionRango && $isTipoRango):?>
    idCondicion1.push(<?= $condicion->condicion1;?>);
    idCondicion2.push(<?= $condicion->condicion2;?>);
    <?php endif;
    endforeach;
    ?>

    select_redes_condiciones(<?= $tipoRango->id_rango;?>,<?= $tipoRango->id_tipo_rango;?>, idRed, idCondicion1, idCondicion2);

    <?php

    }

    ?>


</script>
<style>
    .link {
        margin: 0.5rem;
    }

    .minh {
        padding: 50px;
    }

    .link a:hover {
        text-decoration: none !important;
    }
</style>
		
