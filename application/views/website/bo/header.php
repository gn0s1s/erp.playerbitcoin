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
						<img style="width: 18rem; height: auto; padding: 1rem;" src="/logo.png" alt="Networksoft">
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="active">
								<a href="/"><i style="font-size: 2rem;" class="fa fa-home "></i>Home</a>
							</li>
							<li class="">
								<a href="/bo/configuracion/">
								<i style="font-size: 2rem;" class="fa fa-wrench "></i> Settings </a>
							</li>
							<li class="">
								<a href="/bo/comercial/">
								<i style="font-size: 2rem;" class="fa fa-money "></i>Commercial </a>
							</li>
							<li class="">
								<a href="/bo/logistico/">
								<i style="font-size: 2rem;" class="fa fa-cubes "></i>Logistics </a>
							</li>
							<li class="">
								<a href="/bo/administracion/">
								<i style="font-size: 2rem;" class="fa fa-folder-open "></i> Directives </a>
							</li>
							<li class="">
								<a href="/bo/oficinaVirtual/">
								<i style="font-size: 2rem;" class="fa fa-desktop "></i> BackOffice </a>
							</li>
							<li class="">
								<a href="/bo/reportes">
								<i style="font-size: 2rem;" class="fa fa-book "></i>Reports </a>
							</li>

						</ul>
						<ul class="nav navbar-nav navbar-right">
							<!-- <li style="margin-right: 2rem; margin-top: 0.3rem;" class="">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="/template/img/blank.gif" class="flag flag-es" alt="Spanish"><span> Español </span></a>
							</li> -->
                            <?php $this->load->view('website/traslate'); ?>
							<li class="dropdown">
							<div id="logout" class="btn-header transparent">
								<span> 
									<a style="width: 6rem !important; height: 4rem;color: rgb(255, 255, 255); background: rgb(206, 53, 44) none repeat scroll 0% 0%;" 
									href="/auth/logout" title="sign out" data-action="userLogout"
									data-logout-msg="Do you really want to sign out ?">
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
