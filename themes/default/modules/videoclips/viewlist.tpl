<!-- BEGIN: main -->
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
                    <li><em class="fa fa-eye">&nbsp;</em> {LANG.viewHits}: {OTHERCLIPSCONTENT.view}</li>
                </ul>
            </div>
            <p>{OTHERCLIPSCONTENT.hometext}</p>
            <!-- BEGIN: clearfix -->
            <div class="clearfix"></div>
            <!-- END: clearfix -->
        </div>
    </div>
    <!-- END: otherClipsContent -->
    <!-- BEGIN: nv_generate_page -->
    <div class="text-center">{NV_GENERATE_PAGE}</div>
    <!-- END: nv_generate_page -->
</div>
<!-- END: main -->
