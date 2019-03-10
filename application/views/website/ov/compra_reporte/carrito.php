<link href="/cart/HTML/assets/css/style.css" rel="stylesheet">
<link href="/cart/HTML/assets/css/skin-3.css" rel="stylesheet">

<!-- css3 animation effect for this template -->
<link href="/cart/HTML/assets/css/animate.min.css" rel="stylesheet">

<!-- styles needed by carousel slider -->
<link href="/cart/HTML/assets/css/owl.carousel.css" rel="stylesheet">
<link href="/cart/HTML/assets/css/owl.theme.css" rel="stylesheet">

<!-- styles needed by checkRadio -->
<link href="/cart/HTML/assets/css/ion.checkRadio.css" rel="stylesheet">
<link href="/cart/HTML/assets/css/ion.checkRadio.cloudy.css" rel="stylesheet">

<!-- styles needed by mCustomScrollbar -->
<link href="/cart/HTML/assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">

<!-- Just for debugging purposes. -->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<!-- include pace script for automatic web page progress bar  -->
<style>
    #google_translate_element {
        position: fixed;
        z-index: 1000;
        right: 0;
    }
    #content{
        height: auto !important;
    }
</style>
<!-- Estilos of the new integración del ERP-->
<link rel="stylesheet" type="text/css" href="/template/css/playerBitcoin.css">

