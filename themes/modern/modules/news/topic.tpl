<!-- BEGIN: main -->
<div class="cat-nav" style="border:1px solid #ccc;padding:4px;">
    <a href="{TOPPIC_LINK}" class="current-cat" title="{TOPPIC_TITLE}">{TOPPIC_TITLE}</a> 
</div>
<!-- BEGIN: topicdescription -->
<div style="border:1px solid #ccc;border-top:none;padding:4px;">
    {TOPPIC_DESCRIPTION}
</div>
<!-- END: topicdescription -->
<div class="clear">&nbsp;</div>
<!-- BEGIN: topic -->
<div class="box-border-shadow m-bottom listz-news">
    <div class="content-box">
        <!-- BEGIN: homethumb --><a href="{TOPIC.link}" title="{TOPIC.title}"><img class="s-border fl left" alt="{TOPIC.alt}" src="{TOPIC.src}" width="{TOPIC.width}"/></a><!-- END: homethumb --><h4><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h4>
        <p>
            <span class="time">{LANG.pubtime}: {TIME} - {DATE}</span>
        </p>
        <p>
            {TOPIC.hometext}
        </p>
        <!-- BEGIN: adminlink -->
        <p style="text-align : right;">
            {ADMINLINK}
        </p>
        <!-- END: adminlink -->
    </div>
</div>
<!-- END: topic --><!-- BEGIN: other -->
<div class="other-news">
    <ul>
        <!-- BEGIN: loop -->
        <li>
            <a title="{TOPIC_OTHER.title}" href="{TOPIC_OTHER.link}">{TOPIC_OTHER.title}</a>
            <span class="date">({TOPIC_OTHER.publtime})</span>
        </li>
        <!-- END: loop -->
    </ul>
</div>
<!-- END: other -->
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
