<?php 
include_once("application/models/ov/model_web_personal_reporte.php");
?>
<!-- MAIN CONTENT -->
<div id="content">
	<div class="row">
		<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
			<h1 class="page-title txt-color-blueDark">
					<a class="backHome" href="/ov"><i class="fa fa-home"></i> Menu</a> 
				<span>
					<a > </a> >	Personal website
				</span>
			</h1>
		</div>
	</div>
	
	<?php if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Exito! </strong> '.$this->session->flashdata('success').'
			</div>'; 
	}
?>	 

<?php if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Error! </strong> '.$this->session->flashdata('error').'
			</div>'; 
	}
?>	
	
	<section id="widget-grid" class="" >
		<!-- START ROW -->
		<div class="row col-xs-12 col-md-12 col-sm-8 col-lg-6" style="padding-left: 0%;">
			<!-- new  COL START -->
			
			
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-2"
					data-widget-editbutton="false" data-widget-custombutton="false"
					data-widget-colorbutton="false">


					<!-- widget div-->


					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body">
						<div class="tab-pane">
							<form class="smart-form" action="/ov/cgeneral/actualizar_clave_web_personal" method="POST" role="form">
							<div class="row col-xs-12 col-md-12 col-sm-8 col-lg-12 smart-form">
								<br>
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4" style="margin-top: 2rem;">
									
										<label > Key to my Personal website
										</label>
									</section>
									
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4" style="margin-top: 0.5rem;">
									
										<label class="input"> <i class="icon-append fa fa-briefcase"></i>
											<input name="clave" placeholder="Enter your password" type="text" value='<?php $vacio=0; if(!isset($datos_web_personal[0]->clave)){echo "";$vacio=$vacio+1;}else{echo $datos_web_personal[0]->clave;}?>'>
										</label>
										
										<label class="input">
											<input name="username" class="hide" type="text" value='<?php echo $username;?>'>
										</label>
										<label class="input">
											<input name="vacio" class="hide" type="text" value='<?php echo $vacio;?>'>
										</label>
									</section>
									
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4">
										<button type="submit" class="btn btn-success col col-xs-12 col-md-12 col-sm-8 col-lg-12">
											Update
										</button>
									</section>
									
							</div>
							<br>
							</form>
						</div>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->
			</article>
		</div>
		
		<div class="row col-xs-12 col-md-12 col-sm-8 col-lg-6" style="padding-left: 0%;">
			<!-- new  COL START -->
			
			
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-3"
					data-widget-editbutton="false" data-widget-custombutton="false"
					data-widget-colorbutton="false">


					<!-- widget div-->


					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body">
						<div class="tab-pane">
							<form class="smart-form" action="/ov/cgeneral/envia_mail_invitacion_web_personal" method="POST" role="form">
							<div class="row col-xs-12 col-md-12 col-sm-8 col-lg-12 smart-form">
								<br>
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4" style="margin-top: 2rem;">
									
										<label > Invite a customer 
										</label>
									</section>
									
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4" style="margin-top: 0.5rem;">
									
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input name="email_receptor" placeholder="Your customer's email" type="email">
										</label>
									</section>
									
									<section class="col col-xs-12 col-md-12 col-sm-8 col-lg-4">
										<button type="submit" class="btn btn-success col col-xs-12 col-md-12 col-sm-8 col-lg-12">
											Invite
										</button>
									</section>
									
							</div>
							<br>
							</form>
						</div>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->
			</article>
		</div>
		
	</section>
	
	
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">
			<!-- new  COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id"
					data-widget-editbutton="false" data-widget-custombutton="false"
					data-widget-colorbutton="false">


					<!-- widget div-->


					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<!-- widget content -->
					<div class="widget-body">
						<div class="tab-pane">

							<div class="row col-xs-12 col-md-12 col-sm-8 col-lg-5 pull-right">
								<div class="col-xs-6 col-md-8 col-sm-8 col-lg-8">		
								</div>
								<div class="col-xs-3 col-md-2 col-sm-2 col-lg-2">
									<center>
										<a title="Descargar" href="#" class="txt-color-blue">
										<i class='fa fa-truck  fa-3x'></i></a> <br>Paid out	
									</center>		
								</div>
								<div class="col-xs-3 col-md-2 col-sm-2 col-lg-2">
									<center>
										<a title="Descargar" href="#" class="txt-color-blue">
										<i class='fa fa-check-circle  fa-3x'></i></a> <br>Embarked
									</center>
								</div>
							</div>
							<br>
							<table  id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th data-hide="phone">ID_sale</th>
										<th data-hide="phone">Date</th>	
										<th data-class="expand">Name</th>
										<th data-hide="phone">Surname</th>	
										<th data-hide="phone">Email</th>
										<th data-hide="phone,tablet">Telephone</th>
										<th data-hide="phone,tablet">Type</th>
										<th data-hide="phone,tablet">Merchandise name</th>
										<th data-hide="phone,tablet">Quantity</th>
										<th data-hide="phone,tablet">Unit value</th>
										<th data-hide="phone,tablet">Cost</th>
									    <th data-hide="phone,tablet">Status</th>
									    <th data-hide="phone,tablet">Sent</th>
									</tr>
								</thead>
								<tbody>
									
										<?php 
										$averiguar_producto=0;
										$tipo_mercancia=0;
										$wp=new model_web_personal_reporte();
											for($i=0;$i<sizeof($datos_compra);$i++)
		                                                     {
		                                                     
		    if(($datos_compra[$i]->id_tipo_mercancia)=="1"){
	         $averiguar_producto=$wp->tipo_de_producto("producto",$datos_compra[$i]->sku);
		     $tipo_mercancia="producto";
		                                                   }
		                                                     		
		     else if(($datos_compra[$i]->id_tipo_mercancia)=="2"){
		     $averiguar_producto=$wp->tipo_de_producto("servicio",$datos_compra[$i]->sku);
		     $tipo_mercancia="servicio";
		                                             }
		                   else if(($datos_compra[$i]->id_tipo_mercancia)=="3"){
		    $averiguar_producto=$wp->tipo_de_producto("combinado",$datos_compra[$i]->sku);
		     $tipo_mercancia="combinado";
		                                                     	}               	
		                                                     	?>
		                                                     <tr>
										<td><?php echo $datos_compra[$i]->id_venta ?></td>
										<td><?php echo $datos_compra[$i]->fecha ?></td>
										<td><?php echo $datos_compra[$i]->nombre ?></td>
										
										<td><?php echo $datos_compra[$i]->apellido ?></td>
										<td><?php echo $datos_compra[$i]->email ?></td>
										<td><?php echo $datos_compra[$i]->telefono ?></td>
										<td><?php echo $tipo_mercancia; ?></td>
										<td><?php echo  $averiguar_producto[0]->nombre; ?></td>
										<td><?php echo $datos_compra[$i]->cantidad ?></td>
										<td><?php echo ($datos_compra[$i]->costo/$datos_compra[$i]->cantidad) ?></td>
										<td><?php echo $datos_compra[$i]->costo ?></td>
										<td><?php echo $datos_compra[$i]->estado ?></td>
										
										<td class='text-center'>
	<?php if($datos_compra[$i]->estado=="Pago"){ ?>
 <a class='txt-color-blue' style='cursor: pointer;' onclick='Enviar(<?php echo $datos_compra[$i]->id_venta ?>)' 
title='Shipping merchandise'><i class='fa fa-truck fa-3x'></i></a>
				   </td>
		        </tr>
<?php } else if($datos_compra[$i]->estado=="Embarcado"){?>
<a class='txt-color-blue' style='cursor: pointer;'  title='Merchandise sent'><i class='fa fa-check-circle fa-3x'></i></a>
				  </td>
		        </tr>
				<?php }?>					
					<?php }?>			
										
									
								</tbody>
							</table>
						</div>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->
			</article>
		</div>
		<!-- end widget -->

		<!-- END COL -->

		<div class="row">
			<!-- a blank row to get started -->
			<div class="col-sm-12">
				<br /> <br />
			</div>
		</div>
		<!-- END ROW -->
	</section>
	<!-- end widget grid -->