<div id="content" style="margin-top: 4em;">
 <div class="navbar navbar-tshop navbar-fixed-top megamenu" role="navigation" id="cart_cont" style="background: <?= $style[0]->btn_1_color;?> !important;">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-cart"> <i style="color : #fff;" class="fa fa-shopping-cart fa-2x"> </i> <span style="color : #fff;" class="cartRespons"> Cart (<?php echo $this->cart->total_items(); ?> ) </span> </button>
      <a style="color : #fff;margin-left:4rem;" class="navbar-brand titulo_carrito" href="/ov/dashboard" > <i class="fa fa-arrow-circle-left"></i> Back &nbsp;</a>
      
      <!-- this part for mobile -
      <div class="search-box pull-right hidden-lg hidden-md hidden-sm">
        <div class="input-group">
          <button class="btn btn-nobg getFullSearch" type="button"> <i class="fa fa-search"> </i> </button>
        </div>
        <! /input-group >
        
      </div> -->
    </div>
    <!-- this part is duplicate from cartMenu  keep it for mobile -->
    <div class="navbar-cart  collapse">
      <div class="cartMenu  hidden-lg col-xs-12 hidden-md hidden-sm">
        <div class="w100 miniCartTable scroll-pane">
          <table>
            <tbody class="cartButton">
            	  <?php
                  	if($this->cart->contents())
					{
						
						$cantidad=0; 
						foreach ($this->cart->contents() as $items) 
						{
							$total=$items['qty']*$items['price'];	
							echo '<tr class="miniCartProduct"> 
									<td style="width:20%" class="miniCartProductThumb"><div> <a href=""> <img src="'.$compras[$cantidad]['imagen'].'" alt="img"> </a> </div></td>
									<td style="width:40%"><div class="miniCartDescription">
				                        <h4> <a href=""> '.$compras[$cantidad]['nombre'].'</a> </h4>
				                        <span> '.$items['price'].' </span>
				                      </div></td>
				                    <td  style="width:10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
				                    <td  style="width:15%" class="miniCartSubtotal"><div class="price"><span>$ '.$total.'</span></div></td>
				                    <td  style="width:5%" class="delete"><a onclick="quitar_producto(\''.$items['rowid'].'\')"> <i class="txt-color-red fa fa-trash-o fa-2x "></i> </a></td>
								</tr>';
								$cantidad++; 
						} 
					}
				?>
             
            </tbody>
          </table>
        </div>
        <!--/.miniCartTable-->

          <?php $this->load->view('website/traslate'); ?>
        <div class="miniCartFooter  miniCartFooterInMobile text-right">
          <h3 class="text-right subtotal"> Subtotal: $<?php echo $this->cart->total(); ?> </h3>
          <a class="btn btn-sm btn-danger" onclick="ver_cart()">
              <i class="fa fa-shopping-cart"> </i> SHOW CART </a>
            <a class="btn btn-sm btn-primary" onclick="a_comprar()">
                <i class="fa fa-money fa"></i> PAY IT!  </a> </div>
        <!--/.miniCartFooter--> 
        
      </div>
      <!--/.cartMenu--> 
    </div>
    <!--/.navbar-cart-->
    
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      <?php
      									$tiposMercancia = array(
											1 => "Products",
											2 => "Services",
											#3 => "Combined",
											#4 => "Packs",
											5 => "Membership"
										);

								if(isset($mostrarMercancia)){									
			      					if($mostrarMercancia>3){
			      						unset($tiposMercancia[1]);
			      						unset($tiposMercancia[2]);
			      						unset($tiposMercancia[3]);
			      					}

			      					if($mostrarMercancia>2){
			      						unset($tiposMercancia[4]);
			      					}

      							}

      							if(sizeof($tiposMercancia)==5){
      								echo '<li class="active"> <a onclick="show_todos()"> Todos </a> </li>';
      							}

      							foreach ($tiposMercancia as $key => $value) {
      								echo '<li class="dropdown megamenu-fullwidth"> <a data-toggle="dropdown" class="dropdown-toggle" onclick="show_todos_tipo_mercancia('.$key.')"> '.$value.'</a></li>';
      							}	     	

      	?>
      </ul>
      <!--- this part will be hidden for mobile version -->
      <div class="nav navbar-nav navbar-right hidden-xs">
        <div class="dropdown  cartMenu " style="background: <?= $style[0]->btn_2_color;?> none repeat scroll 0% 0%;"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-shopping-cart fa-2x"> </i> <span class="cartRespons"> Cart (<?php echo $this->cart->total_items(); ?> ) </span> <b class="caret"> </b> </a>
          <div class="dropdown-menu col-lg-4 col-xs-12 col-md-4 ">
            <div class="w100 miniCartTable scroll-pane">
              <table> 
                <tbody class="cartButton">
                  <?php
                  	if($this->cart->contents())
					{
						$cantidad=0;
						foreach ($this->cart->contents() as $items) 
						{
							$total=$items['qty']*$items['price'];	
							echo '<tr class="miniCartProduct"> 
									<td style="width:20%" class="miniCartProductThumb"><div> <a href=""> <img src="'.$compras[$cantidad]['imagen'].'" alt="img"> </a> </div></td>
									<td style="width:40%"><div class="miniCartDescription">
				                        <h4> <a href=""> '.$compras[$cantidad]['nombre'].'</a> </h4>
				                        <span>$ '.$items['price'].' </span>
				                      </div></td>
				                    <td  style="width:10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
				                    <td  style="width:15%" class="miniCartSubtotal"><div class="price"><span>$ '.$total.'</span></div></td>
				                    <td  style="width:5%" class="delete"><a onclick="quitar_producto(\''.$items['rowid'].'\')"> <i class="txt-color-red fa fa-trash-o fa-3x "></i> </a></td>
								</tr>'; 
								$cantidad++;
						} 
					}
                  
                   ?>
                </tbody>
              </table>
            </div> 
            <!--/.miniCartTable-->
            
            <div class="miniCartFooter text-right">
              <h3 class="text-right subtotal"> Subtotal: $<?php echo $this->cart->total(); ?> </h3>
              <a class="btn btn-sm btn-danger" onclick="ver_cart()">
                  <i class="fa fa-shopping-cart"> </i> SHOW CART </a>
                <a class="btn btn-sm btn-primary" onclick="a_comprar()">
                    <i class="fa fa-money fa"></i> PAY IT!  </a> </div>
            <!--/.miniCartFooter--> 
            
          </div>
          <!--/.dropdown-menu--> 
        </div>
        <!--/.cartMenu-->
        <!--  
        <div class="search-box">
          <div class="input-group">
            <button class="btn btn-nobg getFullSearch" type="button"> <i class="fa fa-search"> </i> </button>
          </div>
          <!-- /input-group 
          
        </div>
        <!--/.search-box -->
      </div>
      <!--/.navbar-nav hidden-xs--> 
    </div>
    <!--/.nav-collapse --> 
