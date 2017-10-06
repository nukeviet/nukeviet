<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/jquery.matchHeight-min.js"></script>
<!-- BEGIN: topicList -->
<div class="otherTopic marginbottom15 clearfix">
    <div class="otherTopicCont">
    <!-- BEGIN: topicListMain -->
    <div>
    <strong>{OTHETP}:</strong>
    <!-- BEGIN: row1 -->
    &nbsp;&nbsp;<a href="{OTHERTOPIC.href}"><!-- BEGIN: img1 --><img src="{NV_BASE_SITEURL}{OTHERTOPIC.img}" width="24" height="24" alt="" /><!-- END: img1 -->{OTHERTOPIC.title}</a><!-- BEGIN: iss1 --><span>&darr;</span><!-- END: iss1 -->
    <!-- END: row1 -->
    </div>
    <!-- END: topicListMain -->
    <!-- BEGIN: topicListSub -->
    <div>
    <strong>{LANG.otherSubTopic}:</strong>
    <!-- BEGIN: row2 -->
    &nbsp;&nbsp;<a href="{OTHERSUBTOPIC.href}"><!-- BEGIN: img2 --><img src="{NV_BASE_SITEURL}{OTHERSUBTOPIC.img}" width="24" height="24" alt="" /><!-- END: img2 -->{OTHERSUBTOPIC.title}</a><!-- BEGIN: iss2 --><span>&darr;</span><!-- END: iss2 -->
    <!-- END: row2 -->
    </div>
    <!-- END: topicListSub -->
    </div>
</div>
<!-- END: topicList -->

<!-- BEGIN: otherClips -->
<div class="otherClips marginbottom15 clearfix">
<!-- BEGIN: otherClipsContent -->
<div class="otherClipsContent">
    <div class="ctn1">
        <div class="ctn2">
            <div style="background:transparent url({OTHERCLIPSCONTENT.img}) no-repeat center center">
                <a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="80" /></a>
            </div>
            <div class="vtitle"><a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.sortTitle}</a></div>
            <div class="viewHits">{LANG.viewHits} <span>{OTHERCLIPSCONTENT.view}</span></div>
            <div class="play">
                <a class="otcl" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">
                <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" alt="{OTHERCLIPSCONTENT.title}" width="120" height="32" /></a>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN: clearfix --><div class="clearfix"></div><!-- END: clearfix -->
<!-- END: otherClipsContent -->
<!-- BEGIN: nv_generate_page -->
<div class="clearfix"></div>
<div class="generate_page">
<div class="ctn3">
{NV_GENERATE_PAGE}
</div>
</div>
<!-- END: nv_generate_page -->
</div>
<!-- END: otherClips -->
<script type="text/javascript">
$('.otherClips .otherClipsContent .ctn1 .ctn2').matchHeight({ property: 'min-height' });
</script>
<!-- END: main -->