<!-- BEGIN: main -->
<div class="news_column">
    <!-- BEGIN: catcontent --><!-- BEGIN: content -->
    <div class="news-content bordersilver white clearfix">
        <div class="items">
            <!-- BEGIN: image -->
            	<a href="{NEWSTOP.link}" title="{NEWSTOP.title}"><img alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}"/></a>
           	<!-- END: image -->
			<h3><a href="{NEWSTOP.link}" title="{NEWSTOP.title}">{NEWSTOP.title}</a></h3>
            <p>
                {NEWSTOP.hometext}
            </p>
            <!-- BEGIN: adminlink -->
            <p style="text-align : right;">
                {ADMINLINK}
            </p>
            <!-- END: adminlink -->
        </div>
        <!-- END: content -->
        <ul class="related">
            <!-- BEGIN: other -->
            <li>
                <a title="{NEWSTOP.title}" href="{NEWSTOP.link}">{NEWSTOP.title}</a>
            </li>
            <!-- END: other -->
        </ul>
    </div>
    <!-- END: catcontent -->
</div>

<!-- BEGIN: loopcat -->
<div class="news_column two_column{LAST} fl">
    <div class="news-content bordersilver white clearfix">
        <div class="header clearfix">
            <a class="current" title="{CAT.title}" href="{CAT.link}"><span><span>{CAT.title}</span></span></a>
        </div>
        <div class="items{BORDER}">
            <!-- BEGIN: content -->
            	<h3><a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a></h3>
                <!-- BEGIN: image -->
                	<a href="{CONTENT.link}" title="{CONTENT.title}"><img alt="{HOMEIMGALT01}" src="{HOMEIMG01}" width="{IMGWIDTH01}" /></a>
                <!-- END: image -->
                <p>
                    {CONTENT.hometext}
                </p>
                <!-- BEGIN: adminlink -->
                <p style="text-align : right;">
                    {ADMINLINK}
                </p>
                <!-- END: adminlink -->
            <!-- END: content -->
            <ul class="related">
                <!-- BEGIN: other -->
                <li>
                    <a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
                </li>
                <!-- END: other -->
            </ul>
        </div>
     </div>
</div>
<!-- END: loopcat -->
<div style="clear:both;">
</div>
<!-- END: main -->
