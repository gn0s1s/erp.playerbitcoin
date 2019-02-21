<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                <a class="backHome" href="/bo">
                    <i class="fa fa-home"></i> Menu</a>
                <span>
							<a href="/ov/tickets"> > Tickets</a>
							 > New Manual
                            </span>
            </h1>
        </div>
    </div>
    <!-- row -->
    <div class="row">


    </div>
    <!-- end row -->

    <!-- row -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well">

                <section id="widget-grid" class="">

                    <!-- row -->
                    <div class="row">

                        <!-- new  WIDGET START -->
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget jarviswidget-color-purity" id="wid-id-1"
                                 data-widget-editbutton="false" data-widget-colorbutton="true">
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
                                <!-- widget content -->
                                <div class="widget-body" style="padding : 0;">
                                    <div id="myTabContent1" class="col-md-6 tab-content ">

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th><i class="fa fa-sitemap"></i> Network</th>
                                                    <th><i class="fa fa-money"></i> Commission</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php if (isset($psr)) :
                                                    foreach ($psr as $index => $pasive) :
                                                        #TODO: bono pasivo
                                                        ?>
                                                        <tr class="info">
                                                            <td colspan="2"><b>
                                                                    PSR <?= $index + 1; ?> $ <?= $pasive->costo; ?>
                                                                </b></td>
                                                        </tr>
                                                    <?php endforeach;
                                                endif; ?>
                                                <?php
                                                $total = 0;
                                                $i = 0;
                                                $total_transact = 0;
                                                //var_dump($comision_todo);

                                                for ($i = 0; $i < sizeof($comision_todo["redes"]); $i++) {

                                                    $totales = (intval($comision_todo["ganancias"][$i][0]->valor) <> 0 || sizeof($comision_todo["bonos"][$i]) <> 0) ? 0 : 'FAIL';

                                                    //echo $totales."|";

                                                    if ($totales !== 'FAIL') {
                                                        echo '<tr class="success" >
																<td colspan="2"><b>' . $comision_todo["redes"][$i]->nombre . '</b></td>
															</tr>';
                                                    }


                                                    if ($comision_todo["ganancias"][$i][0]->valor <> 0) {
                                                        echo '<tr class="success" >
																<td colspan="2"><i class="fa fa-money"></i>Commissions</td>
															</tr>';

                                                        echo '<tr class="success">
															<td>&nbsp;&nbsp;Sponsored Commissions</td>
																<td>$ ' . number_format($comision_todo["directos"][$i][0]->valor, 2) . '</td>
															</tr>';

                                                        echo '<tr class="success">
															<td>&nbsp;&nbsp;Spillover Commissions</td>
																<td>$ ' . number_format($comision_todo["ganancias"][$i][0]->valor - $comision_todo["directos"][$i][0]->valor, 2) . '</td>
															</tr>';

                                                        if ($comision_todo["ganancias"][$i][0]->valor) {
                                                            $totales += ($comision_todo["ganancias"][$i][0]->valor);
                                                        }


                                                    }

                                                    if ($comision_todo["bonos"][$i]) {
                                                        echo '<tr class="success" >
																<td colspan="2"><i class="fa fa-gift"></i>Calculated Commissions</td>
															</tr>';
                                                        for ($k = 0; $k < sizeof($comision_todo["bonos"][$i]); $k++) {
                                                            if ($comision_todo["bonos"][$i][$k]->valor <> 0) {
                                                                $totales += ($comision_todo["bonos"][$i][$k]->valor);
                                                                echo '<tr class="success">
																<td>&nbsp;&nbsp;' . $comision_todo["bonos"][$i][$k]->nombre . '</td>
																	<td>$ ' . number_format($comision_todo["bonos"][$i][$k]->valor, 2) . '</td>
																</tr>';
                                                            }
                                                        }
                                                    }

                                                    if ($totales <> 0) {
                                                        echo '<tr class="warning">
																<td>&nbsp; Total </td>
																<td>$ ' . number_format($totales, 2) . '</td>
															</tr>';
                                                        $total += ($totales);
                                                    }

                                                }

                                                ?>
                                                <tr class="success">
                                                    <td><h4><b>TOTAL</b></h4></td>
                                                    <td>
                                                        <div class="col-md-4">
                                                            <h4><b>$ <?php echo number_format($total, 2); ?></b></h4>
                                                        </div>
                                                        <?php if ($total !== 0) { ?>
                                                            <div class="col-md-1">
                                                                <a title='Show Details' style='cursor: pointer;'
                                                                   class='txt-color-green'
                                                                   onclick='ventas(<?= $id ?>);'>
                                                                    <i class='fa fa-eye fa-3x'></i>
                                                                </a>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                </tr>

                                                <?php if ($transaction) { ?>
                                                    <tr class="warning">
                                                        <td ><b>Wallet movements</b></td>
                                                        <td><a title='Show Details' style='cursor: pointer;'
                                                               class='txt-color-green' onclick='ver(<?= $id ?>);'><i
                                                                        class='fa fa-eye fa-3x'></i></a></td>
                                                    </tr>
                                                    <?php if ($transaction['add']) {
                                                        $total_transact += $transaction['add'];
                                                        ?>
                                                        <tr class="warning">
                                                            <td><b>Total Added</b></td>
                                                            <td>
                                                                <b style="color: green">$ <?php echo number_format($transaction['add'], 2); ?></b>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    if ($transaction['sub']) {
                                                        $total_transact -= $transaction['sub'];
                                                        ?>
                                                        <tr class="warning">
                                                            <td><b>Total removed</b></td>
                                                            <td>
                                                                <b style="color: red">$ <?php echo number_format($transaction['sub'], 2); ?></b>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr class="warning">
                                                        <td><b>TOTAL:</b></td>
                                                        <td><h4>
                                                                <b>$ <?php echo number_format($total_transact, 2); ?></b>
                                                            </h4></td>
                                                    </tr>
                                                <?php } ?>

                                                </tbody>
                                            </table>

                                        </div>


                                        <table id="dt_basic" class="table table-striped table-bordered table-hover">

                                            <?php
                                            $retenciones_total = 0;
                                            foreach ($retenciones as $retencion) { ?>
                                                <tr class="danger">
                                                    <td><b>Retencion
                                                            por <?php echo $retencion['descripcion']; ?></b></b></td>
                                                    <td></td>
                                                    <td>$ <?php
                                                        $retenciones_total += $retencion['valor'];
                                                        echo number_format($retencion['valor'], 2); ?></td>
                                                </tr>
                                                <?php $total;
                                            } ?>

                                            <tr class="danger">
                                                <td><b>Pending Withdrawal</b></td>
                                                <td></td>
                                                <td>$ <?php
                                                    if ($cobroPendientes == null)
                                                        echo "0";
                                                    else
                                                        echo number_format($cobroPendientes, 2);
                                                    ?></td>
                                            </tr>

                                            <?php foreach ($cobro as $cobros) {
                                                ?>
                                                <tr class="danger">
                                                    <td><b>Succeeded Withdrawal</b></td>
                                                    <td></td>
                                                    <td>$
                                                        <?php
                                                        if ($cobros->monto == null) {
                                                            echo '0';
                                                            $cobro = 0;
                                                        } else {
                                                            echo number_format($cobros->monto, 2);
                                                            $cobro = $cobros->monto;
                                                        }
                                                        ?></td>
                                                </tr>
                                                <?php
                                            } ?>
                                            <tr class="info">
                                                <td><h4><b>Clear Status </b></h4>
                                                <td></td>
                                                <td><h4><b>
                                                            $
                                                            <?php
                                                            $saldo_neto = ($total - ($cobro + $retenciones_total + $cobroPendientes) + ($total_transact));
                                                            if ($saldo_neto < 0)
                                                                echo 0;
                                                            else
                                                                echo number_format($saldo_neto, 2)
                                                            ?></b></h4></td>
                                            </tr>
                                        </table>

                                    </div>

                                    <?php $deposit = $saldo_neto;
                                    if ($saldo_neto < 0)
                                        $deposit = 0;

                                    $deposit = $saldo_deposit;
                                    $round = round($bitcoin, 2);
                                    ?>
                                    <form action="send_mail" method="post" id="contact-form1"
                                          class="smart-form col-xs-12 col-md-6">
                                        <fieldset class="">

                                            <section class="padding-10 alert-info col-md-3 text-right">
                                                <h1>1 BTC <br/> <strong id="bitcoin_val"><?= $round ?> USD</strong></h1>
                                            </section>
                                            <section class="col col-4">
                                                <label class="label "><b>Available Balance</b></label>
                                                <label class="input input state-success">
                                                    <input type="text" name="saldo" class="from-control" id="saldo"
                                                           value="<?= $deposit; ?>" readonly/>
                                                </label>
                                            </section>

                                            <section class="col col-4">
                                                <label class="label"><b>Final Balance</b></label>
                                                <label class="input state-disabled state-error">
                                                    <input type="number" disabled="disabled" name="neto"
                                                           id="neto" class="from-control"
                                                           value="<?= round($deposit - 5, 2); ?>" readonly/>
                                                </label>
                                            </section>
                                        </fieldset>

                                        <fieldset>
                                            <div class="col col-md-12 row buttonsticket">

                                                <div class="col col-md-3 pull-right">
                                                    <a style="cursor: pointer;"
                                                       onclick="add_ticket()">
                                                        Add Ticket
                                                        <i class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="col padding-10  col-md-12 row ">
                                                <legend class="col padding-10  col-md-12">Tickets</legend>
                                            </div>
                                            <br>
                                        </fieldset>
                                        <fieldset id="tickets_div" style="overflow-y: scroll;max-height: 150px">

                                            <div class="col col-md-12" id="ticket_div_1">
                                                <section id="ticket_sec_1" class="col col-sm-5">
                                                    <label class="label"><b>Ticket 1</b></label>
                                                    <label class="input">
                                                        <i class="icon-prepend fa fa-money"></i>
                                                        <input name="cobro[]" type="number" min="0.01" step="0.01"
                                                               class="from-control ticket" id="cobro_1"
                                                               onkeyup="CalcularSaldo(1)" value="<?= $round ?>"/>
                                                    </label>
                                                    <a style="cursor: pointer;" onclick="auto_val(1)">
                                                        Automatic Value
                                                        <i class="fa fa-cogs"></i></a>
                                                </section>
                                                <section id="ticket_balance_1"
                                                         class="padding-10 alert-success col col-sm-7 text-left">
                                                    <h1 id="setRange">Range between <br/>
                                                        <strong class="minRange"></strong> USD and
                                                        <strong class="maxRange"></strong> USD</h1>
                                                </section>
                                            </div>
                                        </fieldset>

                                        <footer>
                                            <button type="button" onclick="cobrar()" class="btn btn-success"
                                                    id="enviar">
                                                <i class="glyphicon glyphicon-ok"></i>
                                                Submit
                                            </button>
                                        </footer>
                                    </form>

                                </div>


                                <!-- end widget div -->
                            </div>
                            <!-- end widget -->

                        </article>
                    </div>
                </section>
                <!-- end widget grid -->
            </div>
        </div>
        <!-- row -->
    </div>
    <div class="row">
        <div class="col-sm-12">
            <br/>
            <br/>
        </div>
    </div>
    <!-- end row -->

</div>
<!-- END MAIN CONTENT -->

<!-- PAGE RELATED PLUGIN(S)
<!-- Morris Chart Dependencies -->
<script src="/template/js/plugin/morris/raphael.min.js"></script>
<script src="/template/js/plugin/morris/morris.min.js"></script>

<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
    // PAGE RELATED SCRIPTS

    /*
     * Run all morris chart on this page
     */
    $(document).ready(function () {

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        pageSetUp();

        CalcularSaldo();

        var id_fee = $('.ticket').length;
        if (id_fee > 1) {
            button_ticket_del();
        }
    });

    function ver(id, fecha) {
        $.ajax({
            type: "POST",
            url: "/ov/billetera2/historial_transaccion",
            data: {id: id}
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: 'Transactions Report',
                    buttons: {
                        danger: {
                            label: "Close",
                            className: "btn-danger",
                            callback: function () {

                            }
                        }
                    }
                })//fin done ajax
            });//Fin callback bootbox
    }

    //setup_flots();
    /* end flot charts */

    function button_ticket_del() {
        $('.buttonsticket').append('<div class="rm_div_ticket pull-right col col-md-3">\n' +
            ' <a style="cursor: pointer;color:red !important" \n' +
            '  onclick="del_ticket()">\n' +
            '  Delete Ticket \n' +
            '  <i class="fa fa-minus-circle"></i></a>\n' +
            ' </div>');
    }

    function del_ticket() {

        var id_ticket = $('.ticket').length;
        console.log('#ticket_div_' + id_ticket);
        $('#ticket_div_' + id_ticket).remove();
        if (id_ticket <= 2) {
            $('.rm_div_ticket').remove();
        }
        reload_bitcoin();
    }

    function add_ticket() {

        var id_ticket = $('.ticket').length;

        $.ajax({
            type: "POST",
            url: "/ov/tickets/add_ticket",
            data: {id: id_ticket}
        })
            .done(function (msg) {

                $('#tickets_div').append(msg);
                reload_bitcoin();
                CalcularSaldo();
                if (id_ticket == 1) {
                    button_ticket_del();
                }

            });//Fin callback bootbox
    }

    function reload_bitcoin() {
        $.ajax({
            type: "POST",
            url: "/ov/tickets/bitcoin_val",
            data: {}
        })
            .done(function (msg) {

                var bitcoin = Number(parseFloat(msg).toFixed(2)) + " USD";
                $('#bitcoin_val').html(bitcoin);
                reload_neto();
            });//Fin callback bootbox
    }

    function auto_val(id) {
        $.ajax({
            type: "POST",
            url: "/ov/tickets/auto_val",
            data: {}
        })
            .done(function (msg) {

                var bitcoin = Number(parseFloat(msg).toFixed(2));
                $("#cobro_" + id).val(bitcoin);
                reload_bitcoin();
                CalcularSaldo();

            });//Fin callback bootbox
    }

    function CalcularSaldo(id = false) {

        reload_neto();
        if (!id) {
            var tickets = $('.ticket').length;
            for (var i = 0; i < tickets; i++) {
                var index = i + 1;
                CalcularSaldo(index);
            }
            return true;
        }

        var saldo = $("#cobro_" + id).val();
        var min_value = parseInt(saldo / 5);
        min_value *= 5;
        var max_value = min_value + 5;
        max_value -= 0.01;

        $('#ticket_balance_' + id + ' .minRange').html(min_value + "");
        $('#ticket_balance_' + id + ' .maxRange').html(max_value + "");

    }

    function reload_neto(){
        var tickets = $('.ticket').length;
        var tarifa = <?=$tarifa;?>;
        var saldo = $('#saldo').val();
        var neto = saldo - (tarifa*tickets);
        $('#neto').val(neto);

        if(neto<=0){
            $('#enviar').attr("disabled", true);
        }else{
            $('#enviar').removeAttr("disabled");
        }
    }

    function ventas(id) {
        $.ajax({
            type: "POST",
            url: "/ov/billetera2/ventas_comision",
            data: {id: id}
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: 'Commission Details',
                    buttons: {
                        danger: {
                            label: "Close",
                            className: "btn-danger",
                            callback: function () {

                            }
                        }
                    }
                })//fin done ajax
            });//Fin callback bootbox
    }

    function enviar() {
        $.ajax({
            type: "POST",
            url: "/auth/show_dialog",
            data: {message: 'Sure you want to get this(ese) ticket(s) ?'},
        })
            .done(function (msg) {

                bootbox.dialog({
                    message: msg,
                    title: 'New Tickets',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {
                                iniciarSpinner();
                                $.ajax({
                                    type: "POST",
                                    url: "/ov/tickets/newTicket",
                                    data: $('#contact-form1').serialize()
                                })
                                    .done(function (msg) {
                                        FinalizarSpinner();
                                        bootbox.dialog({
                                            message: msg,
                                            title: '',
                                            buttons: {
                                                success: {
                                                    label: "Accept",
                                                    className: "btn-success",
                                                    callback: function () {
                                                        location.href = '/listTickets';
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

    function cobrar() {

        if (!validarCampos()) {
            alert("Please, verify ticket data");
            return false;
        }

        enviar();
    }

    function validarCampos() {

        if ($('#neto').val() <= 0)
            return false;

        return true;
    }
</script>
