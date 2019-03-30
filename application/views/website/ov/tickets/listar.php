<?php

function getColorTicket($color_ticket = 'ACT')
{
    $color = "bg-color-";
    $colors = array(
        'DES'=>"silver", 'ACT'=>"green", "darken",
        "orange", 'BLK'=>"red", "greenLight",
        "blueLight"
    );

    if (!isset($colors[$color_ticket]))
        $color_ticket = 0;

    $color .= $colors[$color_ticket];

    return $color;
}

function getTipoTicket($tipo_ticket = 0)
{
    $icono = "fa-";
    $tipos = array(
        "btc", "info", "lock",
        "warning", "user", "check",
        "clock-o"
    );

    if (!isset($tipos[$tipo_ticket]))
        $tipo_ticket = 0;

    $icono .= $tipos[$tipo_ticket];

    return $icono;
}


function setFormatDateJs($timestamp)
{
    $formatTime = array(
        date('Y', $timestamp),
        date('m', $timestamp),
        date('d', $timestamp),
        date('H', $timestamp),
        date('i', $timestamp)
    );
    $formatTime = implode(",", $formatTime);
    return $formatTime;
}

?>
<!-- MAIN CONTENT -->
<div id="content">

    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <h1 class="page-title txt-color-blueDark">

                <!-- PAGE HEADER -->

                <a class="backHome" href="/ov/dashboard">
                    <i class="fa-fw fa fa-home"></i> Menu</a>
                <span>>
								<a href="/ov/tickets"> Tickets</a> > List All
							</span>
            </h1>
        </div>
    </div>
    <!-- row -->
    <div class="row">


        <div class="col-sm-12 col-md-12 col-lg-12">

            <!-- new  widget -->
            <div class="jarviswidget jarviswidget-color-blueDark">

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
                    <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                    <h2>Tickets</h2>
                    <div class="widget-toolbar">
                        <!-- add: non-hidden - to disable auto hide -->
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-default"
                                    data-toggle="dropdown">
                                Show <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu js-status-update pull-right">
                                <li>
                                    <a href="javascript:void(0);" id="mt">Month</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" id="ag">Week</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" id="td">Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>
                <div class="col-xs-12">
                    <div class="col-xs-6"></div>
                    <div class="col-xs-1">
                        <a title="See Details" href="#" class="btn btn-success"></a>
                        <br>See Details
                    </div>
                    <div class="col-xs-1">
                        <a title="Settings" href="#" class="btn btn-info"></a>
                        <br>Settings
                    </div>
                    <div class="col-xs-1">
                        <a title="Enable / Disable" href="#" class="btn btn-warning"></a>
                        <br>Enable / Disable
                    </div>
                    <div class="col-xs-1">
                        <a title="Actived" href="#" class="btn bg-color-green"></a>
                        <br>Actived
                    </div>
                    <div class="col-xs-1">
                        <a title="Inactived" href="#" class="btn bg-color-silver"></a>
                        <br>Inactived
                    </div>
                    <div class="col-xs-1">
                        <a title="Blocked" href="#" class="btn bg-color-red"></a>
                        <br>Blocked
                    </div>

                </div>

                <!-- widget div-->
                <div>

                    <div class="widget-body no-padding">
                        <!-- content goes here -->
                        <div class="widget-body-toolbar">

                            <div id="calendar-buttons">

                                <div class="btn-group">
                                    <a href="javascript:void(0)"
                                       class="btn btn-default btn-xs" id="btn-prev">
                                        <i class="fa fa-chevron-left"></i></a>
                                    <a href="javascript:void(0)"
                                       class="btn btn-default btn-xs" id="btn-next">
                                        <i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="calendar"></div>

                        <!-- end content -->
                    </div>

                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->

        </div>

    </div>

    <!-- end row -->

</div>
<!-- END MAIN CONTENT -->
<div class="row">
    <!-- a blank row to get started -->
    <div class="col-sm-12">
        <br/>
        <br/>
    </div>
</div>

