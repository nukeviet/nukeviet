<!-- BEGIN: mainblock -->
<div class="box silver">
    <h3 class="header"><strong>&bull;</strong>{BLOCK_TITLE}</h3>
    <!-- BEGIN: looprss -->
    <li class="clearfix" style="padding-bottom:10px;">
        <a {DATA_RSS.target} title="{DATA_RSS.title}" href="{DATA_RSS.link}"><b>{DATA_RSS.title}</b></a>
        <br>
        <!-- BEGIN: pubDate -->
	        <i>{DATA_RSS.pubDate}</i>
	        <br>
        <!-- END: pubDate -->
        <!-- BEGIN: description -->
        	{DATA_RSS.description}
		<!-- END: description -->        	
        </li>
    <!-- END: looprss -->
</div>
<!-- END: mainblock -->