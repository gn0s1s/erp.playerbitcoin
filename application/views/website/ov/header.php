<header class="mainHeaderPlayer">
<article class="col-sm-12" style="z-index: 1000;">

			<div class="navbar navbar-default" style="border-color: rgb(255, 255, 255);">
				
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<img style="height:4em; width: auto; padding: 1rem;" src="/logo.png" alt="Networksoft">
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="/"><i style="font-size: 2rem;" class="fa fa-home "></i>Home</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-group "></i> Profile & Network<span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/networkProfile">Profile</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/networkProfile/photo">Foto</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/perfil_red/afiliar?tipo=1">Affiliate</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/red/index">Networks</a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-credit-card"></i> Purchases & commissions <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/shoppingcart">Deposito</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/accountStatus">Profit Statement</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/wallet">Profit Wallet</a>
									</li>
									<li class="divider"></li>
									 <li>
										<a href="/ov/reports">Reports</a>
									</li>
										
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-th-large"></i> General <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/surveys">Encuestas</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/archive">Archivero</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/board">Tablero</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/share">Compartir</a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-envelope-square"></i> Comunicación <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/personalWeb">Web Personal</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/chat">Chat mi Network</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/email">Email</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/cgeneral/soporte_tecnico_ver_redes/">Soporte Técnico</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/email">Sugerencias</a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-info-circle"></i> Información & Capacitación <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/information">Información</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/presentations">Presentaciones</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/ebooks">E-books</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/downloads">Descargas</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/coupons">Bonos</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/events">Eventos</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/news">Noticias</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/videos">Vídeos</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/acknowledgments">Reconocimientos</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/statistics">Estadisticas</a>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<!--<li style="margin-right: 2rem; margin-top: 0.3rem;" class="">-->
							<?php $this->load->view('website/traslate'); ?>
							<!-- </li>-->
							<li class="dropdown">
							<div id="logout" class="btn-header transparent">
								<span> 
									<a class="btnLogout" href="/auth/logout" title="Salir" data-action="userLogout" data-logout-msg="¿ Realmente desea salir ?">
									<i class="fa fa-sign-out fa-2x"></i>
									</a> 
									</span>
							</div>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				
			</div>
		</article>
</header>
