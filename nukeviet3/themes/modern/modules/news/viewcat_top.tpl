<!-- BEGIN: main --><!-- BEGIN: breakcolumn --><h3><!-- BEGIN: loop --><a href="{BREAKCOLUMN.link}" title="{BREAKCOLUMN.title}">{BREAKCOLUMN.title}</a><!-- BEGIN: arrow --><span>&raquo;</span><!-- END: arrow --><!-- END: loop --></h3>
<!-- END: breakcolumn -->
<div class="box-border-shadow m-bottom t-news">
    <div class="content-box clearfix">
        <!-- BEGIN: catcontent --><!-- BEGIN: image --><a href="{CONTENT.link}" title="{CONTENT.title}"><img class="s-border fl left" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}" height="{IMGHEIGHT0}"/></a><!-- END: image --><h4><a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a></h4>
        <p>
            {CONTENT.hometext}
        </p>
        <!-- BEGIN: adminlink -->
        <p style="text-align : right;">
            {ADMINLINK}
        </p>
        <!-- END: adminlink -->
        <div class="aright">
            <a title="{LANG.more}" class="more" href="{CONTENT.link}">{LANG.more}</a>
        </div>
        <!-- END: catcontent -->
    </div>
    <div class="other-news">
        <ul>
            <!-- BEGIN: catcontentloop -->
            <li>
                <a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
                <span class="small">{CONTENT.publtime}</span>
            </li>
            <!-- END: catcontentloop -->
        </ul>
    </div>
</div>
<!-- END: main -->
