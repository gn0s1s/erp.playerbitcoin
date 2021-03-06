<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <h1 class="page-title txt-color-blueDark">
                <a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>
							<a href="/ov/wallet"> > Profit Wallet</a>
							 > Withdrawals</span>

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
            <div class="">

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
                                <div class="widget-body" style="padding : 0;background: transparent !important;border:none">
                                    <div class="col col-md-1 col-lg-1"></div>
                                    <div id="myTabContent1" class="col well col-md-5 tab-content ">

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th><i class="fa fa-sitemap"></i> Network</th>
                                                    <th><i class="fa fa-money"></i> Commission</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php

                                                function setPSRs($psr,$comisiones)
                                                {
                                                    #TODO:  setSumPSRTodo($comisiones);

                                                    $sum_psr = setSumPSR($comisiones);

                                                    $data_psr = array();
                                                    if (isset($psr)) :
                                                        foreach ($psr as $index => $pasive) :
                                                            list($data,$sum_psr) = setPSR($pasive,$sum_psr);
                                                            $data_psr[$index] = $data;
                                                        endforeach;
                                                    endif;
                                                    return $data_psr;
                                                }


                                                function setSumPSR($comisiones)
                                                {
                                                    $sum_psr = 0;
                                                    if (isset($comisiones[1])):
                                                        $comision_vip = $comisiones[1]["comisiones"];
                                                        $PSRcomision = isset($comision_vip["indirectos"]) ? $comision_vip["indirectos"] : 0;
                                                        $sum_psr += $PSRcomision;
                                                    endif;

                                                    if (isset($comisiones[1]["bonos"])):
                                                        $bonos_vip = $comisiones[1]["bonos"];
                                                        $pasiveBonus = isset($bonos_vip['Pasive Bonus']) ? $bonos_vip['Pasive Bonus'] : 0;
                                                        $sum_psr += $pasiveBonus;
                                                    endif;
                                                    return $sum_psr;
                                                }

                                                function setSumPSRTodo($comisiones)
                                                {
                                                    $sum_psr = 0;
                                                    foreach ($comisiones as $comision):
                                                        $comision_vip = isset($comision["comisiones"]) ? $comision["comisiones"] : false;
                                                        if ($comision_vip):
                                                            $PSRcomision = isset($comision_vip["indirectos"]) ? $comision_vip["indirectos"] : 0;
                                                            $sum_psr += $PSRcomision;
                                                        endif;
                                                    endforeach;
                                                    return $sum_psr;
                                                }

                                                function setPSR($pasive,$sum = 0)
                                                {
                                                    #TODO: bono pasivo
                                                    $cent = 100;
                                                    $acumulado = 0;#TODO: isset($pasive->amount) ? $pasive->amount : 0;
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

                                                $data_psr = setPSRs($psr,$data_comisiones);

                                                printPSR($data_psr);

                                                $totales = printTodoComisiones($data_comisiones);

                                                ?>
                                                <tr class="info">
                                                    <td><h4><b>TOTAL</b></h4></td>
                                                    <td>
                                                        <div class="col-md-4">
                                                            <h4><b>$ <?php echo number_format($totales, 2); ?></b></h4>
                                                        </div>
                                                        <?php if ($totales !== 0) { ?>
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

                                                <?php
                                                $total_transact = 0;

                                                if ($transaction) { ?>
                                                    <tr class="default">
                                                        <td><b>Wallet movements</b></td>
                                                        <td><a title='Show Details' style='cursor: pointer;'
                                                               class='txt-color-green' onclick='ver(<?= $id ?>);'><i
                                                                        class='fa fa-eye fa-3x'></i></a></td>
                                                    </tr>
                                                    <?php if ($transaction['add']) {
                                                        $total_transact += $transaction['add'];
                                                        ?>
                                                        <tr class="default">
                                                            <td><b>Total Added</b></td>
                                                            <td>
                                                                <b style="color: green">$ <?php echo number_format($transaction['add'], 2); ?></b>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    if ($transaction['sub']) {
                                                        $total_transact -= $transaction['sub'];
                                                        ?>
                                                        <tr class="default">
                                                            <td><b>Total removed</b></td>
                                                            <td>
                                                                <b style="color: red">$ <?php echo number_format($transaction['sub'], 2); ?></b>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr class="default">
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
                                                <tr class="clear">
                                                    <td><b>Withholding <?php echo $retencion['descripcion']; ?></b></b>
                                                    </td>
                                                    <td></td>
                                                    <td>$ <?php
                                                        $retenciones_total += $retencion['valor'];
                                                        echo number_format($retencion['valor'], 2); ?></td>
                                                </tr>
                                                <?php $totales;
                                            } ?>

                                            <tr class="clear">
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
                                                <tr class="clear">
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
                                                            $saldo_neto = ($totales - ($cobro + $retenciones_total + $cobroPendientes) + ($total_transact));
                                                            if ($saldo_neto < 0)
                                                                echo 0;
                                                            else
                                                                echo number_format($saldo_neto, 2)
                                                            ?></b></h4></td>
                                            </tr>
                                        </table>

                                    </div>
                                    <!-- contenido nuevo Farez Prieto-->
                                    <div class="col col-md-1 col-lg-1"></div>
                                    <div id="myTabContent2" class="col well col-sm-12 col-md-4 tab-content transactions_div">
                                        <!--tabs-->
                                        <div id="exTab1"> 
                                            <ul  class="nav nav-pills">
                                                <li class="active pest">
                                                     <a  href="#withdrawallsPest" data-toggle="tab">Withdrawals</a>
                                                </li>
                                                <li class="pest">
                                                    <a href="#transferPest" data-toggle="tab">Transfer</a>
                                                </li>
                                            </ul>
                                        </div>
                                       <form action="send_mail" method="post" id="paymentform"
                                              class="smart-form ">
                                        <div class="row">
                                            <div class="col col-lg-12">
                                                <fieldset>
                                                    <section class="col col-lg-12 col-md-12">
                                                        <label class="label "><b>Available Balance</b></label>
                                                        <label class="input input state-success">
                                                            <input type="text" name="saldo" class="from-control cajasPago" id="saldo"
                                                                   value="<?php
                                                                   //$saldo_neto=number_format(($total-($cobro+$retenciones_total+$cobroPendientes)),2);
                                                                   if ($saldo_neto < 0)
                                                                       echo 0;
                                                                   else
                                                                       echo number_format($saldo_neto, 2) ?>" readonly/>
                                                        </label>
                                                    </section>
                                                    <section class="col col-lg-12">
                                                        <label class="label"><b>Withdrawals</b></label>
                                                        <label class="input">
                                                            <!-- <i class="icon-prepend fa fa-money"></i> -->
                                                            <input name="cobro" type="number" min="0.01" step="0.01"
                                                                   class="from-control cajasPago" id="cobro"/>
                                                        </label>
                                                    </section>
                                                    <section class="col col-lg-12">
                                                        <label class="label"><b>Final Balance</b></label>
                                                        <label class="input state-disabled state-error">
                                                            <input value="" type="number" disabled="disabled" name="neto"
                                                                   id="neto" class="from-control cajasPago" readonly/>
                                                        </label>
                                                    </section>
                                                </fieldset>
                                            </div>
                                        </div>      
                                        <div class="tab-content clearfix">
                                            <div class="tab-pane active" id="withdrawallsPest">
                                                <!--Contenido pest retiros-->
                                                <fieldset>
                                                    <header>
                                                        <h2><span class="widget-icon">
                                                            <i class="fa fa-btc"></i> </span>
                                                            Payment money : at least 5$
                                                        </h2>
                                                        <div class="col-md-12">
                                                            <hr/>Note: Blockchain Trasnsactions have cost of 2%.</div>
                                                        <br/>
                                                    </header>

                                                    <br>
                                                    <section class="col col-md-12">
                                                        <label class="label"><b>Wallet address</b></label>
                                                        <label class="input">
                                                            <!-- <i class="icon-prepend fa fa-btc"></i> -->
                                                            <input required name="wallet" type="text"
                                                                   value="<?= $cuenta[0]->address ?>" class="from-control cajasPago"
                                                                   id="wallet"/>
                                                        </label>
                                                    </section>
                                                    <section class="hide col col-md-12">
                                                        <label class="label"><b>Final Amount (- <?=$payment_fee?>%)</b></label>
                                                        <label class="input state-disabled state-error">
                                                            <input value="" type="number"
                                                                   name="cobro_amount" id="cobro_amount"
                                                                   class="from-control cajasPago" readonly/>
                                                        </label>
                                                    </section>
                                                    <section class="col col-lg-12">
                                                        <center>
                                                        <a onclick="cobrar()" class="btn btn-process"
                                                                id="enviar" style="width:50% !important;height:auto !important;padding:5% 0% !important;text-align: center !important">Withdraw</a>
                                                            </center>
                                                    </section>
                                                </fieldset>
                                                <!-- Fin contenido pest retiros-->
                                            </div>
                                            <div class="tab-pane" id="transferPest">
                                                <!-- Contenido pest transferencia-->

                                                    <fieldset>
                                                        <header>
                                                            <h2>
                                                            <span class="widget-icon">
                                                                <i class="fa fa-arrows-h"></i>
                                                            </span>
                                                                Transferring money between users
                                                            </h2>
                                                            <div class="col-md-12"><hr/>Note: Transferring money have cost of 2$.</div>
                                                            <br/>
                                                        </header>
                                                        <section class="col col-md-12">
                                                            <label for="" class="label"><b>Enter ID or Username for transfer in:</b></label>
                                                            <label  for="" class="input">
                                                                <input type="text" id="user" class="cajasPago" pattern="[A-z0-9]{1,}" placeholder="ID or USERNAME" name="user">

                                                        </section>
                                                        <section class="col col-md-12">
                                                            <div class="col-md-12"> <label for="" class="label"><b>Secure Token :</b></label>
                                                                <label for="" class="input">
                                                                    <i class="icon-prepend fa fa-key"></i>
                                                                    <h3 id="token_val" class="pull-right"><?=$token?></h3>
                                                                </label>
                                                                <br/>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <hr/>
                                                                <p>Please Copy for secured transferring.</p>
                                                            </div>
                                                            <br/>
                                                            <div class="col-md-12">
                                                                <label class="label"><b>Final Transfer amount (- <?=$transfer_fee?>$)</b></label>
                                                                <label class="input state-disabled state-error">
                                                                    <input value="" type="number" disabled="disabled"
                                                                           name="transfer_amount" id="transfer_amount"
                                                                           class="from-control cajasPago" readonly/>
                                                                </label>
                                                            </div>

                                                            <div class="col-md-12">
                                                            <center><br>
                                                                <a onclick="transferMoney()" class="btn btn-success"
                                                                        id="transfer" class="btn btn-process"
                                                                 style="width:50% !important;height:auto !important;padding:5% 0% !important;text-align: center !important;text-transform: uppercase !important; ">
                                                                        Transfer
                                                                </a>
                                                            </center>

                                                            </div>
                                                        </section>
                                                    </fieldset>

                                                    <fieldset class="hide">
                                                        <header>
                                                            <h2><span class="widget-icon"> <i class="fa fa-bank"></i> </span>
                                                                Bank Account Info</h2>

                                                        </header>
                                                        <br>
                                                        <section class="col col-6">
                                                            <label class="label"><b>Account Titular</b></label>
                                                            <label class="input">
                                                                <i class="icon-prepend fa fa-user"></i>
                                                                <input required name="ctitular" type="text"
                                                                       value="<?= $cuenta[0]->titular ?>" class="from-control"
                                                                       id="ctitular"/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label"><b>Country</b></label>
                                                            <label class="select">
                                                                <select id="cpais" required name="cpais">
                                                                    <?php foreach ($pais as $key) {
                                                                        if ($cuenta[0]->pais == $key->Code) {
                                                                            ?>

                                                                            <option selected value="<?= $key->Code ?>">
                                                                                <?= $key->Name ?>
                                                                            </option>
                                                                        <?php } else {
                                                                            ?>
                                                                            <option value="<?= $key->Code ?>">
                                                                                <?= $key->Name ?>
                                                                            </option>
                                                                        <?php }
                                                                    } ?>
                                                                </select>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label "><b>Account Number</b></label>
                                                            <label class="input input">
                                                                <i class="icon-prepend fa fa-credit-card"></i>
                                                                <input type="number" name="ncuenta"
                                                                       value="<?= $cuenta[0]->cuenta ?>" class="from-control"
                                                                       id="ncuenta" value="" required/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label"><b>bank</b></label>
                                                            <label class="input">
                                                                <i class="icon-prepend fa fa-bank"></i>
                                                                <input name="cbanco" type="text" value="<?= $cuenta[0]->banco ?>"
                                                                       class="from-control" id="cbanco" required/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label"><b>SwiftCode</b></label>
                                                            <label class="input">
                                                                <i class="icon-prepend fa fa-sort-numeric-desc"></i>
                                                                <input name="cswift" type="text" class="from-control"
                                                                       value="<?= $cuenta[0]->swift ?>" id="cswift"/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label "><b>Otro</b></label>
                                                            <label class="input input">
                                                                <i class="icon-prepend fa fa-sort-numeric-desc"></i>
                                                                <input type="number" name="cotro" class="from-control" id="cotro"
                                                                       value="<?= $cuenta[0]->otro ?>"/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label"><b>CLABE (ONLY Mexico)</b></label>
                                                            <label class="input">
                                                                <i class="icon-prepend fa fa-sort-numeric-desc"></i>
                                                                <input name="cclabe" type="text" class="from-control"
                                                                       value="<?= $cuenta[0]->clabe ?>" id="cclabe"/>
                                                            </label>
                                                        </section>
                                                        <section class="col col-6">
                                                            <label class="label"><b>ZIPCODE</b></label>
                                                            <label class="input input">
                                                                <i class="icon-prepend fa fa-sort-numeric-desc"></i>
                                                                <input value="" type="number" name="cpostal"
                                                                       value="<?= $cuenta[0]->dir_postal ?>" id="cpostal"
                                                                       class="from-control"/>
                                                            </label>
                                                        </section>
                                                    </fieldset>

                                                    <footer class="hide">
                                                        <button type="button" onclick="cobrar()" class="btn btn-success"
                                                                id="enviar">
                                                            <i class="glyphicon glyphicon-ok"></i>
                                                            Submit
                                                        </button>
                                                    </footer>
                                                <!-- Fin Contenido pest transferencia-->
                                            </div>

                                        </div>
                                            </form>
                                        <!--fin tabs-->

                                    </div>
                                    <div class="col col-md-1 col-lg-1"></div>


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
<style>
    .msg_user{
        max-height:100px;
        overflow-y: scroll;
    }
    .widget-body .myTabContent1{
        margin-left: 2em;
    }
    .widget-body{
        background: none;
    }
</style>
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

        $("#cobro").keyup(CalcularTransfer);
        $("#user").keyup(CalcularUser);
        CalcularSaldo(1);
        CalcularTransfer(1);
    });

    //setup_flots();
    /* end flot charts */
    function CalcularSaldo(evt) {

        var saldo = <?=$saldo_neto?> /*$("#saldo").val()+ (String.fromCharCode(evt.charCode)*/;
        var pago = $("#cobro").val();
        var neto = saldo - pago;
        neto =neto.toFixed(2);

        var factor = <?=$payment_fee;?>;
        factor /= 100;
        factor = 1 - factor;
        var per = pago*factor;
        per =per.toFixed(2);
        $("#cobro_amount").val(per);

        $("#neto").val(neto);
        var isNeto = neto >= 0;
        var isMinValue = pago >= 5;
        var isValid = isNeto && isMinValue;

        console.log(isNeto+' - '+isMinValue);

        if (isValid) {
            $('#enviar').attr("disabled", false);
        } else {
            $('#enviar').attr("disabled", true);
            $('#transfer').attr("disabled", true);
        }
    }

    function CalcularUser(evt) {

        $('.msg_user').remove();

        var user = $('#user').val();

        $.ajax({
            type: "POST",
            url: "/ov/billetera2/val_user",
            data: {user: user}
        }) .done(function (msg) {
            $('.msg_user').remove();
             $('#user').parent().append(msg);

        })
    }

    function CalcularTransfer(evt) {

        var saldo = <?=$saldo_neto?> /*$("#saldo").val()+ (String.fromCharCode(evt.charCode)*/;
        var pago = $("#cobro").val();
        var factor = <?=$transfer_fee;?>;
        var neto = pago - factor;
        neto =neto.toFixed(2);
        $("#transfer_amount").val(neto);
        if (neto > 0) {
            $('#transfer').attr("disabled", false);
        } else {
            $('#transfer').attr("disabled", true);
        }
        CalcularSaldo(1);
    }

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

    function receiveMoney(id) {

    var message = '<fieldset>' +
                    '<div class="col col-md-6">' +
                    '<label for="" class="label"><b>Secure Token :</b></label>' +
                    '<label for="" class="input">' +
                    '<i class="icon-prepend fa fa-key"></i>' +
                    '<input name="token" type="text" pattern="[A-Z0-9]{6,}" '+
                    'class="from-control" id="token" placeholder="Secure Token"/>' +
                    '</label>' +
                    '<br/>' +
                    '</div>' +
                    '<div class="col col-md-6">' +
                    '<hr/>' +
                    'This token is available only 24 h.' +
                    '</div>\n' +
                    '</fieldset>' ;

        $.ajax({
            type: "POST",
            url: "/auth/show_dialog",
            data: {message: message},
        }).done(function (msg) {

            bootbox.dialog({
                message: msg,
                title: 'Transferring',
                buttons: {
                    success: {
                        label: "Accept",
                        className: "btn-success",
                        callback: function () {
                            var token = $("#token").val();
                            console.log(token);
                            iniciarSpinner();
                            $.ajax({
                                type: "POST",
                                url: "/ov/billetera2/receiveTransfer",
                                data: {
                                    token: token,
                                    id: id
                                }
                            }).done(function (msg) {
                                FinalizarSpinner();
                                bootbox.dialog({
                                    message: msg,
                                    title: '',
                                    buttons: {
                                        success: {
                                            label: "Accept",
                                            className: "btn-success",
                                            callback: function () {
                                                location.href = 'requestPayment';
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


    function transferMoney() {

        var valor = $("#transfer_amount").val();
        var user = $("#user").val();
        var token = '<?=$token?>';
        var message = 'Are you sure you want to request transferring ?';
        $.ajax({
            type: "POST",
            url: "/auth/show_dialog",
            data: {message: message},
        })
            .done(function (msg) {

                bootbox.dialog({
                    message: msg,
                    title: 'Transferring',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {
                                iniciarSpinner();
                                $.ajax({
                                    type: "POST",
                                    url: "/ov/billetera2/transfer",
                                    data: {
                                        valor: valor,
                                        token: token,
                                        user: user
                                    }
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
                                                        location.href = 'requestPayment';
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

    function paymentProcess() {
        iniciarSpinner();
        $.ajax({
            type: "POST",
            url: "/ov/billetera2/cobrar",
            data: $('#paymentform').serialize()
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
                                location.href = 'history';
                            }
                        }
                    }
                })//fin done ajax
            });//Fin callback bootbox
    }

    function cobrar() {

        if (validarCampos()) {
            $.ajax({
                type: "POST",
                url: "/auth/new2FA",
                data: {message: 'Are you sure you want to request payment with the data that was just entered?'},
            })
                .done(function (msg) {

                    bootbox.dialog({
                        message: msg,
                        title: 'Transaction',
                        buttons: {
                            success: {
                                label: "Accept",
                                className: "btn-success",
                                callback: function () {
                                    paymentProcess();

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
        } else {
            alert("The account or collection data is incomplete or erroneous");
        }
    }

    function validarCampos() {

        if ($('#cobro').val() <= 0)
            return false;

        if ($('#wallet').val() == "")
            return false;

        return true; //TODO:

        if ($('#ctitular').val() == "")
            return false;

        if ($('#ncuenta').val() == "")
            return false;

        if ($('#cbanco').val() == "")
            return false;

        return true;
    }
</script>
