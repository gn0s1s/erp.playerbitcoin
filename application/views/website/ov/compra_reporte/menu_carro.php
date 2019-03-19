<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
				<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
				<span>> 
					Shopping cart
				</span>
			</h1>
		</div>
	</div>
		<?php if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Error </strong> '.$this->session->flashdata('error').'
			</div>'; 
	}
	if($this->session->flashdata('exito')) {
		echo '<div class="alert alert-success fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong> </strong> '.$this->session->flashdata('exito').'
			</div>';
	}
?>	
<div class="well">
 <fieldset>
	<legend>Shopping cart</legend>
							<div class="row">
							<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
								<a href="carrito?tipo=1">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
										<i class="fa fa-user fa-3x"></i>
										<h5>Personal purchase</h5>
									</div>
								</a>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
								<a onclick="comprador()" style="cursor: pointer;">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_2_color?>">
										<i class="fa fa-group fa-3x"></i>
										<h5>Buy by another person</h5>
									</div>     
								</a>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
								<a href="carrito?tipo=3">
									<div class="well well-sm txt-color-white text-center link_dashboard" style="background:<?=$style[0]->btn_1_color?>;">
										<i class="fa fa-clock-o fa-3x"></i>
										<h5>Automatic purchase</h5>
									</div>
								</a>
							</div>
						</div>
		</fieldset>
</div>

</div>	
<script>
	function comprador()
	{
		$.ajax({
			type: "get",
			url: "select_af",
			success: function(msg){
				bootbox.dialog({
						message: msg,
						title: "Select an affiliate",
						className: "",
				});
			}
		});
	}
	function enviar_carro()
	{
		if($("#afiliado_id").val()==0)
		{
			alert("Select an affiliate");
		}
		else
		{
			var afiliado=$("#afiliado_id").val();
			if (confirm('Are you sure you want to make a purchase for this affiliate?')) {
			    window.location.assign("carrito?tipo=2&usr="+afiliado)
			} else {
			    // Do nothing!
			}
		}
	}
</script>
