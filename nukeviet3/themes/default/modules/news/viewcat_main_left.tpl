<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
    <div class="news-content bordersilver white clearfix">
        <div class="header clearfix">
            <a title="{CAT.title}" class="current" href="{CAT.link}"><span><span>{CAT.title}</span></span></a>
            <!-- BEGIN: subcatloop -->
            	<a title="{SUBCAT.title}" href="{SUBCAT.link}">{SUBCAT.title}</a>
            <!-- END: subcatloop -->
            <!-- BEGIN: subcatmore -->
            	<a title="{MORE.title}" href="{MORE.link}">{MORE.title}</a>
            <!-- END: subcatmore -->
        </div>
        <div class="clear">
        </div>
		<!-- BEGIN: related -->
        <ul class="related fixedwidth">
            <!-- BEGIN: loop -->
            <li>
                <a title="{OTHER.title}" href="{OTHER.link}">{OTHER.title}</a>
            </li>
            <!-- END: loop -->
        </ul>
		<!-- END: related -->
        <div class="{WCT}{BORDER}items clearfix">
            <h3><a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a></h3>
            <!-- BEGIN: image -->
            	<a title="{CONTENT.title}" href="{CONTENT.link}"><img src="{HOMEIMG}" alt="{HOMEIMGALT}" width="{IMGWIDTH}" /></a>
            <!-- END: image -->
            <p>
                {CONTENT.hometext}
            </p>
        </div>
    </div>
</div>
<!-- END: listcat -->
<!-- END: main -->