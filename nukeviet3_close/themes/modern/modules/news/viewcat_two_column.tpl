<!-- BEGIN: main -->
<!-- BEGIN: catcontent -->
<div class="box-border-shadow m-bottom t-news">
    <!-- BEGIN: content -->
    <div class="content-box clearfix">
        <h4><a href="{NEWSTOP.link}" title="{NEWSTOP.title}">{NEWSTOP.title}</a></h4>
        <!-- BEGIN: image --><a href="{NEWSTOP.link}" title="{NEWSTOP.title}"><img class="s-border fl left" alt="{HOMEIMGALT0}" src="{HOMEIMG0}" width="{IMGWIDTH0}"/></a><!-- END: image -->
        <p>
            {NEWSTOP.hometext}
        </p>
        <!-- BEGIN: adminlink -->
        <p style="text-align : right;">
            {ADMINLINK}
        </p>
        <!-- END: adminlink -->
        <div class="aright">
            <a title="{LANG.more}" class="more" href="{NEWSTOP.link}">{LANG.more}</a>
        </div>
    </div>
    <!-- END: content -->
    <div class="other-news">
        <ul>
            <!-- BEGIN: other -->
            <li>
                <a title="{NEWSTOP.title}" href="{NEWSTOP.link}">{NEWSTOP.title}</a>
                <span class="small">{NEWSTOP.publtime}</span>
            </li>
            <!-- END: other -->
        </ul>
    </div>
</div>
<!-- END: catcontent --><!-- BEGIN: loopcat -->
<div id="catid-{ID}" class="m-bottom box50{FLOAT}">
    <div class="box-border-shadow">
        <div class="cat-nav">
            <a class="rss" href="{CAT.rss}">RSS</a>
            <a class="current-cat" title="{CAT.title}" href="{CAT.link}">{CAT.title}</a>
        </div>
        <div class="content-box">
            <!-- BEGIN: content -->
            <div class="m-bottom">
                <h4><a href="{CONTENT.link}" title="{CONTENT.title}">{CONTENT.title}</a></h4>
                <p class="small">
                    {LANG.pubtime}: {CONTENT.publtime} - {LANG.view}: {CONTENT.hitstotal} - {LANG.total_comment}: {CONTENT.hitscm}
                </p>
            </div>
            <!-- BEGIN: image --><a href="{CONTENT.link}" title="{CONTENT.title}"><img class="s-border fl left" alt="{HOMEIMGALT01}" src="{HOMEIMG01}" width="{IMGWIDTH01}"/></a><!-- END: image -->
            <p>
                {CONTENT.hometext}
            </p>
            <div class="aright">
                <a title="{LANG.more}" class="more" href="{CONTENT.link}">{LANG.more}</a>
            </div>
            <!-- BEGIN: adminlink -->
            <p style="text-align : right;">
                {ADMINLINK}
            </p>
            <!-- END: adminlink --><!-- END: content -->
            <ul class="other-news">
                <!-- BEGIN: other -->
                <li>
                    <a title="{CONTENT.title}" href="{CONTENT.link}">{CONTENT.title}</a>
                </li>
                <!-- END: other -->
            </ul>
        </div>
    </div>
</div>
<!-- BEGIN: clear -->
<div style="clear:both;">
</div>
<!-- END: clear -->
<!-- END: loopcat -->
<!-- END: main -->
