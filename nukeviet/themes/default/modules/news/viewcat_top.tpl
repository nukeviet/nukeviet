<!-- BEGIN: main -->
<div class="news_column">
    <div class="news-content bordersilver white clearfix">
        <!-- BEGIN: catcontent -->
        <div class="items border_b clearfix">
            <!-- BEGIN: image -->
            	<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}" /></a>
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
        <!-- END: catcontent -->
        <ul class="related">
            <!-- BEGIN: catcontentloop -->
            <li>
                <a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
            </li>
            <!-- END: catcontentloop -->
        </ul>
    </div>
</div>
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
