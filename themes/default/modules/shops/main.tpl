<!-- BEGIN: main -->
<!-- BEGIN: viewall -->
<div id="viewall">{CONTENT}</div>
<!-- END: viewall -->
<!-- BEGIN: viewcat -->
<div id="viewcat">
    <!-- BEGIN: catalogs -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <a class="pull-left" href="{LINK_CATALOG}" title="{TITLE_CATALOG}">{TITLE_CATALOG} ({NUM_PRO} {LANG.title_products})</a> <span class="pull-right"> <!-- BEGIN: subcatloop --> <a href="{SUBCAT.link}" title="{SUBCAT.title}">{SUBCAT.title}</a>&nbsp;&nbsp;&nbsp; <!-- END: subcatloop -->
            </span>
            <div class="clear"></div>
        </div>
        <div class="panel-body">{CONTENT}</div>
    </div>
    <!-- END: catalogs -->
</div>
<!-- END: viewcat -->
<!-- END: main -->