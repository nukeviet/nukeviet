<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
    <div class="news-content bordersilver white clearfix">
        <div class="header clearfix">
            <a class="current" href="{CAT.link}" title="{CAT.title}"><span><span>{CAT.title}</span></span></a>
            <!-- BEGIN: subcatloop -->
            	<a href="{SUBCAT.link}" title="{SUBCAT.title}">{SUBCAT.title}</a>
            <!-- END: subcatloop -->
        </div>
        <div class="items {BORDER}clearfix">
            <!-- BEGIN: image -->
            	<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT}" src="{HOMEIMG}" width="{IMGWIDTH}" /></a>
            <!-- END: image -->
            <h3><a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a></h3>
            <p>
                {CONTENT.hometext}
            </p>
            <!-- BEGIN: adminlink -->
            <p style="text-align : right;">
                {ADMINLINK}
            </p>
            <!-- END: adminlink -->
        </div>
        <!-- BEGIN: related -->
        <ul class="related">
            <!-- BEGIN: loop -->
            <li>
                <a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
            </li>
            <!-- END: loop -->
        </ul>
        <!-- END: related -->
    </div>
</div>
<!-- END: listcat -->
<!-- END: main -->
