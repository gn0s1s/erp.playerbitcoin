<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span><a href="/ov/accountStatus">> Wallet Status</a> > Recent Status</span></h1>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <h1 class="page-title txt-color-blueDark"><i style="color: #5B835B;" class="fa fa-money"></i> My Earnings
                <span class="txt-color-black"><b>$ <?= number_format($comisiones + $total_bonos, 2) ?> </b></span></h1>
        </div>
    </div><!-- row -->
    <div class="row">


    </div><!-- end row --> <!-- row -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well">

                <section id="widget-grid" class=""><!-- row -->
                    <div class="row"><!-- new  WIDGET START -->
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget jarviswidget-color-purity" id="wid-id-1"
                                 data-widget-editbutton="false" data-widget-colorbutton="true"><!-- widget options:
											usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
							
											data-widget-colorbutton="false"
											data-widget-editbutton="false"
											data-widget-togglebutton="false"
											data-widget-deletebutton="false"
											data-widget-fullscreenbutton="false"
											data-widget-custombutton="false"
											data-widget-collapsed="true"
											data-widget-sortable="false"
							
											--> <!-- widget content -->
                                <div class="widget-body">
                                    <div id="myTabContent1" class="tab-content padding-10">
                                        <h1 class="text-center"></h1>

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th><i class="fa fa-sitemap"></i> Network</th>
                                                    <th><i class="fa fa-money"></i> Commission</th>
                                                </tr>
                                                </thead>
                                                <tbody>   <?php

                                                function setPSRs($psr,$comisiones)
                                                {
                                                    $sum_psr =  0;
                                                    foreach ($comisiones as $comision):
                                                        if($comision["comisiones"])
                                                            $sum_psr += $comision["comisiones"]["indirectos"];
                                                    endforeach;
                                                    $sum_psr =  0;
                                                    if(isset($comisiones[1]) )
                                                        $sum_psr = $comisiones[1]["comisiones"]["indirectos"];

                                                    $data_psr = array();
                                                    if (isset($psr)) :
                                                        foreach ($psr as $index => $pasive) :
                                                            list($data,$sum_psr) = setPSR($pasive,$sum_psr);
                                                            $data_psr[$index] = $data;
                                                        endforeach;
                                                    endif;
                                                    return $data_psr;
                                                }

                                                function setPSR($pasive,$sum = 0)
                                                {
                                                    #TODO: bono pasivo
                                                    $cent = 100;
                                                    $acumulado = isset($pasive->amount) ? $pasive->amount : 0;
                                                    $total = $pasive->costo * 2;

                                                    $acumulado += $sum;
                                                    $sum = $acumulado - $total;
                                                    if($acumulado>$total):
                                                        $acumulado = $total;
                                                    endif;


                                                    if($sum<0)
                                                        $sum =0;

                                                    echo "<script>console.log('$pasive->id -> $sum')</script>";

                                                    $per = $cent / $total;
                                                    $percent = $per * $acumulado;
                                                    $residuo = $cent - $percent;
                                                    if ($percent > $cent)
                                                        $percent = $cent;

                                                    $color_bg = $percent >= $cent ? '#0DC143' : '#ed8c17';

                                                    $data_psr = array(
                                                        "costo" => $pasive->costo,
                                                        "per" => $percent,
                                                        "residuo" => $residuo,
                                                        "color" => $color_bg,
                                                    );

                                                    return array($data_psr,$sum);
                                                }

                                                function setComisiones($comision_todo){

                                                    $data_comisiones = array();
                                                    $redes = $comision_todo["redes"];
                                                    foreach ($redes as $i => $comision_red) :

                                                        $comisiones = $comision_todo["ganancias"][$i];
                                                        $valorGanancias = $comisiones[0]->valor;
                                                        $intGanancias = intval($valorGanancias);
                                                        $bonos = $comision_todo["bonos"][$i];
                                                        $intBono = sizeof($bonos);
                                                        $isGanancias = $intGanancias <> 0;
                                                        $isValorGanancias = $valorGanancias <> 0;
                                                        $isBono = $intBono <> 0;

                                                        $comision_directos = $comision_todo["directos"][$i];

                                                        $totales = ($isGanancias || $isBono) ? 0 : 'FAIL';

                                                        //echo $totales."|";

                                                        $isRed = ($totales !== 'FAIL') ? $comision_red->nombre : false;

                                                        $comisiones = false;
                                                        if($isValorGanancias):
                                                            $directos = $comision_directos[0]->valor;
                                                            $indirectos = $valorGanancias - $directos;
                                                            $tickets = 0;#TODO: comisiones por boletos
                                                            $comisiones = array(
                                                                "directos" => $tickets,#TODO: $directos,
                                                                "indirectos" => $valorGanancias,#TODO: $indirectos,
                                                                "total" => $valorGanancias
                                                            );
                                                        endif;

                                                        $valor_bonos = false;
                                                        if($bonos):
                                                            $valor_bonos = array();
                                                            foreach ($bonos as $k => $bono) :
                                                                $intBonoVal = $bono->valor <> 0;
                                                                $nombre_bono = $bono->nombre;
                                                                if ($intBonoVal)
                                                                    $valor_bonos[$nombre_bono] = $bono->valor;
                                                            endforeach;
                                                            $valor_bonos["total"] = array_sum($valor_bonos);
                                                        endif;

                                                        $data_comision = array(
                                                            "red" => $isRed,
                                                            "comisiones" => $comisiones,
                                                            "bonos" =>$valor_bonos
                                                        );

                                                        $data_comisiones[$i] = $data_comision;

                                                    endforeach;

                                                    return $data_comisiones;

                                                }

                                                function printPSR($data_psr){
                                                    foreach ($data_psr as $index => $value):

                                                        $percent = $value["per"];
                                                        $costo = $value["costo"];
                                                        $color_bg = $value["color"];

                                                        ?>
                                                        <tr class="psr_<?= $index ?>">
                                                            <td><b>
                                                                    PSR <?= $index + 1; ?> $ <?= $costo; ?>
                                                                </b></td>
                                                            <td>
                                                                <b class="pull-right"><?= $percent*2 ?> %</b>
                                                            </td>
                                                        </tr>
                                                        <style>
                                                            .psr_<?=$index?> {
                                                                background: linear-gradient(90deg,<?=$color_bg?> <?=$percent?>%, #1048b1 0%);
                                                                color: #fff;
                                                            }
                                                        </style>

                                                    <?php endforeach;
                                                }

                                                function printComisional($comisional)
                                                {
                                                    $decimal = 2;
                                                    $directos = $comisional["directos"];
                                                    $indirectos = $comisional["indirectos"];

                                                    $printDirectos = number_format($directos, $decimal);
                                                    $printIndirectos = number_format($indirectos, $decimal);

                                                    ?>
                                                    <tr class="info" >
                                                        <td colspan="2">
                                                            <i class="fa fa-money"></i>Commissions
                                                        </td>
                                                    </tr>
                                                    <!--  <tr class="info">
                                                        <td>&nbsp;&nbsp;Commissions by Tickets</td>
                                                        <td>$ <?=$printDirectos;?></td>
                                                    </tr> -->
                                                    <tr class="info">
                                                        <td>&nbsp;&nbsp;Commissions</td>
                                                        <td>$ <?=$printIndirectos;?></td>
                                                    </tr>

                                                    <?php
                                                }

                                                function printBonos($bonos)
                                                {
                                                    echo '<tr class="info" >
																<td colspan="2">
																<i class="fa fa-gift"></i>
																Calculated Commissions
																</td>
															</tr>';
                                                    foreach ($bonos as $nombre => $valor) :
                                                        $printBono = number_format($valor, 2);
                                                        ?>
                                                        <tr class="info">
                                                            <td>&nbsp;&nbsp;<?=$nombre;?></td>
                                                            <td>$ <?=$printBono;?></td>
                                                        </tr>
                                                    <?php
                                                    endforeach;
                                                }

                                                function printTodoComisiones($data_comisiones)
                                                {
                                                    $totales = 0;
                                                    foreach ($data_comisiones as $i => $comision) :
                                                        $total = 0;

                                                        $red = $comision["red"];
                                                        $comisional = $comision["comisiones"];
                                                        $bonos = $comision["bonos"];

                                                        if ($red) : ?>
                                                            <tr class="info">
                                                                <td colspan="2"><b><?= $red; ?></b></td>
                                                            </tr>
                                                        <?php
                                                        endif;

                                                        if ($comisional) :
                                                            $valorGanancias = $comisional["total"];

                                                            printComisional($comisional);

                                                            if ($valorGanancias) :
                                                                $total += $valorGanancias;
                                                            endif;

                                                        endif;

                                                        if ($bonos) :
                                                            printBonos($bonos);
                                                            if ($bonos["total"] > 0)
                                                                $total += $bonos["total"];
                                                        endif;

                                                        if ($total <> 0) :
                                                            $printTotales = number_format($total, 2);
                                                            ?>
                                                            <tr class="default">
                                                                <td><h5 class="no-margin">&nbsp;subtotal</h5></td>
                                                                <td>$ <?= $printTotales; ?></td>
                                                            </tr>
                                                            <?php
                                                            $totales += $total;
                                                        endif;

                                                    endforeach;
                                                    return $totales;
                                                }

                                                ?>

                                                <?php

                                                $data_comisiones = setComisiones($comision_todo);

                                                #var_dump($data_comisiones);

                                                $data_psr = setPSRs($psr, $data_comisiones);

                                                printPSR($data_psr);

                                                $totales = printTodoComisiones($data_comisiones);

                                                ?>
                                                <tr class="success">
                                                    <td><h4><b>TOTAL</b></h4></td>
                                                    <td>
                                                        <div class="col-md-3">
                                                            <h4><b>$ <?php echo number_format($totales, 2); ?></b></h4>
                                                        </div><?php if ($totales !== 0) { ?>
                                                            <div class="col-md-1"><a title='Show Details'
                                                                                     style='cursor: pointer;'
                                                                                     class='txt-color-green'
                                                                                     onclick='ventas(<?= $id ?>);'><i
                                                                        class='fa fa-eye fa-3x'></i></a></div><?php } ?>
                                                    </td>
                                                </tr><?php
                                                $total_transact = 0;
                                                if ($transaction) { ?>
                                                    <tr class="warning">
                                                    <td><b>Wallet movements</b></td>
                                                    <td><a title='Show Details' style='cursor: pointer;'
                                                           class='txt-color-green' onclick='ver(<?= $id ?>);'><i
                                                                    class='fa fa-eye fa-3x'></i></a></td>
                                                    </tr><?php if ($transaction['add']) {
                                                        $total_transact += $transaction['add'];
                                                        ?>
                                                        <tr class="warning">
                                                        <td><b>Total Added</b></td>
                                                        <td>
                                                            <b style="color: green">$ <?php echo number_format($transaction['add'], 2); ?></b>
                                                        </td>
                                                        </tr><?php }
                                                    if ($transaction['sub']) {
                                                        $total_transact -= $transaction['sub'];
                                                        ?>
                                                        <tr class="warning">
                                                        <td><b>Total removed</b></td>
                                                        <td>
                                                            <b style="color: red">$ <?php echo number_format($transaction['sub'], 2); ?></b>
                                                        </td>
                                                        </tr><?php } ?>
                                                    <tr class="warning">
                                                    <td><b>TOTAL:</b></td>
                                                    <td><h4><b>$ <?php echo number_format($total_transact, 2); ?></b>
                                                        </h4></td>
                                                    </tr><?php } ?></tbody>
                                            </table>

                                        </div>


                                        <table id="dt_basic"
                                               class="table table-striped table-bordered table-hover"><?php
                                            $retenciones_total = 0;
                                            foreach ($retenciones as $retencion) { ?>
                                                <tr class="danger">
                                                <td><b>Hoarding by <?php echo $retencion['descripcion']; ?></b></td>
                                                <td></td>
                                                <td>$ <?php
                                                    $retenciones_total += $retencion['valor'];
                                                    echo number_format($retencion['valor'], 2); ?></td>
                                                </tr><?php $totales;
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
                                            </tr><?php foreach ($cobro as $cobros) {
                                                ?>
                                                <tr class="danger">
                                                <td><b>Succeeded Withdrawal</b></td>
                                                <td></td>
                                                <td>$ <?php
                                                    if ($cobros->monto == null) {
                                                        echo '0';
                                                        $cobro = 0;
                                                    } else {
                                                        echo number_format($cobros->monto, 2);
                                                        $cobro = $cobros->monto;
                                                    }
                                                    ?></td>
                                                </tr><?php
                                            } ?>
                                            <tr class="info">
                                                <td><h4><b>Clear Status</b></h4>
                                                <td></td>
                                                <td><h4>
                                                        <b>$ <?php echo number_format(($totales - ($cobro + $retenciones_total + $cobroPendientes) + ($total_transact)), 2); ?></b>
                                                    </h4></td>
                                            </tr>
                                        </table>

                                    </div>

                                </div><!-- end widget div --></div><!-- end widget --></article>
                    </div>
                </section><!-- end widget grid --></div>
        </div><!-- row --></div>
    <div class="row">
        <div class="col-sm-12"><br/> <br/></div>
    </div><!-- end row --></div><!-- END MAIN CONTENT --> <!-- PAGE RELATED PLUGIN(S)
		<!-- Morris Chart Dependencies -->
<script type="text/javascript">

    function ver(id) {
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

</script>
<script src="/template/js/plugin/morris/raphael.min.js"></script>
<script src="/template/js/plugin/morris/morris.min.js"></script>

<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
	
