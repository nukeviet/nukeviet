<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/{MODULE_THEME}_jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_THEME}/jwplayer/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="KzcW0VrDegOG/Vl8Wb9X3JLUql+72MdP1coaag==";</script>
<!-- BEGIN: video_flash -->
<div class="detailContent clearfix">
	<div class="videoTitle" id="videoTitle">
		{DETAILCONTENT.title}
	</div>
	<div class="videoplayer">
		<div class="cont">
			<div id="videoCont"></div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
$(function() {
    var ele = "videoCont";
    var a = $("#" + ele).outerWidth(), b;
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
        autostart: {MODULECONFIG.playerAutostart} ? true : false
    });
});
</script>
<!-- END: video_flash -->

<!-- BEGIN: video_youtube -->
<div class="detailContent clearfix">
	<div class="videoTitle" id="videoTitle">
		{DETAILCONTENT.title}
	</div>
</div>
<script type="text/javascript">
$(function() {
    var a = $("div.detailContent").width(), b;
    640 < a && (a = 640);
    b = a;
    a = Math.ceil(45 * a / 80) + 4;
    $("div.detailContent").append('<div class="videoplayer"><div class="clearfix" style="height: ' + a + 'px;width:' + b + 'px;margin:0 auto;"><iframe class="detailContent clearfix" allowfullscreen="" frameborder="0" style="height: ' + a + 'px;width:' + b + 'px" src="//www.youtube.com/embed/{CODE}?rel=0&amp;controls=1{autoplay}"></iframe></div></div>');
});
</script>
<!-- END: video_youtube -->
<!-- BEGIN: topicList -->
			<div class="topic_List m_b10">
                            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/"><span itemprop="title">{OTHETP}</span></a> » </li>
                             <!-- BEGIN: row1 -->
                            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="{OTHERTOPIC.href}" alt="{OTHERTOPIC.title}"><span itemprop="title" class="m-l5"> {OTHERTOPIC.title} </span></a></li>
                         <!-- END: row1 -->
                        </div>			
<!-- END: topicList -->
[HEADER]
<!-- BEGIN: otherClips -->
<div class="news_column">
     <!-- BEGIN: otherClipsContent -->
    <div class="panel panel-default">
        <div class="panel-body featured">
            <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}"><img id="imghome" alt="{OTHERCLIPSCONTENT.title}" src="{OTHERCLIPSCONTENT.img}" class="img-thumbnail pull-left imghome" width="100" /></a>
            <h2 class="other_title">
                <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.sortTitle}</a>
                <!-- BEGIN: newday -->
                <span class="icon_new"></span>
                <!-- END: newday -->
            </h2>
            <div class="text-muted">
                <ul class="list-unstyled list-inline">
                    <li><em class="fa fa-eye">&nbsp;</em> {LANG.viewHits} :{OTHERCLIPSCONTENT.view}</li>
                </ul>
            </div>
            <!-- BEGIN: clearfix --><div class="clearfix"></div><!-- END: clearfix -->
        </div>
    </div>
     <!-- END: otherClipsContent -->
</div>
<hr />
<!-- BEGIN: nv_generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: nv_generate_page -->
<!-- END: otherClips -->
<script type="text/javascript">
$('.otherClips .otherClipsContent .ctn1 .ctn2').matchHeight({ property: 'min-height' });
</script>
<!-- END: main -->