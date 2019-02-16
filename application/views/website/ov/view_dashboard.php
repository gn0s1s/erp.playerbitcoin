<?php $ci = &get_instance();
   	$ci ->load ->model("model_permissions");?>
			<!-- MAIN CONTENT -->
			<div id="content" >

				<!-- row -->
				<div class="row">
				<br /><br /><br />
				</div>
				<!-- end row -->
      <div class="row">
      	<!-- panel lateral info usuario-->
		<div class="col-sm-6 col-lg-6">
							<div class="well well-sm">
								<div class="row">
							      	<div class="col-sm-12 col-md-12 col-lg-12">
												<div class="well well-light well-sm no-margin no-padding">
													<div class="row">
														<div class="col-sm-12">
															<div id="myCarousel" class="carousel fade profile-carousel">
																<div class="air air-top-left padding-10">
																	<h4 class="txt-color-white font-md"></h4>
																</div>
																<div class="carousel-inner">
																	<!-- Slide 1 -->
																	<div class="item active">
																		<!--<img src="/media/imagenes/m3.jpg" alt="demo user">-->
																	</div>
																</div>
															</div>
														</div>

														<!-- info del usuario-->
														<div class="col-sm-12">

															<div class="row">

																<div class="col-sm-3 profile-pic">
																	<img class="<?php if($actividad) echo "online";?>" src="<?=$user?>" alt="demo user">
																	<div class="padding-10">
																	<a style="padding: 6px 2rem 6px 6px;" href="/ov/networkProfile/photo"><i class="fa fa-camera fa-2x fa-fw fa-lg"></i></a>
																		<br><br>
																		<h4 class="font-md"><strong><?=$id?></strong>
																		<br>
																		<small>ID</small></h4>
																	</div>
																</div>
																<div class="col-sm-4">
																	<h1><?=$usuario[0]->nombre?> <span class="semi-bold"><?=$usuario[0]->apellido?></span>
																	<br>
																	<small> <?php //echo $nivel_actual_red?></small></h1>

																	<ul class="list-unstyled ">
		                                <li>
		                                <div class="demo-icon-font">
																				<img class="flag flag-<?=strtolower($pais)?>">
		                                </div>
																		</li>
																		<li>
																			<h5 class="text-muted">
																				<i class="fa fa-globe"></i> Refferal Link:<br/></h5>
																				<a href="<?=$link_personal;?>" target="_blank"><?=$link_personal;?></a>
																			<hr/>
																		</li>
																		<li>
																			<p class="text-muted">
																				<i class="fa fa-envelope"></i>&nbsp;&nbsp;<a ><?=$email?></a>
																			</p>
																		</li>
																		<li>
																			<p class="text-muted">
																				<i class="fa fa-user"></i>&nbsp;&nbsp;<span class="txt-color-darken"><?=$username?></span>
																			</p>
																		</li>
																		<li>
																			<p class="text-muted">
																				<i class="fa fa-calendar"></i>&nbsp;&nbsp;<span class="txt-color-darken">Last session: <a href="javascript:void(0);" rel="tooltip" title="" data-placement="top" data-original-title="Create an Appointment"><?=$ultima?></a></span>
																			</p>
																		</li>
											                                <li>
											                                <?php if($id_sponsor&&$name_sponsor){
											                                if(($id_sponsor[0]->id_usuario!=1)){
											                                ?>
											                               <b>Sponsor:</b>
											                              <?=$name_sponsor[0]->nombre?> <?=$name_sponsor[0]->apellido?> con id <?=$id_sponsor[0]->id_usuario?><br/>
											
											                              <?php }else{?>
											                              You're Network Head, Sponsored by The Business<br />
											                              <?php }}?>
											                                </li>
		                                									<div class="row">
																			<br>
																			<div class="col-xs-2 col-sm-1">
																					<strong class="<?php if($actividad) echo "label label-success";else echo "label label-default";?>" style="font-size: 2rem;"> <?php if($actividad) echo "<i class='fa fa-smile-o'></i> Actived";else echo "<i class='fa fa-frown-o'></i> Not Actived";?></strong>
																			</div>
																			<div class="col-sm-12">
																				<br>
																				<?php if($titulo!=NULL) 
																					echo '<ul id="sparks" class="">
																						<li class="sparks-info">
																						<h5>Ranking<span class="txt-color-yellow"><i class="fa fa-trophy fa-2x"></i>'.$titulo.'</span></h5>
																						<div class="sparkline txt-color-yellow hidden-mobile hidden-md hidden-sm"></div>
																						</li>
																					</ul>'
																					 ?>
																			</div>
																			<div class="col-sm-12">
						
																				<br>
						
																			</div>
						
																		</div>
																	</ul>
																	<br>
																</div>
																<div class="col-sm-4">
																<h1><small>My Points of commissions</small>  <i class='fa fa-user'></i></h1>
																	<ul class="list-inline friends-list">
																		<li><span class="font-md"><i>On Week :</i></span> <?=intval($puntos_semana)?>
																		</li>
																		<li><span class="font-md"><i>On Month :</i></span> <?=intval($puntos_mes)?>
																		</li>
																		<li><span class="font-md"><i>Total :</i></span> <?=intval($puntos_total)?>
																		</li>
																	</ul>
																<h1><small>Points of commissions on the Network </small>  <i class='fa fa-sitemap'></i></h1>
																	<ul class="list-inline friends-list">
																		<li><span class="font-md"><i>On Week :</i></span> <?=$puntos_red_semana?>
																		</li>
																		<li><span class="font-md"><i>On Month :</i></span> <?=$puntos_red_mes?>
																		</li>
																		<li><span class="font-md"><i>Total :</i></span> <?=$puntos_red_total?>
																		</li>
																	</ul>
		                                                            <?php if (sizeof($ultimos_auspiciados)>0) :?>
		                                                                <h1><small>Last Sponsored</small></h1>
		                                                                <ul class="list-inline friends-list">
		                                                                    <?php
		                                                                    foreach ($ultimos_auspiciados as $afiliado) {
		                                                                        echo '<li><a onclick="detalles('.$afiliado["id"].')"><img src="'.$afiliado["foto"].'"></a>
																				  </li>';
		                                                                    }?>
		                                                                </ul>
		                                                            <?php endif;?>
																<hr/>
		                                                            <div class="col-md-12">
		                                                                <a href="/play/dashboard">
		                                                                    <div id="play-boton" class="link_dashboard well well-sm txt-color-white text-center ">
		                                                                        <h3><i class="fa fa-btc "></i> Let's Play</h3>
		                                                                    </div>
		                                                                </a>
		                                                            </div>


																</div>
															</div>
														</div>
														<!-- fin info del usuario-->

													</div>
		  									</div>
									</div>
                				<div class="col-sm-12 col-md-12 col-lg-12">
									<!--Inica the secciion of the Profile & red-->
									<div class="well" style="box-shadow: 0px 0px 0px !important;border-color: transparent;">
										<fieldset>
											<legend><b>Messaging</b></legend>
											<div class="row">
												<div role="widget" class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable" id="wid-id-1" data-widget-editbutton="false" data-widget-fullscreenbutton="false">
												<header role="heading"><div role="menu"><a data-toggle="dropdown" href="javascript:void(0);"></a><ul class="dropdown-menu arrow-box-up-right color-select pull-right"><li><span class="bg-color-green" data-widget-setstyle="jarviswidget-color-green" rel="tooltip" data-placement="left" data-original-title="Green Grass"></span></li><li><span class="bg-color-greenDark" data-widget-setstyle="jarviswidget-color-greenDark" rel="tooltip" data-placement="top" data-original-title="Dark Green"></span></li><li><span class="bg-color-greenLight" data-widget-setstyle="jarviswidget-color-greenLight" rel="tooltip" data-placement="top" data-original-title="Light Green"></span></li><li><span class="bg-color-purple" data-widget-setstyle="jarviswidget-color-purple" rel="tooltip" data-placement="top" data-original-title="Purple"></span></li><li><span class="bg-color-magenta" data-widget-setstyle="jarviswidget-color-magenta" rel="tooltip" data-placement="top" data-original-title="Magenta"></span></li><li><span class="bg-color-pink" data-widget-setstyle="jarviswidget-color-pink" rel="tooltip" data-placement="right" data-original-title="Pink"></span></li><li><span class="bg-color-pinkDark" data-widget-setstyle="jarviswidget-color-pinkDark" rel="tooltip" data-placement="left" data-original-title="Fade Pink"></span></li><li><span class="bg-color-blueLight" data-widget-setstyle="jarviswidget-color-blueLight" rel="tooltip" data-placement="top" data-original-title="Light Blue"></span></li><li><span class="bg-color-teal" data-widget-setstyle="jarviswidget-color-teal" rel="tooltip" data-placement="top" data-original-title="Teal"></span></li><li><span class="bg-color-blue" data-widget-setstyle="jarviswidget-color-blue" rel="tooltip" data-placement="top" data-original-title="Ocean Blue"></span></li><li><span class="bg-color-blueDark" data-widget-setstyle="jarviswidget-color-blueDark" rel="tooltip" data-placement="top" data-original-title="Night Sky"></span></li><li><span class="bg-color-darken" data-widget-setstyle="jarviswidget-color-darken" rel="tooltip" data-placement="right" data-original-title="Night"></span></li><li><span class="bg-color-yellow" data-widget-setstyle="jarviswidget-color-yellow" rel="tooltip" data-placement="left" data-original-title="Day Light"></span></li><li><span class="bg-color-orange" data-widget-setstyle="jarviswidget-color-orange" rel="tooltip" data-placement="bottom" data-original-title="Orange"></span></li><li><span class="bg-color-orangeDark" data-widget-setstyle="jarviswidget-color-orangeDark" rel="tooltip" data-placement="bottom" data-original-title="Dark Orange"></span></li><li><span class="bg-color-red" data-widget-setstyle="jarviswidget-color-red" rel="tooltip" data-placement="bottom" data-original-title="Red Rose"></span></li><li><span class="bg-color-redLight" data-widget-setstyle="jarviswidget-color-redLight" rel="tooltip" data-placement="bottom" data-original-title="Light Red"></span></li><li><span class="bg-color-white" data-widget-setstyle="jarviswidget-color-white" rel="tooltip" data-placement="right" data-original-title="Purity"></span></li><li><a href="javascript:void(0);" class="jarviswidget-remove-colors" data-widget-setstyle="" rel="tooltip" data-placement="bottom" data-original-title="Reset widget color to default">Remove</a></li></ul></div>
													<span class="widget-icon"> <i class="fa fa-comments txt-color-white"></i> </span>
													<h2>Notifications </h2>
													<div role="menu">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
														<!-- add: non-hidden - to disable auto hide -->
														<div>
															
															<ul class="dropdown-menu pull-right js-status-update">
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-circle txt-color-green"></i> Online</a>
																</li>
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-circle txt-color-red"></i> Busy</a>
																</li>
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-circle txt-color-orange"></i> Away</a>
																</li>
																<li class="divider"></li>
																<li>
																	<a href="javascript:void(0);"><i class="fa fa-power-off"></i> Log Off</a>
																</li>
															</ul>
														</div>
													</div>
												<span style="display: none;" class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
												<!-- widget div-->
													<div style="display: block;" role="content">
														<!-- widget edit box -->
														<div class="jarviswidget-editbox">
															<div>
																<label>Title:</label>
																<input type="text">
															</div>
														</div>
														<!-- end widget edit box -->
														<div class="widget-body widget-hide-overflow no-padding">
															<!-- content goes here -->
															<!-- CHAT CONTAINER -->
															<div id="chat-container">
																<span><i class="fa fa-user"></i><b>!</b></span>
																<div class="chat-list-footer">
																	<div class="control-group">
																		<form class="smart-form">
																			<section>
																				<label class="input">
																					<input id="filter-chat-list" placeholder="Filter" type="text">
																				</label>
																			</section>
																		</form>
																	</div>
																</div>
															</div>
															<!-- CHAT BODY -->
															<div id="chat-body" class="chat-body custom-scroll">
																<span id="" class="activity-dropdown"> <i class="fa fa-user"></i> My Network Members
																<b class="badge bg-color-red bounceIn animated"> <?php echo $numeroAfiliadosRed;?> </b> </span>
																<ul>
																    <?php 
																    
																	foreach ($notifies as $notify){
																		$fecha_inicio = substr($notify->fecha_inicio, 0 , 10);
																		$fecha_fin = substr($notify->fecha_fin, 0 , 10);
																		if (date("Y-m-d") >= $fecha_inicio && date("Y-m-d") <= $fecha_fin){
																		echo '<li class="message">
																		<img src="/media/imagenes/notificacion.png" style="width: 5rem;" class="online" alt="">
																		<div class="message-text">
																			<time>
																				'.$fecha_inicio.'
																			</time> 
																				<a href="javascript:void(0);" class="username">'.$notify->nombre.'</a> 
																				'.$notify->descripcion.'
																		</div>
																	</li>';
																		}}
																	?>		
																	

																	<?php 
																	foreach ($cuentasPorPagar as $cuenta){
																		echo '<li class="message">
																		<img src="/template/img/notificaciones/icon-deuda.png" style="width: 5rem;" class="online" alt="">
																		<div class="message-text">
																			<time>
																				'.$cuenta->fecha.'
																			</time> 
																				<a href="/ov/email" class="username">Submit payment voucher</a>
																				<br>
																				<span>Realizar the consignacion bancaria a </span><br>
																				<span>bank : <b>'.$cuenta->nombreBanco.'</b>,</span><br> 
																				<span>Cuenta : <b>'.$cuenta->cuenta.'</b>,</span><br>
																		';
																		if($cuenta->otro)
																			echo'<span>Titular:	<b>'.$cuenta->otro.'</b>,</span><br>';
																		if($cuenta->clabe)
																		   echo'<span>Clabe:	<b>'.$cuenta->clabe.'</b>,</span><br>';
																		if($cuenta->swift)
																			echo'<span>SWIFT:	<b>'.$cuenta->swift.'</b>,</span><br>';																		
																		if($cuenta->dir_postal)
																			echo'<span>Dirección postal  :<b>'.$cuenta->dir_postal.'</b>,</span><br>';
																		   echo'<span>Valor:	<b> $ '.$cuenta->valor.'</b>,</span>
																		</div>
																	</li>';
																		}
																	?>


																	
																</ul>
															</div>

															</div>
														</div>
                                                </div>
												</div>                                                                                      
												</div>
									</fieldset>
									<!--Termina the secciion of Profile & red-->
								</div>
						    </div>
				 </div>
          </div>

		 <div class="col col-lg-6 col-md-6">

		 	<!--Inica the seccion of the Profile & red-->
				<div class="well" style="">
					<fieldset>
						<legend><b>Profile & Network</b></legend>
						<div class="row">
							<?php $permiso=$ci->model_permissions->check($id,'perfil');
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="networkProfile/">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
										<h5><i class="fa fa-user "></i> Profile</h5>
									</div>
								</a>
							</div>

							<?php }$permiso=$ci->model_permissions->check($id,'afiliar'); //foto
							if($permiso){
							?>
						  	<div class="col-sm-6">
								<a href="/ov/perfil_red/afiliar?tipo=1"> <!-- perfil_red/foto -->
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>;">
										 <!-- fa-camera -->
										<h5><i class="fa fa-edit "></i> Affiliate</h5> <!-- Foto -->
									</div>
								</a>
							</div> 
							<?php }?>
						</div>
						<div class="row">
							<?php $permiso= $ci->model_permissions->check($id,'reportes'); //afiliar
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="reports"> <!-- /ov/perfil_red/afiliar?tipo=1 -->
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
										<h5><i class="fa fa-table "></i> Reports</h5> <!-- Afiliar -->
									</div>
								</a>
							</div>
							<?php }$permiso=$ci->model_permissions->check($id,'red');
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="/ov/red/index">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
										<h5><i class="fa fa-sitemap "></i> Network</h5>
									</div>
								</a>
							</div>

							<?php }?>
						</div>
					</div>
				</fieldset>
				<!--Termina the seccion of Profile & red-->

				<!--Inica the seccion of Purchases & reportes-->
				<div class="well">
					<fieldset>
						<legend><b>Purchases & commissions</b></legend>
						<div class="row">
							<?php $permiso=$ci->model_permissions->check($id,'carrito');
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="/shoppingcart">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
										<h5><i class="fa fa-shopping-cart "></i> Deposits</h5>
									</div>
								</a>
							</div>
							<?php }?>
    <?php $permiso=$ci->model_permissions->check($id,'billetera');
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="accountStatus">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
										<h5><i class="fa fa-money "></i> Profit Statement</h5>
									</div>
								</a>
							</div>
							<?php }?>
						</div>
						<div class="row">
    <?php $permiso=$ci->model_permissions->check($id,'afiliar'); //reportes
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="javascript:void(0);"> <!-- compras/reportes -->
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
										<h5><i class="fa fa-ticket "></i> Tickets/Cupons</h5> <!-- Reportes -->
									</div>
								</a>
							</div>
							<?php }?>
  <?php $permiso=$ci->model_permissions->check($id,'tickets');
							if($permiso){
							?>
							<div class="col-sm-6">
								<a href="wallet" >
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>;">
										<h5><i class="fa fa-dollar "></i> Profit Wallet</h5>
									</div>
								</a>
							</div>
							<?php }?>
						</div>
					</fieldset>
				</div>
			  <!--Termina the seccion of Purchases & reportes-->

             <div class="row">
                 <div class="col-sm-12 col-md-12 col-lg-12">
                     <!--Inica the seccion general-->
                    <!--  <div class="well">
                         <fieldset>
                             <legend><b>General</b></legend>
                             <div class="row">
                                 <?php $permiso=$ci->model_permissions->check($id,'encuestas');
                                 if($permiso){
                                     ?>
                                     <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                         <a href="surveys">
                                             <div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
                                                 <h5><i class="fa fa-file-text-o "></i> Surveys</h5>
                                             </div>
                                         </a>
                                     </div>
                                 <?php }$permiso=$ci->model_permissions->check($id,'archivero');
                                 if($permiso){
                                     ?>
                                     <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                         <a href="archive">
                                             <div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
                                                 <h5><i class="fa fa-archive "></i> Files</h5>
                                             </div>
                                         </a>
                                     </div>
                                 <?php }?>
                             </div>
                             <div class="row">
                                 <?php $permiso=$ci->model_permissions->check($id,'tablero');
                                 if($permiso){
                                     ?>
                                     <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                         <a href="board">
                                             <div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>;">
                                                 <h5><i class="fa fa-cogs "></i> Template</h5>
                                             </div>
                                         </a>
                                     </div>
                                 <?php }$permiso=$ci->model_permissions->check($id,'compartir');
                                 if($permiso){
                                     ?>
                                     <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                                         <a href="share">
                                             <div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
                                                 <h5><i class="fa  fa-folder-open-o  "></i> SharePoint</h5>
                                             </div>
                                         </a>
                                     </div>
                                 <?php }?>
                             </div>
                         </fieldset>
                     </div> -->
                     <!--Termina the seccion general-->
                 </div>
             </div>

		 </div>
        </div>
		<!-- end row -->



          <!-- panel lateral info usuario-->

          <!-- Descomentar esto para la segunda fase-->
          <?php $a = 0; ?>
          <?php if($a==1000){ ?>
				<div>
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12">

							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<!--Inicia the seccion of comunicacion-->
									<div class="well">
										<div class="row">
											<fieldset>
												<legend><b>Comunications</b></legend>
												<div class="col-sm-2">
												</div>
												
												<div class="col-sm-2">
													<a href="cgeneral/autoresponder">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-globe "></i> Autoresponder</h5>
														</div>
													</a>
												</div>	
												<div class="col-sm-2">
													<a href="cgeneral/invitacion_afiliar">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
															<h5><i class="fa fa-envelope fa-2x"></i>&nbsp;&nbsp;<i class="fa fa-sitemap "></i> Affiliate Banner</h5>
														</div>
													</a>
												</div>
												
												<?php $permiso=$ci->model_permissions->check($id,'e_mail');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="email">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-envelope "></i> E-mail</h5>
														</div>
													</a>
												</div>
												
												<?php }?>
                                                <div class="col-sm-2">
                                                    <a href="javascript:void(0);"><!-- cgeneral/ganadores -->
                                                        <div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
                                                            <h5><i class="fa fa-trophy "></i> Winners</h5>
                                                        </div>
                                                    </a>
                                                </div>

                                        </div>

											
										</div>
									</div>
									<!--Termina the seccion of comunicacion-->
								</div>
							
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<!--Inicia the seccion of escuela & negocios-->
									<div class="well">
										<div class="row">
											<fieldset>
												<legend><b>Informatión & Training</b></legend>
												<div class="col-sm-2">
												</div>
												<?php $permiso=$ci->model_permissions->check($id,'informacion');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="information">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-info "></i> Informatión</h5>
														</div>
													</a>
												</div>
												<?php }$permiso=$ci->model_permissions->check($id,'presentaciones');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="presentations">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<h5><i class="fa fa-desktop "></i> Slideshow</h5>
														</div>
													</a>
												</div>
												<?php }$permiso=$ci->model_permissions->check($id,'e_books');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="ebooks">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-book "></i> E-books</h5>
														</div>
													</a>
												</div>
												<?php }$permiso=$ci->model_permissions->check($id,'descargas');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="downloads">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
															<h5><i class="fa fa-download "></i> Downloadable</h5>
														</div>
													</a>
												</div>
												
												<?php }?>
											</fieldset>
										</div>
										<div class="row">
											<div class="col-sm-2">
											</div>
											<?php $permiso=$ci->model_permissions->check($id,'eventos');
											if($permiso){
											?>
											<div class="col-sm-2">
												<a href="events">
													<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
														<h5><i class="fa fa-calendar "></i> Events</h5>
													</div>
												</a>
											</div>
											<?php }$permiso=$ci->model_permissions->check($id,'noticias');
											if($permiso){
											?>
											<div class="col-sm-2">
												<a href="news">
													<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
														<h5><i class="fa fa-bullhorn "></i> News</h5>
													</div>
												</a>
											</div>
											<?php }$permiso=$ci->model_permissions->check($id,'videos');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="videos">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<h5><i class="fa fa-file-video-o "></i> Videos</h5>
														</div>
													</a>
												</div>
											
                      <?php } ?>
                    	<?php $permiso=$ci->model_permissions->check($id,'estadisticas');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="statistics">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-bar-chart-o "></i> Statistics</h5>
														</div>
													</a>
												</div>
												<?php } ?>
										</div>
									</div>
									<!--Termina the seccion of escuela & negocios-->
								</div>
							</div>
							
						<?php if($id==2){  ?>
							<div class="row hide">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<!-- FUNCIONALIDADES SIN HACER O POR TERMINAR -->
									<div class="well">
										<div class="row">
											<fieldset>
												<legend><b>Premium</b></legend>
												<div class="col-sm-1">
												</div>
												<?php $permiso=$ci->model_permissions->check($id,'promociones');
												if($permiso){
												?>
											  	<div class="col-sm-2">
													<a href="coupons"><!--   escuela_negocios/promociones -->
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-gift  "></i> Calculated Commissions</h5>
														</div>
													</a>
												</div>  
												<?php }?>
												<?php $permiso=$ci->model_permissions->check($id,'reconocimientos');
											if($permiso){
											?>
										  	<div class="col-sm-2">
												<a href="acknowledgments">
													<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
														<h5><i class="fa fa-star "></i> Reconocimientos</h5>
													</div>
												</a>
											</div> 
                      						<?php } ?>
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>;">
															<div class="row">
																<i class="fa fa-facebook fa-1x"></i>
																<i class="fa fa-twitter fa-1x"></i>
															</div>
															<div class="row">
																<i class="fa fa-skype fa-1x"></i>
																<i class="fa fa-youtube fa-1x"></i>
															</div>
															<h5>Social Networks </h5>
														</div>
													</a>
												</div>
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<h5><i class="fa fa-gift "></i> Promociones</h5>
														</div>
													</a>
												</div>
												<?php $permiso=$ci->model_permissions->check($id,'mensajes');
												if($permiso){
												?>
											  	<div class="col-sm-2">
													<a href="personalWeb">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-globe "></i> Web Personal</h5>
														</div>
													</a>
												</div>	
												<?php }?>
												<?php $permiso=$ci->model_permissions->check($id,'chat');
												if($permiso){
												?>
											  	<div class="col-sm-2">
													<a href="chat">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<h5><i class="fa fa-weixin "></i> Chat my Network</h5>
														</div>
													</a>
												</div> 
												<?php }?>
												<?php $permiso=$ci->model_permissions->check($id,'soporte_tecnico');
												if($permiso){
												?>
												<div class="col-sm-2">
													<a href="cgeneral/soporte_tecnico_ver_redes">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
															<h5><i class="fa fa-support "></i> Support</h5>
														</div>
													</a>
												</div>
												<?php }?>
												
												<?php $permiso=$ci->model_permissions->check($id,'soporte_tecnico');
												if($permiso){
												?>
								
											<div class="col-sm-1">
												</div>
												<div class="col-sm-2">
													<a href="suggestion">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-send "></i> Suggestions</h5>
														</div>
													</a>
												</div>
								</div>
												<?php }?>
												<?php $permiso=$ci->model_permissions->check($id,'videollamadas'); // [comunicacion]
											if($permiso){
											?>
											<!-- cgeneral/videollamada -->
											 <div class="col-sm-12">
												<a href="#">
													<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
														<h5><i class="fa fa-video-camera "></i> Vídeollamadas</h5>
													</div>
												</a>
											</div> 
											
											<?php }?>
												<?php $permiso=$ci->model_permissions->check($id,'social_network'); // [comunicacion] 
												if($permiso){
												?>
													<div class="col-sm-12">
													<a href="cgeneral/social_network">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<div class="row">
																<i class="fa fa-facebook fa-1x"></i>
																<i class="fa fa-twitter fa-1x"></i>
															</div>
															<div class="row">
																<i class="fa fa-skype fa-1x"></i>
																<i class="fa fa-youtube fa-1x"></i>
															</div>
															<h5>Social network</h5>
														</div>
													</a>
												</div>
												<?php }?>
												
												<?php $permiso=$ci->model_permissions->check($id,'tickets'); //reportes [compras]
												if($permiso){
												?>
												<div class="col-sm-6">
													<a href="javascript:void(0);"> <!-- compras/reportes -->
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:#c0c0c0;<?//=$style[0]->btn_1_color?>;">
															<!-- fa-table -->
															<h5><i class="fa fa-ticket "></i> Tickets</h5> <!-- Reportes -->
														</div>
													</a>
												</div>
												<?php }?>
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
															<h5><i class="fa fa-globe "></i> Web Personal</h5>
														</div>
													</a>
												</div>	
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>;">
															<h5><i class="fa fa-stack-overflow  "></i> Revista Digital</h5>
														</div>
													</a>
												</div>
												<div class="col-sm-2">
													<a href="cgeneral/redes_afiliado_chat?id=red_personal">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
															<h5><i class="fa fa-weixin "></i> Chat</h5>
														</div>
													</a>
												</div> 
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
															<h5><i class="fa fa-mortar-board "></i> Aula Virtual</h5>
														</div>
													</a>
												</div>	
												<div class="col-sm-2">
													<a href="javascript:void(0);">
														<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>">
															<h5><i class="fa fa-support "></i> Support</h5>
														</div>
													</a>
												</div>
											
											</div>
										</div>
									<!--ESTE LIMITE NO ES FIJO-->
								</div>
							</div>
							
							<?php }?>
						</div>

					</div>

					<?}?><!-- Descomentar esto para la segunda fase-->
				</div>
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

    #play-boton{
        background:#C00;
        border-radius:100%;
        padding: 2em 0;
        width: 12.5em;
    }
    @media (min-width: 1170px){
         /* #play-boton{
            position: fixed;
            z-index:10000;
            top:5em;
            right:0;
        } */
    }
    .link_dashboard{
        box-shadow: none;
    }
</style>
<script>

    resizelinks();

    function resizelinks(){
        var wdth= $("#play-boton").parent().parent().width();
        console.log("screen "+wdth);
        // if(screen.width < 1170){
            var link = $("#play-boton").width();
            link/=2;wdth/=2;
            var margin = wdth-link;
            console.log("left "+margin+" -> "+wdth+" - "+link);
            $(".link_dashboard").css("left",margin+"px");
        // }
    }

function detalles(id)
{
	$.ajax({
		type: "POST",
		url: "/ov/perfil_red/detalle_usuario",
		data: {id: id},
	})
	.done(function( msg )
	{
		bootbox.dialog({
			message: msg,
			title: "Información Personal",
			buttons: {
				success: {
					label: "Cerrar!",
					className: "btn-success",
					callback: function() {
					//location.href="";
				}
			}
		}
	});
	});
}
</script>
