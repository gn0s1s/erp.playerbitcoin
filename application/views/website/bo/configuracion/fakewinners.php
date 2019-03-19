<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>&gt; <a href="/bo/configuracion/">Settings</a></span> <span>&gt; <a
                            href="/bo/configuracion/empresa">My Business</a></span> <span>&gt; E-mail dependences</span>
            </h1>
        </div>
    </div><?php if ($this->session->flashdata('success')) {
        echo '<div class="alert alert-success fade in">
                <button class="close" data-dismiss="alert">
                    ×
                </button>
                <i class="fa-fw fa fa-check"></i>
                <strong>Felicidades </strong> ' . $this->session->flashdata('success') . '
            </div>';
    }
    ?> <?php if ($this->session->flashdata('error')) {
        echo '<div class="alert alert-danger fade in">
                <button class="close" data-dismiss="alert">
                    ×
                </button>
                <i class="fa-fw fa fa-check"></i>
                <strong>Alerta </strong> ' . $this->session->flashdata('error') . '
			</div>';
    }
    ?>
    <section id="widget-grid" class=""><!-- START ROW -->
        <div class="row"><!-- new  COL START -->
            <article class="col-sm-12 col-md-12 col-lg-12"><!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false"><!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

					--> <!-- widget div-->
                    <div>
                        <fieldset id="pswd">
                            <form class="smart-form" action="/bo/configuracion/updatefakewinners"
                                  method="POST" role="form">
                                <legend>Fake Winners</legend>
                                <br>
                                <div id="winners_div" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                   <?php foreach ($fakeWinners as $key => $winner) : ?>

                                       <div id="win_<?=$key;?>" class="winners col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                           <label class="input col-xs-12 col-sm-9 col-md-1 col-lg-1">
                                               <b class="pull-right ">Username</b>
                                           </label>
                                           <label class="input col-xs-12 col-sm-9 col-md-3 col-lg-3">
                                               <input type="text" class="form-control" name="username[]"
                                                      placeholder="username" pattern="[A-z0-9]{1,}"
                                                      value='<?=$winner->username;?>' required>
                                           </label>
                                           <label class="input col-xs-12 col-sm-9 col-md-2 col-lg-2">
                                               <b class="pull-right ">Full Name</b>
                                           </label>
                                           <label class="input col-xs-12 col-sm-9 col-md-3 col-lg-3">
                                               <input type="text" class="form-control" name="fullname[]"
                                                      placeholder="fullname"
                                                      value='<?=$winner->fullname;?>' required>
                                           </label>
                                           <label class="input col-xs-12 col-sm-12 col-md-3 col-lg-3"><br><br><br></label>
                                       </div>


                                   <?php endforeach; ?>

                                </div>
                                <footer>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <label class="input col-xs-12 col-sm-4">
                                        </label>
                                        <button style="margin-bottom: 4rem;" type="button" onclick="add_winner()"
                                                class="btn btn-warning col-xs-12 col-sm-4">
                                            Add Winner
                                        </button>
                                        <button style="margin-bottom: 4rem;" type="button" onclick="remove_winner()"
                                                class="btn btn-danger col-xs-12 col-sm-4">
                                            Remove Winner
                                        </button>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <button style="margin-bottom: 4rem;" type="submit"
                                                class="btn btn-success col-xs-12 col-sm-6">Update
                                        </button>
                                    </div>
                                </footer>
                            </form>
                        </fieldset>
                    </div>
                </div>
        </div>
    </section>
</div>
<script>
    var winners = $('.winners').length;
    function add_winner(){
        winners = $('.winners').length;
        var html = ' <div id="win_'+winners+'"' +
            ' class="winners col-xs-12 col-sm-12 col-md-12 col-lg-12">' +
            '<label class="input col-xs-12 col-sm-9 col-md-1 col-lg-1">' +
            '<b class="pull-right ">Username</b>' +
            '</label>' +
            '<label class="input col-xs-12 col-sm-9 col-md-3 col-lg-3">' +
            '<input type="text" class="form-control" name="username[]" ' +
            'placeholder="username" pattern="[A-z0-9]{1,}" required>' +
            '</label>' +
            '<label class="input col-xs-12 col-sm-9 col-md-2 col-lg-2">' +
            '<b class="pull-right ">Full Name</b>' +
            '</label>' +
            '<label class="input col-xs-12 col-sm-9 col-md-3 col-lg-3">' +
            '<input type="text" class="form-control" name="fullname[]" ' +
            'placeholder="fullname"  required>' +
            '</label>' +
            '<label class="input col-xs-12 col-sm-12 col-md-3 col-lg-3">' +
            '<br><br><br></label>' +
            '</div>';

        $('#winners_div').append(html);
    }
    function remove_winner() {
        winners = $('.winners').length;

        if(winners <=1)
            return false;

        var lastdiv = winners-1;
        $('#win_'+lastdiv).remove();
    }
</script>