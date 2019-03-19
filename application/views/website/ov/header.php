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
								<a href="/"><i style="font-size: 2rem;" class="fa fa-btc "></i>Home</a>
							</li>
                            <li class="">
                                <a href="/ov/dashboard"><i style="font-size: 2rem;" class="fa fa-home "></i>Dashboard</a>
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
										<a href="/ov/networkProfile/photo">Profile Photo</a>
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
										<a href="/shoppingcart">Deposits</a>
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
							<li class="hide dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-th-large"></i> General <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/surveys">Surveys</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/archive">Files</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/board">Template</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/share">SharePoint</a>
									</li>
								</ul>
							</li>
							<li class="hide dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-envelope-square"></i> Comunications <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/personalWeb">Web Personal</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/chat">Chat my Network</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/email">Email</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/cgeneral/soporte_tecnico_ver_redes/">Support</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/email">Suggestions</a>
									</li>
								</ul>
							</li>
							<li class="hide dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i style="font-size: 2rem;" class="fa fa-info-circle"></i> Information & Training <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="/ov/information">Information</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/presentations">Slideshow</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/ebooks">E-books</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/downloads">Downloadable</a>
									</li>
									<li class="divider"></li>
									<li class="hide">
										<a href="/ov/coupons">Calculated Commissions</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/events">Events</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/news">News</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/videos">Videos</a>
									</li>
									<li class="divider"></li>
									<li class="hide">
										<a href="/ov/acknowledgments">Reconocimientos</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/ov/statistics">Statistics</a>
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
									<a class="btnLogout" href="/auth/logout" title="sign out" data-action="userLogout" data-logout-msg="Do you really want to sign out ?">
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
