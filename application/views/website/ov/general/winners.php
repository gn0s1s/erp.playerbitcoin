<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark">
                <a class="backHome" href="/ov">
                    <i class="fa fa-home"></i> Menu</a>
                <span>
                    > Winners
				</span>
            </h1>
        </div>
    </div>
    <section id="widget-grid" class="">
        <!-- START ROW -->
        <div class="row">
            <!-- new  COL START -->
            <article class="col-sm-12 col-md-12 col-lg-12">
                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false"
                     data-widget-colorbutton="false">
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

                    <!-- widget div-->
                    <div>

                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->

                        </div>
                        <!-- end widget edit box -->
                        <!-- widget content -->
                        <div class="widget-body">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a href="#s1" data-toggle="tab" onclick="load_winners();">This month</a>
                                </li>
                                <li>
                                    <a href="#s2" data-toggle="tab" onclick="load_winners('h_winners');">Historical</a>
                                </li>
                            </ul>
                            <div id="myTabContent1" class="tab-content padding-10">
                                <div class="tab-pane fade in active" id="s1">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="well well-sm">
                                                <div class="row" id="m_winners">



                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="s2">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="well well-sm">
                                                <div class="row" id="h_winners">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end widget content -->

                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!-- END COL -->
        </div>
        <div class="row">
            <!-- a blank row to get started -->
            <div class="col-sm-12">
                <br/>
                <br/>
            </div>
        </div>
        <!-- END ROW -->
    </section>
    <!-- end widget grid -->
</div>

<script src="/template/js/plugin/markdown/markdown.min.js"></script>
<script src="/template/js/plugin/markdown/to-markdown.min.js"></script>
<script src="/template/js/plugin/markdown/bootstrap-markdown.min.js"></script>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        pageSetUp();
        load_winners();

        $("#mymarkdown").markdown({
            autofocus: false,
            savable: false
        })

        /*
                * TODO: add a way to add more todo's to list
                */

        // initialize sortable
        $(function () {
            $("#sortable1, #sortable2").sortable({
                handle: '.handle',
                connectWith: ".todo",
                update: countTasks
            }).disableSelection();
        });

        // check and uncheck
        $('.todo .checkbox > input[type="checkbox"]').click(function () {
            var $this = $(this).parent().parent().parent();

            if ($(this).prop('checked')) {
                $this.addClass("complete");

                // remove this if you want to undo a check list once checked
                //$(this).attr("disabled", true);
                $(this).parent().hide();

                // once clicked - add class, copy to memory then remove and add to sortable3
                $this.slideUp(500, function () {
                    $this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
                    $this.remove();
                    countTasks();
                });
            } else {
                // insert undo code here...
            }

        })

        // count tasks
        function countTasks() {

            $('.todo-group-title').each(function () {
                var $this = $(this);
                $this.find(".num-of-tasks").text($this.next().find("li").size());
            });

        }

    })

    function load_winners(id = "m_winners") {

        if(!id)
            id = "m_winners";

        $.ajax({
            type: "POST",
            url: "getWinners",
            data: {id : id}
        })
            .done(function (msg) {

                $('#'+id).html(msg)

            });
    }


</script>