</div>
</div>
<div class="container main-container" style="background-color: #fff;min-height: auto ! important;padding-top: 10rem;padding-bottom: 10rem;"> 
<div class="row">
	<div class="breadcrumbDiv col-lg-12">
      <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a> </li>
        <li class="active"><i class="fa fa-shopping-cart"></i> Shopping cart </li>
      </ul>
    </div>
<article class="col-lg-12 col-sm-4 col-md-3 col-lg-3">
	  			<div class="carousel-inner">
				<!-- Slide 1 -->
				<div class="item active" style="height: 100%; margin-bottom: 2rem;">
					<img src="/media/imagenes/m3.jpg" alt="demo user">
				</div>
			</div>
				<div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
						<h2 class="font-md"><i>Categories</i></h2>
					</header>

					<!-- widget div-->
					<div>
						
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						
						<!-- widget content -->
						<div class="widget-body">
							<? if(isset($redes)){?>
							<? foreach ($redes as $red) {?>
								<h3><?= $red->nombre;?></h3>
								<div class="dropdown">
										<?php foreach ($grupos as $grupo) {
                                            $isCategoria = $red->nombre == $grupo->red;
                                            $isCategoria &= $grupo->id_grupo != 4;
												if ($isCategoria ){
													echo '<a id="dLabel" style="background:'.$style[0]->btn_1_color.' !important" role="button" data-toggle="dropdown" class="btn btn-primary btn-block" data-target="#" 
														onclick="show_todos_categoria('.$grupo->id_grupo.');" class="btn btn-block">'.$grupo->descripcion.'</a>';
												} 
										}?>

								</div>
								<br>
								
							<? } ?>
							<? } ?>

						</div>
						<!-- end widget content -->
						
					</div>
					<!-- end widget div -->
					
				</div>
				<div class="hide jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
						<h2 class="font-md"><i>Tipos of Mercancia</i></h2>
					</header>

					<!-- widget div-->
					<div>
						
						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						
						<!-- widget content -->
						<div class="widget-body">
								<h3>Tipos Mercancia</h3>
								<div class="dropdown">
								<?php 

								foreach ($tiposMercancia as $key => $value) {
      									echo ' <a id="dLabel" style="background:'.$style[0]->btn_1_color.' !important" role="button" data-toggle="dropdown" class="btn btn-primary btn-block" data-toggle="dropdown" class="dropdown-toggle"
      										 onclick="show_todos_tipo_mercancia('.$key.')"> '.$value.' </a>';
      							}	

      							?>
								</div>
								<br>
						</div>
						<!-- end widget content -->
						
					</div>
					<!-- end widget div -->
					
				</div>
				<!-- end widget -->
				<div class="hide paymentMethodImg">
				<h3>Métodos of pago</h3>
					<img src="/template/img/payment/payu.jpg" alt="img" height="50"> 
					<img src="/template/img/payment/blockchain.png" alt="img" height="30">
					<img src="/template/img/payment/paypal.png" alt="img" height="30"> 
					<img class="hide" src="/template/img/payment/american_express_card.png" alt="img" height="30">
					<img class="hide" src="/template/img/payment/discover_network_card.png" alt="img" height="30">
				</div>
