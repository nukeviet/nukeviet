<!-- BEGIN: main -->
<link type="text/css" rel="stylesheet" href="{NV_STATIC_URL}themes/{TEMPLATE}/css/jquery.ui.tabs.css" />
<link type="text/css" rel="stylesheet" href="{NV_STATIC_URL}themes/{TEMPLATE}/css/contentslider.css" />
<script src="{ASSETS_STATIC_URL}/js/jquery/jquery.imgpreload.min.js"></script>
<script src="{NV_STATIC_URL}themes/default/js/contentslider.js"></script>
<script src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<div id="topnews" class="panel panel-default clearfix" style="display:none">
    <div class="row">
        <!-- BEGIN: hots_news_img -->
        <div class="col-md-12">
            <div id="slider1" class="sliderwrapper">
                <!-- BEGIN: loop -->
                <div class="contentdiv clearfix">
                    <a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}"><img class="img-responsive" id="slImg{HOTSNEWS.imgID}" src="{PIX_IMG}" alt="{HOTSNEWS.image_alt}" /></a>
                    <div class="h3 margin-bottom"><a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}"><strong>{HOTSNEWS.title}</strong></a></div>
                </div>
                <!-- END: loop -->
            </div>
            <div id="paginate-slider1" class="pagination">&nbsp;</div>
        </div>
        <!-- END: hots_news_img -->
        <div id="tabs" class="col-md-12 tabs">
            <ul>
                <!-- BEGIN: loop_tabs_title -->
                <li>
                    <a href="#tabs-{TAB_TITLE.id}"><span><span>{TAB_TITLE.title}</span></span></a>
                </li>
                <!-- END: loop_tabs_title -->
            </ul>
            <div class="clear">&nbsp;</div>
            <!-- BEGIN: loop_tabs_content -->
            <div id="tabs-{TAB_TITLE.id}">
                <!-- BEGIN: content -->
                <ul class="lastest-news">
                    <!-- BEGIN: loop -->
                    <li>
                        <a class="show" href="{LASTEST.link}" title="{LASTEST.title}">{LASTEST.title}</a>
                    </li>
                    <!-- END: loop -->
                </ul>
                <!-- END: content -->
            </div>
            <!-- END: loop_tabs_content -->
        </div>
    </div>
</div>
<!-- BEGIN: imgpreload -->
<script>
$(function() {
    var b = [{IMGPRELOAD}];
    $.imgpreload(b, function() {
        for (var c = b.length, a = 0; a < c; a++) $("#slImg" + a).attr("src", b[a]);
        featuredcontentslider.init({
            id: "slider1",
            contentsource: ["inline", ""],
            toc: "#increment",
            nextprev: ["&nbsp;", "&nbsp;"],
            revealtype: "click",
            enablefade: [true, 0.2],
            autorotate: [true, 3E3],
            onChange: function() {}
        });
        $("#tabs").tabs({
            ajaxOptions: {
                error: function(e, f, g, d) {
                    $(d.hash).html("Couldnt load this tab.")
                }
            }
        });
        $("#topnews").show()
    });
});
</script>
<!-- END: imgpreload -->
<!-- END: main -->