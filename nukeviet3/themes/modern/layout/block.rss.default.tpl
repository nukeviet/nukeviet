<!-- BEGIN: mainblock -->
<div class="box-border m-bottom">
	<div class="header-block1">
		<h3><span>{BLOCK_TITLE}</span></h3>
	</div>
	<div class="content-box" >
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
</div>
<!--  END: mainblock -->