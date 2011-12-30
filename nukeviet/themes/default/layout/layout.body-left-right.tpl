<!-- BEGIN: main -->
{FILE "header.tpl"}
<div class="contentwrapper">
	[HEADER]
    <div class="span-13 contentcolumn">
         <!-- BEGIN: mod_title -->
	    <h3 class="breakcolumn">
	    	<a title="{LANG.Home}" href="{NV_BASE_SITEURL}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/icons/home.png" alt="{LANG.Home}" /></a>
	    	<!-- BEGIN: breakcolumn -->
				<span class="breakcolumn">&raquo;</span>	    	
	    		<a href="{BREAKCOLUMN.link}" title="{BREAKCOLUMN.title}">{BREAKCOLUMN.title}</a>
	    	<!-- END: breakcolumn -->
	    </h3>
         <!-- END: mod_title -->    
        [TOP]
        {MODULE_CONTENT}
        [BOTTOM]
    </div>
    <div class="span-5">
        [LEFT]
    </div>
    <div class="span-6 last">
        [RIGHT]
    </div>
	<div class="clear"></div>
	[FOOTER]
</div>{FILE "footer.tpl"} 
<!-- END: main -->