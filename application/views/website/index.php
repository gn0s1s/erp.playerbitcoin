<script>
    $("ul.nav.navbar-nav").addClass('hide');
    $("ul.nav.navbar-nav.navbar-right").removeClass('hide');
</script>
<div id="content">

    <!-- row -->
    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <!--Inica the seccion of the Profile & red-->
                    <div class="row well" class="text-center">

                        <div class="col col-md-12">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <img src="/logo.png" width="100%" alt="logo">
                            </div>
                        </div>

                        <div class="col col-md-12 text-center margin-top-10 ">
                            <h1>SELECCIONE UNA of LAS OPCIONES</h1>
                        </div>

                        <div class="col col-md-12 margin-top-10">
                            <div class="col-lg-4 col-md-3 col-sm-2"></div>
                            <div class="col-lg-4 col-md-7 col-sm-9">
                                <div class="col-sm-6 col-xs-12 text-center">
                                    <a href="/play/dashboard">
                                        <div id="play-boton"
                                             class="link_dashboard well well-sm txt-color-white text-center ">
                                            <i class="fa fa-btc fa-3x"></i>
                                            <h3>JUGAR/PLAY</h3>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-xs-12 text-center">
                                    <a href="/ov/dashboard">
                                        <div id="ov-boton"
                                             class="link_dashboard well well-sm txt-color-white text-center ">
                                            <i class="fa fa-tablet fa-3x"></i>
                                            <h3>MESA of JUEGO</h3>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
    <style>

        #ov-boton {
            background: #09C;
        }

        #play-boton {
            background: #C00;
        }

        .link_dashboard {
            box-shadow: none;
            width: 12.5em;
            padding: 2em 0;
            border-radius: 100%;

        }


    </style>
    <script>
        resizelinks();

        function resizelinks(){
            var wdth= $(".link_dashboard").parent().parent().width();
            console.log("screen "+wdth);
            if(screen.width < 768){
                var link = $(".link_dashboard").width();
                link/=2;wdth/=2;
                var margin = wdth-link;
                console.log("left "+margin+" -> "+wdth+" - "+link);
                $(".link_dashboard").css("left",margin+"px");
            }
        }

    </script>