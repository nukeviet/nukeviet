<!-- BEGIN: main -->
<div class="viewgrid">
    <div class="row">
        <!-- BEGIN: otherClipsContent -->
        <div class="col-xs-24 col-sm-8 col-md-8">
            <div class="panel panel-default panel-item-clip">
                <div class="panel-body text-center">
                    <a class="clip-thumb" href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}" style="background-image: url('{OTHERCLIPSCONTENT.img}');"><img alt="{OTHERCLIPSCONTENT.title}" src="{OTHERCLIPSCONTENT.img}" class="img-thumbnail imghome" /></a>
                    <h3>
                        <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}">{OTHERCLIPSCONTENT.sortTitle}</a>
                        <!-- BEGIN: newday -->
                        <span class="icon_new"></span>
                        <!-- END: newday -->
                    </h3>
                    <div class="text-muted">
                        <ul class="list-unstyled list-inline">
                            <li><i class="fa fa-eye"></i> {LANG.viewHits}: {OTHERCLIPSCONTENT.view}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: otherClipsContent -->
    </div>
    <!-- BEGIN: nv_generate_page -->
    <div class="text-center">{NV_GENERATE_PAGE}</div>
    <!-- END: nv_generate_page -->
</div>
<!-- END: main -->
