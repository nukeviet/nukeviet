<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="panel panel-default panel-shops-cat">
    <div class="panel-heading clearfix">
        <a class="pull-left" href="{LINK_CATALOG}" title="{TITLE_CATALOG}">{TITLE_CATALOG} ({NUM_PRO} {LANG.title_products})</a>
        <span class="pull-right more-cats">
            <!-- BEGIN: subcatloop --><a href="{SUBCAT.link}" title="{SUBCAT.title}">{SUBCAT.title}</a><!-- END: subcatloop -->
        </span>
    </div>
    <div class="panel-body">
        {CONTENT}
    </div>
</div>
<!-- END: loop -->
<!-- END: main -->
