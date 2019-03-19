<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>> <a href="/bo/configuracion">Settings</a> > <a href="/bo/configuracion/empresa">My Business</a> >Update Identity</span>
            </h1>
        </div>
    </div>
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
                        <h2>My Business&rsquo; Data</h2>
                    </header><!-- widget div-->
                    <div>
                        <form id="form_empresa" method="post" action="/bo/admin/empresa_multinivel" role="form"
                              class="smart-form">
                            <fieldset>
                                <legend>The Business&rsquo; Billing</legend>
                                <input type="hidden" value="<?= $empresa[0]->id_tributaria; ?>" name="id">
                                <section id="usuario" class="col col-3">
                                    <label class="input">Tax Identity<input required type="text" name="id_tributaria"
                                                                            placeholder="Empresa"
                                                                            value="<?= $empresa[0]->id_tributaria; ?>">
                                    </label>
                                </section>
                                <section id="usuario" class="col col-3">
                                    <label class="input">Business name<input required type="text" name="nombre"
                                                                             value="<?= $empresa[0]->nombre; ?>">
                                    </label>
                                </section>
                                <section id="usuario" class="col col-3">
                                    <label class="input">Web page<input required type="url" name="web"
                                                                        value="<?= $empresa[0]->web; ?>">
                                    </label>
                                </section>
                                <section id="usuario" class="col col-3">
                                    <label class="textarea">Business Purpose<textarea required type="text"
                                                                                      name="resolucion"
                                                                                      value=""><?= $empresa[0]->resolucion; ?></textarea>
                                    </label>
                                </section>
                                <section id="usuario" class="col col-3">
                                    <label class="textarea">Comments<textarea required type="text" name="comentarios"
                                                                              value=""><?= $empresa[0]->comentarios ?></textarea>
                                    </label>
                                </section><!--  <section class="col col-3">Regimen fiscal
                                             <label class="select">
                                                 <select class="custom-scroll" name="regimen">
                                <? //foreach ($regimen as $key){?>
                                <?php //if($key->id_regimen == $empresa[0]->regimen){?>
                                                         <option selected value="<? //$key->id_regimen ?>">
                                <? //$key->abreviatura." ".$key->descripcion?></option>
                                <?php //}else{?>
                                                                                        <option value="<? //$key->id_regimen ?>">
                                <? //$key->abreviatura." ".$key->descripcion?></option>
                                <? //}}?>
                                                 </select>
                                             </label>
                                         </section>--></fieldset>
                            <fieldset>
                                <legend>Tax residence</legend>
                                <div id="dir" class="row">
                                    <section class="col col-3">Country<label class="select">
                                            <select id="pais" required
                                                    name="pais"><?php foreach ($pais as $key) { ?><?php if ($key->Code == $empresa[0]->pais) { ?>
                                                    <option selected
                                                            value="<?= $key->Code ?>"><?= $key->Name ?></option><?php } else { ?>
                                                    <option value="<?= $key->Code ?>"><?= $key->Name ?></option><?php }
                                                } ?></select>
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">ZIPCODE<input type="text" id="postal" name="postal"
                                                                           value="<?= $empresa[0]->postal; ?>">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">Tax residence<input type="text" name="direccion"
                                                                                 value="<?= $empresa[0]->direccion; ?>">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">Tel.<input required type="text" name="fijo"
                                                                        value="<?= $empresa[0]->fijo; ?>">
                                        </label>&apos;
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">Cell.<input required type="text" name="movil"
                                                                         value="<?= $empresa[0]->movil; ?>">
                                        </label>
                                    </section>
                                    <section id="colonia" class="col col-3">
                                        <label class="input">City/Town<input type="text" name="ciudad"
                                                                             value="<?= $empresa[0]->ciudad; ?>">
                                        </label>
                                    </section>
                                    <section id="municipio" class="col col-3">
                                        <label class="input">State<input type="text" name="provincia"
                                                                         value="<?= $empresa[0]->provincia; ?>">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <footer>
                                <button style="margin: 1rem;margin-bottom: 4rem;" type="input" class="btn btn-success">
                                    Submit
                                </button>
                            </footer>
                        </form>
                    </div><!-- end widget div --></div><!-- end widget --></article><!-- END COL --></div>
        <div class="row"><!-- a blank row to get started -->
            <div class="col-sm-12"><br/> <br/></div>
        </div><!-- END ROW --></section>
</div>

<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">

    $("#form_empresa").submit(function (event) {
        event.preventDefault();
        iniciarSpinner();
        enviar();
    });

    function enviar() {


        $.ajax({
            type: "POST",
            url: "/bo/admin/empresa_multinivel",
            data: $("#form_empresa").serialize()
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: 'My Business',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {
                                location.href = "/bo/configuracion/datos_empresa";
                                FinalizarSpinner();
                            }
                        }
                    }
                })//fin done ajax

            });//Fin callback bootbox
    }


</script>

