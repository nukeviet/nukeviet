<!-- BEGIN: main -->
<link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.ui.tabs.css" />
<link type="text/css" rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/contentslider.css" />
<div id="topnews" class="panel panel-default clearfix" style="display:none">
    <div class="row">
        <!-- BEGIN: hots_news_img -->
        <div class="col-md-12">
            <div id="slider1" class="sliderwrapper">
                <!-- BEGIN: loop -->
                <div class="contentdiv clearfix">
                    <a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}" {HOTSNEWS.target_blank}><img class="img-responsive" id="slImg{HOTSNEWS.imgID}" src="{PIX_IMG}" alt="{HOTSNEWS.image_alt}" /></a>
                    <h3><a title="{HOTSNEWS.title}" href="{HOTSNEWS.link}" {HOTSNEWS.target_blank}>{HOTSNEWS.title}</a></h3>
                </div>
                <!-- END: loop -->
            </div>
            <div id="paginate-slider1" class="slider-pagination">&nbsp;</div>
        </div>
        <!-- END: hots_news_img -->
        <div class="col-md-12">
            <div id="tabs" class="tabs">
                <ul class="clearfix">
                    <!-- BEGIN: loop_tabs_title -->
                    <li>
                        <a href="#tabs-{TAB_TITLE.id}"><span><span>{TAB_TITLE.title}</span></span></a>
                    </li>
                    <!-- END: loop_tabs_title -->
                </ul>
                <!-- BEGIN: loop_tabs_content -->
                <div id="tabs-{TAB_TITLE.id}">
                    <!-- BEGIN: content -->
                    <ul class="lastest-news">
                        <!-- BEGIN: loop -->
                        <li>
                            <a {TITLE} class="show" href="{LASTEST.link}" {LASTEST.target_blank} data-content="{LASTEST.hometext_clean}" data-img="{LASTEST.homeimgfile}" data-rel="block_headline_tooltip">{LASTEST.title}</a>
                        </li>
                        <!-- END: loop -->
                    </ul>
                    <!-- END: content -->
                </div>
                <!-- END: loop_tabs_content -->
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: tooltip -->
<script type="text/javascript">
$(document).ready(function() {$("[data-rel='block_headline_tooltip'][data-content!='']").tooltip({
    placement: "{TOOLTIP_POSITION}",
    html: true,
    title: function(){return ( $(this).data('img') == '' ? '' : '<img class="img-thumbnail pull-left margin_image" src="' + $(this).data('img') + '" width="90" />' ) + '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
});});
</script>
<!-- END: tooltip -->
<!-- END: main -->