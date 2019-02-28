<!-- BEGIN: main -->
<div class="viewgrid">
    <div class="row">
        <!-- BEGIN: otherClipsContent -->
        <div class="col-xs-24 col-sm-8 col-md-8">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <a href="{OTHERCLIPSCONTENT.href}" title="{OTHERCLIPSCONTENT.title}"><img id="imghome" alt="{OTHERCLIPSCONTENT.title}" src="{OTHERCLIPSCONTENT.img}" class="img-thumbnail imghome" /></a>
                    <h2>
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
                </div>
            </div>
        </div>
        <!-- END: otherClipsContent -->
    </div>
    <!-- BEGIN: nv_generate_page -->
    <div class="text-center">{GENERATE_PAGE}</div>
    <!-- END: nv_generate_page -->
</div>
<!-- END: main -->