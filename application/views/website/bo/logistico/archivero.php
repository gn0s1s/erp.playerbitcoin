<!-- MAIN CONTENT -->
			<div id="content" >
				<div class="row">
					<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
						<h1 class="page-title txt-color-blueDark">
							
							<?php  if($type=='5'){?>
						<a class="backHome" href="/bol"><i class="fa fa-home"></i> Menu</a>
						<span>
							> Archivero	
						</span>
							 <?php }else if($type=='8'||$type=='9'){
						 	$index= ($type=='8') ? '/CEDI' : '/Almacen';?>
						<a class="backHome" href="<?=$index?>"><i class="fa fa-home"></i> Menu</a>
						<span>
							> Archivero			
						</span>
							 <?php }else{?>
						
						<a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
						<span>
							> <a href="/bol/dashboard">Logistics</a>
							> Archivero	
						</span>
							
							<?php }?>
						</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="well">

							<section id="widget-grid" class="">
							
								<!-- row -->
								<div class="row">
							
									<!-- new  WIDGET START -->
									<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

										<!-- Widget ID (each widget will need unique ID)-->
										<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
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
											<header>
												<span class="widget-icon"> <i class="fa fa-table"></i> </span>
												<h2>Archivos</h2>
							
											</header>
							
											<!-- widget div-->
											<div>
							
												<!-- widget edit box -->
												<div class="jarviswidget-editbox">
													<!-- This area used as dropdown edit box -->
												</div>
												<!-- end widget edit box -->
							
											
												<!-- widget content -->
												<div class="widget-body no-padding">
													<form action="upload.php" class="dropzone dz-clickable" id="personal">
									                    <div class="dz-message">
										                    <br /><br /><br /><br /><br /><br />
										                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										                    </div>
										                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
										                        <h1>Arrastra tus archivos o da clic para buscarlos on tu computadora</h1>
										                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
										                        </div><i class="fa fa-file fa-5x"></i>
										                    </div>
									                    </div>
								                	</form>
												</div>
												<!-- end widget content -->
											</div>
											<!-- end widget div -->
										</div>
										<!-- end widget -->
							
											
											
											
							</div>
											</div>
											<!-- end widget div -->
										</div>
										<!-- end widget -->
							
									</article>
								</div>
							</section>
						<!-- end widget grid -->

						 <!-- widget grid -->
							<section id="widget-grid" class="">
							
								<!-- row -->
								<div class="row">
							
									<!-- new  WIDGET START -->
									<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

										<!-- Widget ID (each widget will need unique ID)-->
										<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
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
											<header>
												<span class="widget-icon"> <i class="fa fa-table"></i> </span>
												<h2>Files</h2>
											</header>
							
											<!-- widget div-->
											<div>
												<!-- widget edit box -->
												<div class="jarviswidget-editbox">
													<!-- This area used as dropdown edit box -->
												</div>
												<!-- end widget edit box -->
							
							
							
							
								<!-- widget content -->
												<div class="widget-body no-padding">
													<?php $i=1;
													if($archivos){?>
													<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
														<thead>
															<tr>
																<th class="text-center">
																	<input onclick="checado()" id="todos" type="checkbox">
																</th>
																<th class="text-center hidden-xs hidden-sm"><b>Number</th>
																<th class="text-center"><b>Name</b></th>
																<th class="text-center hidden-xs hidden-sm"><b>Date</b></th>
																<th class="text-center hidden-xs hidden-sm"><b>Size</b></th>
																<th class="text-center hidden-xs hidden-sm"><b>Action</b></th>
															</tr>
														</thead>
														<tbody>
															<form id="archivos">
																<?php foreach ($archivos as $archivo){?>
																<!-- TR -->
																<tr>
																	<td class="text-center"><input name="archivo[]" value="<?=$archivo->id_archivero?>" class="chek" type="checkbox"></td>
																	<td class="text-center"><?=$i?></td>
																	<td>
																			<?=$archivo->nombre_completo?>
																	</td>
																	<td class="text-center hidden-xs hidden-sm">
																		<?=$archivo->fecha?>
																	</td>
																	<td class="hidden-xs hidden-sm"><?=$archivo->tamano?> Kb
																	</td>
																	<td class="hidden-xs hidden-sm">
																		<a onclick="borrar('<?=$archivo->id_archivero?>','<?=$archivo->url?>','<?=$archivo->nombre_completo?>')" class="btn btn-labeled btn-danger" href="#">
																			<span class="btn-label">
																			<i class="glyphicon glyphicon-trash"></i>
																			</span>
																			Borrar
																		</a>&nbsp;
																		<a target="_blank" class="btn btn-labeled btn-info" href="<?=$archivo->url?>">
																			<span class="btn-label">
																			<i class="glyphicon glyphicon-download"></i>
																			</span>
																			Descargar
																		</a>&nbsp;
																		<!--<a target="_blank" onclick="compartir('<?=$archivo->id_archivero_cedi?>')" class="btn btn-labeled btn-warning" href="#">
																			<span class="btn-label">
																			<i class="glyphicon glyphicon-share"></i>
																			</span>
																			Compartir
																		</a>-->
																	</td>
																</tr>
																<!-- end TR -->
																<?php $i++;}?>
															</form>
														</tbody>
													</table>
													<?php }else{echo "<h1>No tienes archivos aún</h1>";}?>
												</div>
												<!-- end widget content -->
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
			            <br />
			            <br />
			        </div>
		        </div>
			</div>
			<!-- PAGE RELATED PLUGIN(S) 
		<script src="..."></script>-->
		<script src="/template/js/plugin/dropzone/dropzone.min.js"></script>
		<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
			 	
				/* DO NOT REMOVE : GLOBAL FUNCTIONS!
				 *
				 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
				 *
				 * // activate tooltips
				 * $("[rel=tooltip]").tooltip();
				 *
				 * // activate popovers
				 * $("[rel=popover]").popover();
				 *
				 * // activate popovers with hover states
				 * $("[rel=popover-hover]").popover({ trigger: "hover" });
				 *
				 * // activate inline charts
				 * runAllCharts();
				 *
				 * // setup widgets
				 * setup_widgets_desktop();
				 *
				 * // run form elements
				 * runAllForms();
				 *
				 ********************************
				 *
				 * pageSetUp() is needed whenever you load a page.
				 * It initializes and checks for all basic elements of the page
				 * and makes rendering easier.
				 *
				 */
				
				 pageSetUp();

				  Dropzone.autoDiscover = false;
			    $("#personal").dropzone({
			        paramName: "foto",
			        url: "/bo/logistico/sube_archivo",
			        addRemoveLinks : true,
			        maxFilesize: 150,
			        dictResponseError: 'Error no se logro subir the archivo!',
			    });
				 
				/*
				 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
				 * eg alert("my home function");
				 * 
				 * var pagefunction = function() {
				 *   ...
				 * }
				 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
				 * 
				 * TO LOAD A SCRIPT:
				 * var pagefunction = function (){ 
				 *  loadScript(".../plugin.js", run_after_loaded);	
				 * }
				 * 
				 * OR
				 * 
				 * loadScript(".../plugin.js", run_after_loaded);
				 */

				 /* COLUMN FILTER  */
		    	var otable = $('#datatable_fixed_column').DataTable({
		    			"columnDefs": [ {
			            "searchable": false,
			            "orderable": false,
			            "sortable": false,
			            "targets": 0
			        	} ],
			        	"order": [[ 1, 'asc' ]],
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,	
			
		    });
		    /* END COLUMN FILTER */   
				
			})
		
		</script>
