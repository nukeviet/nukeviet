<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/flexslider/flexslider.css" type="text/css" media="screen" />
<script defer src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/flexslider/jquery.flexslider.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>

<div id="videotop{BID}" class="flexslider videotop col-md-24">
    <ul class="groups_notice slides">
        <!-- BEGIN: loop -->
        <li class="clearfix">
            <!-- BEGIN: youtube -->
            <div class="clearfix">
                <iframe id="ytvideo_bl{BID}_v{ROW.id}" allowfullscreen="allowfullscreen" frameborder="0"  src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1&amp;autohide=0"></iframe>
            </div>
            <script type="text/javascript">
            $(function() {
                var a = $("#videotop{BID}").outerWidth(), b;
                640 < a && (a = 640);
                b = a;
                a = Math.ceil(45 * a / 80) + 4;
                $('#ytvideo_bl{BID}_v{ROW.id}').width(b);
                $('#ytvideo_bl{BID}_v{ROW.id}').height(a);
                $('#ytvideo_bl{BID}_v{ROW.id}').parent().css({
                    width: b,
                    height: a,
                    margin: "0 auto"
                });
            });
            </script>
            <!-- END: youtube -->

            <!-- BEGIN: player -->
            <div class="clearfix">
                <div class="videoplayer_bl">
                    <div class="cont">
                        <div id="videoCont_bl{BID}_v{ROW.id}"></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function() {
                    var ele = "videoCont_bl{BID}_v{ROW.id}";
                    var a = $("#videotop{BID}").outerWidth(), b;
                    640 < a && (a = 640);
                    b = a;
                    a = Math.ceil(45 * a / 80) + 4;
                    $("#" + ele).parent().css({
                        width: b,
                        height: a,
                        margin: "0 auto"
                    });
                    jwplayer(ele).setup({
                        file: "{DETAILCONTENT.filepath}",
                        width: b,
                        height: a,
                        autostart: false
                    });
                });
                </script>
            </div>
            <!-- END: player -->
        </li>
        <!-- END: loop -->
    </ul>
</div>

<ul class="flex-direction-nav">
    <li class="flex-nav-prev"><a href="#" class="flex-next">Sau <i class="fa fa-angle-right fa-lg"></i></a></li>
    <li class="flex-nav-next"><a href="#" class="flex-prev"><i class="fa fa-angle-left fa-lg"></i> Trước</a></li>
</ul>

<script type="text/javascript">
$(window).on('load', function() {
    $('#videotop{BID}').flexslider({
        animation : "slide",
        controlNav : false,
        animationLoop : false,
        slideshow : false,
        controlsContainer: $(".custom-controls-container"),
        customDirectionNav: $(".custom-navigation a")
    });
});
</script>

<!-- END: main -->