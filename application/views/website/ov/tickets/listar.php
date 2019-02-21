<?php

function getColorTicket($color_ticket = 0)
{
    $color = "bg-color-";
    $colors = array(
        "green", "darken", "blue",
        "orange", "red", "greenLight",
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
                    <h2> Mis eventos </h2>
                    <div class="widget-toolbar">
                        <!-- add: non-hidden - to disable auto hide -->
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-default"
                                    data-toggle="dropdown">
                                Mostrando <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu js-status-update pull-right">
                                <li>
                                    <a href="javascript:void(0);" id="mt">Mes</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" id="ag">Agenda</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" id="td">Hoy</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

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


<!-- PAGE RELATED PLUGIN(S) -->
<script src="/template/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>
<script>
    function ver_ticket(id) {
        $.ajax({
            data: {id: id},
            type: "POST",
            url: "get_ticket"
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
                google.maps.event.addDomListener(window, 'load', initialize);
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
                $color = getColorTicket();
                $icono = getTipoTicket();

             foreach ($tickets as $ticket) :
                $init = strtotime($ticket->date_creation);
                $final = strtotime($ticket->date_final);

                $init = setFormatDateJs($init);
                $final = setFormatDateJs($final);

                $descripcion = "PRICE ESTIMATED : $ticket->amount $";
                $descripcion .= "<br><h1>".
                   " <a class='btn btn-success' onclick='ver_ticket($ticket->id);'>".
                    "See Details</a>".
                    "</h1>";
                ?>
                {
                    title: 'Ticket #<?=$ticket->id;?>',
                    start: new Date('<?=$ticket->date_creation;?>'),
                    end: new Date('<?=$ticket->date_final;?>'),
                    description: "<?=$descripcion;?>",
                    className: ['event', '<?=$color;?>'],
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
            return false;
        });

        $('#calendar-buttons #btn-next').click(function () {
            $('.fc-button-next').click();
            return false;
        });

        $('#calendar-buttons #btn-today').click(function () {
            $('.fc-button-today').click();
            return false;
        });

        $('#mt').click(function () {
            $('#calendar').fullCalendar('changeView', 'month');
        });

        $('#ag').click(function () {
            $('#calendar').fullCalendar('changeView', 'agendaWeek');
        });

        $('#td').click(function () {
            $('#calendar').fullCalendar('changeView', 'agendaDay');
        });

        $('#calendar').fullCalendar('changeView', 'agendaDay');

    })

</script>
