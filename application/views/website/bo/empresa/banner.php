<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>> <a href="/bo/configuracion">Settings</a></span> <span>> <a href="/bo/configuracion/empresa">My Business</a></span>
                <span>> Update Banner</span></h1>
        </div>
    </div><?php if ($this->session->flashdata('error')) {
        if ($this->session->flashdata('error') != "Se ha modificado the banner.") {
            echo '<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Error </strong> ' . $this->session->flashdata('error') . '
			</div>';
        } else {
            echo '<div class="alert alert-success fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>completado </strong> ' . $this->session->flashdata('error') . '
			</div>';
        }
    }
    ?>
    <section id="widget-grid" class=""><!-- START ROW -->
        <div class="row"><!-- new  COL START -->
            <article class="col-sm-12 col-md-12 col-lg-12"><!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-1"
                     data-widget-editbutton="false" data-widget-custombutton="false"
                     data-widget-colorbutton="false"><!-- widget options:
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
                    <header><span class="widget-icon"><i class="fa fa-bookmark"></i></span>
                        <h2>Banner Data</h2>
                    </header><!-- widget div-->
                    <div>
                        <div class="widget-body">
                            <form class="smart-form" name="form_banner" method="post"
                                  action="/bo/configuracion/crear_banner" enctype="multipart/form-data">
                                <fieldset>
                                    <legend>Banner Title</legend>
                                    <div class="row">
                                        <section class="col col-3">
                                            <label class="input">Title<input required type="text" id="titulo"
                                                                             name="titulo"
                                                                             value="<?= $img[0]->titulo ?>" required>
                                            </label>
                                        </section>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Banner Description</legend>
                                    <div class="row"><!--<section style="padding-left: 0px;" class="col col-6">Descripcion
														<textarea name="descripcion" style="max-width: 96%" id="mymarkdown" value="" required><? //echo $img[0]->descripcion?></textarea>
													</section>-->
                                        <section class="col col-3">
                                            <label class="textarea">Description<textarea id="descripcion"
                                                                                         name="descripcion" rows="3"
                                                                                         class="custom-scroll"
                                                                                         required><?= $img[0]->descripcion ?></textarea>
                                            </label>
                                        </section>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Banner Image</legend>
                                    <div id="dir" class="row">
                                        <section id="imagenes2" class="col col-12">
                                            <label class="label">Recent
                                                Banner</label><?php if ($img[0]->nombre_banner != "") {
                                                echo '<div class="no-padding col-xs-12 col-sm-12 col-md-6 col-lg-6"><img style="max-height: 150px;" src="/media/Empresa/' . $img[0]->nombre_banner . '" width="390px" height="150px"></div>';
                                            } else {
                                                echo "Image not found";
                                            }
                                            ?></section>

                                        <section id="imagenes" class="col col-12">
                                            <label class="label">Image Attachment</label>
                                            <div class="input input-file"><span class="button"><input id="img"
                                                                                                      name="img"
                                                                                                      onchange="this.parentNode.nextSibling.value = this.value"
                                                                                                      type="file"
                                                                                                      value="<? $img[0]->nombre_banner ?>"
                                                                                                      multiple>Search</span></span><? $img[0]->nombre_banner ?>
                                                <input id="imagen_mr"
                                                       placeholder="Add Image"
                                                       value="<?= $img[0]->nombre_banner ?>" type="text">
                                            </div>
                                            <small><cite
                                                        title="Source Title">Push on Search Button for do image
                                                    attachment</cite></small>
                                        </section>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <footer>
                                        <button style="margin: 1rem;margin-bottom: 4rem;" type="input"
                                                class="btn btn-success">Submit
                                        </button>
                                    </footer>
                            </form>
                        </div>
                    </div><!-- end widget div --></div><!-- end widget --></article><!-- END COL --></div>
        <div class="row"><!-- a blank row to get started -->
            <div class="col-sm-12"><br/> <br/></div>
        </div><!-- END ROW --></section>
</div>
<script src="/template/js/plugin/dropzone/dropzone.min.js"></script>
<script src="/template/js/plugin/markdown/markdown.min.js"></script>
<script src="/template/js/plugin/markdown/to-markdown.min.js"></script>
<script src="/template/js/plugin/markdown/bootstrap-markdown.min.js"></script>
<script src="/template/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="/template/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="/template/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">


    /*$( "#form_banner" ).submit(function( event ) {
        event.preventDefault();
        //iniciarSpinner();
        enviar();
    });

    function enviar()
    {

                    $.ajax({
                        type: "POST",
                        url: "/bo/configuracion/crear_banner",
                        data: $("form_banner").serialize()
                    })
                    .done(function( msg )
            {
                bootbox.dialog({
                message: msg,
                title: 'Attention !!!',
                buttons: {
                    success: {
                    label: "Accept",
                    className: "btn-success",
                    callback: function() {
                        location.href="/bo/configuracion/banner";
                        //FinalizarSpinner();
                    }
                }
                }
                })//fin done ajax
        });
    }*/
</script>