</div>
<!-- END MAIN CONTENT -->
<script src="/template/js/plugin/dropzone/dropzone.min.js"></script>
<script src="/template/js/plugin/markdown/markdown.min.js"></script>
<script src="/template/js/plugin/markdown/to-markdown.min.js"></script>
<script src="/template/js/plugin/markdown/bootstrap-markdown.min.js"></script>
<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script
	src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script
	src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new  ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */

	/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic_paquete').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new  ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */
	
		
	pageSetUp();

})
	
</script>

<script type="text/javascript">
		function Enviar(id)
		{
			bootbox.dialog({
				message: "Do you want to send this record now?",
				title: "Send sale ".concat(id),
				className: "",
				buttons: {
					success: {
					label: "Si",
					className: "btn-success",
					callback: function() {
						$.ajax({
							type: "post",
							data: {id:id},
							url: "/ov/compras/Cambiar_estado_enviar"
						})
						.done(function(msg){
							bootbox.dialog({
								message: "These products have been sent successfully.",
								title: "Success",
								className: "",
								buttons: {
									success: {
										label: "Accept",
										className: "btn-success",
										callback: function(){
											window.location.href="/ov/personalWeb";
										}
									}
								}
							})
						});
					}
				},
				danger: {
					label: "No",
					className: "btn-danger",
					callback: function(){
					
					}
				}
			}
		});
					
		}

		</script>