<script>
function checado()
{
	if ($("#todos").is(":checked"))
	{
		$(".chek").attr("checked","checked")
	}
	else
	{
		$(".chek").removeAttr("checked")
	}
}
function borrar(id,link,name)
{

	$.ajax({
		type: "POST",
		url: "/auth/show_dialog",
		data: {message: '¿ Esta seguro que desea eliminar the archivo '+name+'?'},
	})
	.done(function( msg )
	{
		bootbox.dialog({
		message: msg,
		title: 'Eliminar Archivo',
		buttons: {
			success: {
			label: "Accept",
			className: "btn-success",
			callback: function() {
				
					$.ajax({
						type: "POST",
						url: "/bo/logistico/del_file",
						data: {id_archivero_cedi: id, url: link}
					}).done(function( msg )
					{
						bootbox.dialog({
						message: msg,
						title: 'Archivo eliminado con exito',
						buttons: {
							success: {
							label: "Accept",
							className: "btn-success",
							callback: function() {
								location.href="/bo/logistico/archivero";
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
				callback: function() {

					}
			}
		}
	})
	});
}
function borrar_multiple()
{
	$.ajax({
		type: "POST",
		url: "/bo/logistico/del_file_multiple",
		data: $("#archivos").serialize()
		})
		.done(function( msg ) {
		alert( "Data Saved: " + msg );
	});
}

</script>