</article>
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
	    		<div class="">
	      			<div class="row xsResponse" id="mercancias">
	      									<!-- start row -->
					<div class="row">
						<div class="col-sm-3">
						</div>
						<div class="col-sm-8">
				
							<div class="row">
				
								<div class="col-sm-12 col-md-12 col-lg-12">
				
									<!-- well -->
									<div class="well">
										<div id="myCarousel-2" class="carousel slide">
											<ol class="carousel-indicators">
												<li data-target="#myCarousel-2" data-slide-to="0" class="active"></li>
												<li data-target="#myCarousel-2" data-slide-to="1" class=""></li>
												<li data-target="#myCarousel-2" data-slide-to="2" class=""></li>
											</ol>
											<div class="carousel-inner">
												<!-- Slide 1 -->
												<div class="item active">
													<img src="/logo.png" alt="">
													<div class="carousel-caption caption-right">
												<!--  		<h4>Title 1</h4>
														<p>/media/imagenes/carrito/banner1.png
															Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non my porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
														</p>
														<br>
														<a href="javascript:void(0);" class="btn btn-info btn-sm">Read more</a> -->
													</div>
												</div>
												<!-- Slide 2 -->
												<div class="item">
													<img src="/logo.png" alt="">
													<div class="carousel-caption caption-left">
													<!--  	<h4>Title 2</h4>
														<p>/media/imagenes/carrito/banner2.png
															Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non my porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
														</p>
														<br>
														<a href="javascript:void(0);" class="btn btn-danger btn-sm">Read more</a> -->
													</div>
												</div>
												<!-- Slide 3 -->
												<div class="item">
													<img src="/logo.png" alt="">
													<div class="carousel-caption caption-left">
													<!--  	<h4>Title 2</h4>
														<p>/media/imagenes/carrito/banner3.png
															Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non my porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
														</p>
														<br>
														<a href="javascript:void(0);" class="btn btn-danger btn-sm">Read more</a> -->
													</div>
												</div>
											</div>
											<a class="left carousel-control" href="#myCarousel-2" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
											<a class="right carousel-control" href="#myCarousel-2" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
										</div>
				
									</div>
									<!-- end well-->
				
								</div>
				
							</div>
				
						</div>
				
					</div>
				        
				        <!--/.item--> 
	      			</div>
	      <!-- /.row -->
	      
	      			
	    		</div>
	    	<!--/.container--> 
	  		</div>
	  <!--/.featuredPostContainer-->
	  
	  
	  <!--/.section-block-->
	  
	  
	  <!--/.section-block--> 
	  
	</div>
</div>
</div>
</div>


<script>
    paceOptions = {
      elements: true
    };
