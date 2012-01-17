<!-- BEGIN: main -->
    <!-- BEGIN: topic -->
    <div class="news_column">
        <div class="items clearfix">
            <!-- BEGIN: homethumb -->
            	<a href="{TOPIC.link}" title="{TOPIC.title}"><img alt="{TOPIC.alt}" src="{TOPIC.src}" width="{TOPIC.width}" /></a>
            <!-- END: homethumb -->
            <h3><a href="{TOPIC.link}" title="{TOPIC.title}">{TOPIC.title}</a></h3>
            <p>
                <span class="time">{TIME}</span> | <span class="date">{DATE}</span>
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
    <!-- END: topic -->
    <!-- BEGIN: other -->
        <ul class="related">
            <!-- BEGIN: loop -->
            <li>
                <a title="{TOPIC_OTHER.title}" href="{TOPIC_OTHER.link}">{TOPIC_OTHER.title}</a>
                <span class="date">({TOPIC_OTHER.publtime})</span>
            </li>
            <!-- END: loop -->
        </ul>
    <!-- END: other -->
    
	<!-- BEGIN: generate_page -->
	<div class="generate_page">
		{GENERATE_PAGE}
	</div>
	<!-- END: generate_page -->    
<!-- END: main -->