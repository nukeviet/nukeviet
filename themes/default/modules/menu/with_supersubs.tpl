<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}"{MENUTREE.target} class="sf-with-ul" >{MENUTREE.title}</a>	
	<!-- BEGIN: tree_content --><ul>
		{TREE_CONTENT}
	</ul><!-- END: tree_content -->                
</li><!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-navbar.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-vertical.css" />
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/hoverIntent.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/superfish.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/supersubs.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){ 
	$("#with_supersubs").supersubs({ 
		minWidth:    12,   
		maxWidth:    27,   
		extraWidth:  1                                    
	}).superfish();                
}); 
</script>
<style type="text/css">
div.navs{position:relative;background:#bdd2ff;height:32px;line-height:32px;z-index:990}
</style>
<div class="navs">
	<ul class="sf-menu" id="with_supersubs">
		<!-- BEGIN: loopcat1 --><li{CAT1.class}>
			<a title="{CAT1.note}" href="{CAT1.link}" class="sf-with-ul"{CAT1.target}>{CAT1.title}</a>
			<!-- BEGIN: cat2 --><ul>			
				{HTML_CONTENT}	
			</ul><!-- END: cat2 -->
		</li><!-- END: loopcat1 -->
	</ul>
</div>
<div class="clear"></div><!-- END: main -->