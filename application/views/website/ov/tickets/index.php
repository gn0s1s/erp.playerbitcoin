<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark">
                <a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>> Tickets
				</span>
            </h1>
        </div>
    </div>
    <div class="well">
        <fieldset>
            <legend>Tickets</legend>

            <div class="col-lg-2 col-sm-4 col-md-4 col-xs-12">
            </div>
            <div class="col-sm-3 col-xs-12">
                <a href="/ov/tickets/manual">
                    <div class="well well-sm txt-color-white text-center link_dashboard"
                         style="background:<?= $style[0]->btn_1_color ?>">
                        <i class="fa fa-edit fa-3x"></i>
                        <h5>New Manual</h5>
                    </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12">
                <a href="/ov/tickets/automatic">
                    <div class="well well-sm txt-color-white text-center link_dashboard"
                         style="background:<?= $style[0]->btn_2_color ?>">
                        <i class="fa fa-cogs fa-3x"></i>
                        <h5>New Automatic</h5>
                    </div>
                </a>
            </div>
            <div class="col-sm-3 col-xs-12">
                <a href="/listTickets">
                    <div class="well well-sm txt-color-white text-center link_dashboard"
                         style="background:<?= $style[0]->btn_1_color ?>">
                        <i class="fa fa-list-alt fa-3x"></i>
                        <h5>List Tickets</h5>
                    </div>
                </a>
            </div>
        </fieldset>
    </div>
</div>	