<style>
img.fa{
    height: 1em;
    padding: 1rem 0;
}
</style>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="/template/js/plugin/jquery-nestable/jquery.nestable.min.js"></script>
<script src="/template/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>
<script>
    function ver_ticket(id) {
        $.ajax({
            data: {id: id},
            type: "POST",
            url: "/ov/tickets/get_ticket"
        })
            .done(function (msg) {

                bootbox.dialog({
                    message: msg,
                    title: "Ticket # " + id,
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function () {

                            }
                        }
                    }
                });

                balance();
            });
    }
    function editar_ticket(id) {
        $.ajax({
            data: {id: id},
            type: "POST",
            url: "/ov/tickets/edit_ticket"
        })
            .done(function (msg) {
                bootbox.dialog({
                    message: msg,
                    title: "Ticket # " + id,
                    buttons: {
                        success: {
                            label: "Update",
                            className: "btn-success",
                            callback: function () {
                                enviar();
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

                balance();
                date_stamp(id);

            });
    }
    function estatus_ticket(id) {
        $.ajax({
            data: {id: id},
            type: "POST",
            url: "/ov/tickets/estatus_ticket"
        })
            .done(function (msg) {

                bootbox.dialog({
                    message: msg,
                    title: "Ticket # " + id,
                    buttons: {
                        success: {
                            label: "Update",
                            className: "btn-success",
                            callback: function () {
                                location.href="/listTickets";
                            }
                        }
                    }
                });

            });
    }
    function date_stamp(id = false){

        <?php
        date_default_timezone_set('UTC');
        $date_init = date('Y-m');
        $day_init = date('d');
        $date_stamp = date('Y-m-d');

        if($day_init > 21){
            $fecha_sub = new DateTime($date_stamp);
            date_add($fecha_sub, date_interval_create_from_date_string("1 day"));
            $date_stamp = date_format($fecha_sub, 'Y-m-d');
        }

        ?>

        $(function()
        {
            a = new Date();
            a = a.getFullYear()-18;
            a+="-12-31";
            if(id)
                a = $('#date_'+id).val();

            b = '<?=$date_stamp?>';
            $( ".datepicker" ).datepicker({
                changeMonth: true,
                maxDate: a,
                minDate: b,
                dateFormat:"yy-mm-dd",
                yearRange: "-99:+0",
            });
        });
    }

    function balance(id = ""){

        var saldo = $(".ticket").val();
        var min_value = parseInt(saldo / 5);
        min_value *= 5;
        var max_value = min_value + 5;
        max_value -= 0.01;

        var lengtht = $('#ticket_balance').length;
        console.log(lengtht+' -> '+saldo);

        if(lengtht<=0){
            <?php $balance_div = '<section id="ticket_balance" style="z-index: 100;" '.
                            'class="padding-10 alert-success col col-md-12 text-left">'.
                            '<h1 id="setRange">Range between <br/>'.
                            '<strong class="minRange"></strong> USD <br/>and<br>'.
                            '<strong class="maxRange"></strong> USD</h1>'.
                            '</section>';
            ?>
            var html_balance = '<?=$balance_div?>';
            $('.ticket').parent().append(html_balance);
        }

        $('#ticket_balance .minRange').html(min_value + "");
        $('#ticket_balance .maxRange').html(max_value + "");

        $('#ticket_'+id).html();
    }

    $( "#ticket_set" ).submit(function( event ) {
        event.preventDefault();
        enviar();
    });

    function enviar() {

        var message = '<h3>Sure you want to activeate this ticket?.</h3>'+
            ' <br/><p>Remember jacpkot date must be 24 hours later<p>';

        var data_form = $('#ticket_set').serialize();

        $.ajax({
            type: "POST",
            url: "/auth/show_dialog",
            data: {
                message: message
            },
        })
            .done(function( msg )
            {
                bootbox.dialog({
                    message: msg,
                    title: '',
                    buttons: {
                        success: {
                            label: "Accept",
                            className: "btn-success",
                            callback: function() {

                                $.ajax({
                                    type: "POST",
                                    url: "/ov/tickets/update_ticket",
                                    data: data_form
                                })
                                    .done(function( msg ) {

                                        bootbox.dialog({
                                            message: msg,
                                            title: "Attention",
                                            buttons: {
                                                success: {
                                                    label: "Ok!",
                                                    className: "btn-success",
                                                    callback: function() {
                                                        location.href="/listTickets";
                                                    }
                                                }
                                            }
                                        });
                                    });//fin Done ajax
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

</script>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!


    $(document).ready(function () {

        pageSetUp();

        "use strict";

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var hdr = {
            left: 'title',
            center: 'month,agendaWeek,agendaDay',
            right: 'prev,today,next'
        };


        /* initialize the external events

        /* initialize the calendar
         -----------------------------------------------------------------*/

        $('#calendar').fullCalendar({

            header: hdr,
            buttonText: {
                prev: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>'
            },


            select: function (start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    calendar.fullCalendar('renderEvent', {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        }, true // make the event "stick"
                    );
                }
                calendar.fullCalendar('unselect');
            },

            events: [
                <?php

                $icono = getTipoTicket();

                $i = 1;

             foreach ($tickets as $ticket) :
                $date_creation = $ticket->date_creation;
                $date_final = $ticket->date_final;
                $init = strtotime($date_creation);
                $final = strtotime($date_final);

                $init_date = setFormatDateJs($init);
                $final_date = setFormatDateJs($final);

                $color = getColorTicket($ticket->estatus);

                $descripcion = "<h5>PRICE ESTIMATED : $ticket->amount $</h5>";

                $descripcion .= "<br><h1 class='buttons_ticket' id='buttons_$i' >".
                    "<a class='btn btn-success' onclick='ver_ticket($ticket->id);'>".
                    "<img class='fa fa-eye'>".
                    "See Details</a>";

                $isBlocked = $ticket->estatus == 'BLK';
                $isInactived = $ticket->estatus == 'DES';

                if($isInactived && $isVIP)
                    $descripcion .= "<a class='btn btn-info' onclick='editar_ticket($ticket->id);'>".
                    "<img class='fa fa-edit'>".
                    "Settings</a>".
                    "<a class='hide btn btn-warning' onclick='estatus_ticket($ticket->id);'>".
                    "<img class='fa fa-check'>".
                    "Enable/Disable</a>";

                    $descripcion .= "</h1>";

                $date_creation = date('Y-m-d H:i',$init);
                $date_final = date('Y-m-d H:i',$final);

                $i++;

                ?>
                {
                    title: 'Ticket #<?=$ticket->id;?>',
                    start: new Date('<?=$date_creation;?>'),
                    end: new Date('<?=$date_final;?>'),
                    description: "<?=$descripcion;?>",
                    className: ['event', '<?=$color;?>'],
                    allDay: false,
                    icon: '<?=$icono;?>'
                },
                <?php endforeach; ?>
            ],

            eventRender: function (event, element, icon) {
                if (!event.description == "") {
                    element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
                        "</span>");
                }
                if (!event.icon == "") {
                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
                        " '></i>");
                }
            },

            windowResize: function (event, ui) {
                $('#calendar').fullCalendar('render');
            }
        });

        /* hide default buttons */
        $('.fc-header-right, .fc-header-center').hide();


        $('#calendar-buttons #btn-prev').click(function () {
            $('.fc-button-prev').click();
            setSizeBtn();
            return false;
        });

        $('#calendar-buttons #btn-next').click(function () {
            $('.fc-button-next').click();
            setSizeBtn();
            return false;
        });

        $('#calendar-buttons #btn-today').click(function () {
            $('.fc-button-today').click();
            return false;
        });

        $('#mt').click(function () {
            $('#calendar').fullCalendar('changeView', 'month');
            setSizeBtn();
        });

        $('#ag').click(function () {
            $('#calendar').fullCalendar('changeView', 'agendaWeek');
            setSizeBtn();
        });

        $('#td').click(function () {
            $('#calendar').fullCalendar('changeView', 'agendaDay');
            setSizeBtn();
        });

        $('#calendar').fullCalendar('changeView', 'agendaWeek');


        setSizeBtn();
    });

    function setSizeBtn(){

        var i = 1;
       $('.fc-event').each(function () {
           var divEvent = $(this);
           var id='event_panel_'+i;
           divEvent.attr('id',id);
           var widthDiv = divEvent.width();

           console.log(id+' '+widthDiv);

           if (widthDiv <= 165) {
               /*$('#'+id+' .btn').css(
                   {
                       'width': '2.5em',
                       'display': 'block',
                       'color': 'transparent'
                   }
               );*/
               var h5title = '#' + id + ' h5';
               var subtitle = $(h5title).html();
               $(h5title).remove();
               if (widthDiv > 50)
                   $('#' + id + ' h1').parent().append(subtitle);

               $('#' + id + ' .fc-event-title').css(
                   {
                       'writing-mode': 'tb-rl'
                   }
               );
               $('#' + id + ' .btn').css(
                   {
                       'padding': '6px 4px',
                       'vertical-align': 'bottom'
                   }
               );
           }


           i++;
       })
    }


</script>