</script>
<script src="/cart/HTML/assets/js/pace.min.js"></script>
<!--<script type="text/javascript" src="/cart/HTML/assets/js/smoothproducts.min.js"></script> -->
<!--<script src="/template/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>  -->
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">

    function validar_variable()
    {
        console.log("validar_variable");

        var costo_variable = $("#costo_variable").val();

        var validar = /^[0-9]{1,}$/.test(costo_variable);
        validar &= costo_variable>=5;

        if(!validar)
            $('#agregar_item').attr('disabled','disabled');
        else
            $('#agregar_item').removeAttr('disabled');
    }


			function detalles(id,tipo)
			{
				var datos={'id':id,'tipo':tipo};
				$.ajax({
					data: {info:JSON.stringify(datos)},
					type: "get",
					url: "muestra_mercancia",
					success: function(msg){
				             
				             bootbox.dialog({
								message: msg,
								title: "Descripción",
								className: "div_info_merc",
								buttons: {
									success: {
										label: "Close",
										className: "btn-danger",
										callback: function() {
											}
									}
								}
							})
				    }
				});
			}
			
			function compra_prev(id,tipo,desc)
			{
				var datos={'id':id,'tipo':tipo,'desc':desc};
				$.ajax({
					data: {info:JSON.stringify(datos)},
					type: "get",
					url: "add_carrito",
					success: function(msg){
				            
				             bootbox.dialog({
								message: msg,
								title: "Descripcion",
								className: "div_info_merc",
								buttons: {
									danger: {
										label: "Cancel",
										className: "btn-danger",
										callback: function() {
											}
									}
								}
							})
				    }
				});
			}
		</script>
		<script type="text/javascript">

			function show_todos_categoria(idTipoRed)
			{
				iniciarSpinner();
				$.ajax({
					type: "get",
					url: "show_todos_categoria",
					data: { id: idTipoRed},
					success:function(msg){
						FinalizarSpinner();
						$("#mercancias").html(msg);
				
					}
				});
			}
					
			function show_todos(idTipoRed)
			{
				iniciarSpinner();
				$.ajax({
					type: "get",
					url: "show_todos",
					data: { id: idTipoRed},
					success:function(msg){
						FinalizarSpinner();
						$("#mercancias").html(msg);
				
					}
				});
			}

			function show_todos_tipo_mercancia(tipoMercancia)
			{
				iniciarSpinner();
				$.ajax({
					type: "get",
					url: "show_todos_tipo_mercancia",
					data: { tipoMercancia: tipoMercancia},
					success:function(msg){
						FinalizarSpinner();
						$("#mercancias").html(msg);
				
					}
				});
			}

		</script>
		<script>
			function comprar(id,tipo)
			{
				
				var qty=$("#cantidad").val();

                var costo = ($("#costo_variable")) ? $("#costo_variable").val() : false;

                var datos= {'id':id,'tipo':tipo,'qty':qty,'costo_unidad':costo};
					$.ajax({
						data:{info:JSON.stringify(datos)},
						type: 'get',
						url: 'add_merc',
						success: function(msg){
							if(msg=="Error")
							{
								bootbox.dialog({
									message: "¡Ooops! the producto se ha agotado, intente mas tarde porfavor.",
									title: "Error",
									className: "",
									buttons: {
										danger: {
										label: "Ok!",
										className: "btn-danger",
										callback: function() {
											}
										}
									}
								});
							}
							else
							{
								bootbox.dialog({
									message: "The items has been added to cart",
									title: "success",
									className: "",
									buttons: {
										success: {
										label: "Ok!",
										className: "btn-success",
										callback: function() {
												bootbox.hideAll();
											}
										}
									}
								});
								update_cart_button();
							}			
						}
					});
			}
			function ver_cart()
			{
				$.ajax({
					type: 'get',
					url: 'ver_carrito',
					success: function(msg){
			 			bootbox.dialog({
								message: msg,
								title: "See shopping cart",
								className: "",
								buttons: {
									success: {
									label: "Accept",
									className: "btn-success",
									callback: function() {
										}
									}
								}
							})
					}
				});
			}

			function update_cart_button()
			{
				$.ajax({
					type: "get",
					url: "printContentCartButton",
					success:function(msg){
						$(".cartButton").html(msg);
						
					}
				});
			}
			

			function quitar_producto(id,confirm = false)
			{

                if (!confirm) {
                    $.ajax({
                        type: "POST",
                        url: "/ov/compras/quitar_producto",
                        data: {id: id},
                    })
                        .done(function (msg) {
                            bootbox.dialog({
                                message: msg,
                                title: 'Remove item',
                                buttons: {
                                    success: {
                                        label: "Accept",
                                        className: "btn-success",
                                        callback: function () {
                                            quitar_producto(id, true);
                                        }
                                    },
                                    danger: {
                                        label: "Cancel",
                                        className: "btn-danger",
                                        callback: function () {

                                        }
                                    }
                                }
                            });
                        });
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "/ov/compras/quitar_producto",
                    data: {id:id,confirm:confirm}
                })
                    .done(function( msg )
                    {
                        bootbox.dialog({
                            message: msg,
                            title: 'Attention',
                            buttons: {
                                success: {
                                    label: "Accept",
                                    className: "btn-success",
                                    callback: function() {
                                        window.location.href='/shoppingcart'
                                    }
                                }
                            }
                        });
                    });//Fin callback bootbox
				
			}

		function a_comprar()
			{
				window.location.href="DatosEnvio";						
			}
		</script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
            show_todos_categoria(3);
			/*
			 * Autostart Carousel
			 */
			$('.carousel.slide').carousel({
				interval : 3000,
				cycle : true
			});
			$('.carousel.fade').carousel({
				interval : 3000,
				cycle : true
			});
		
			// Fill all progress bars with animation
			
			$('.progress-bar').progressbar({
				display_text : 'fill'
			});
			
				
		})

		</script>
