<!-- BEGIN: main -->
<!-- BEGIN: listcat -->
<div class="news_column">
    <div class="news-content bordersilver white clearfix">
        <div class="header clearfix">
            <a class="current" href="{CAT.link}"><span><span>{CAT.title}</span></span></a>
            <!-- BEGIN: subcatloop --><a href="{SUBCAT.link}">{SUBCAT.title}</a>
            <!-- END: subcatloop -->
        </div>
        <div class="items {BORDER}clearfix">
            <!-- BEGIN: image --><a href="{CONTENT.link}"><img src="{HOMEIMG}" width="{IMGWIDTH}" /></a><!-- END: image -->
            <h3><a href="{CONTENT.link}">{CONTENT.title}</a></h3>
            <p>{CONTENT.hometext}</p>
            <!-- BEGIN: adminlink --><p>{ADMINLINK}</p><!-- END: adminlink -->
        </div>
        <!-- BEGIN: related -->
        <ul class="related">
			<!-- BEGIN: loop -->
			<li><a href="{OTHER.link}">{OTHER.title}</a></li><!-- END: loop -->
        </ul><!-- END: related -->
    </div>
</div><!-- END: listcat --><!-- END: main -->
