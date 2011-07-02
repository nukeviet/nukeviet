<!-- BEGIN: tree -->
	<li class="{MENUTREE.class1}"><a href="{MENUTREE.link}" class="sf-with-ul"><strong>{MENUTREE.title}</strong></a>	
		<!-- BEGIN: tree_content -->
			<ul>
				{TREE_CONTENT}
			</ul>
       <!-- END: tree_content -->                
	</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-navbar.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-vertical.css" />
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/hoverIntent.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/superfish.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/supersubs.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){ 
    $("ul.sf-menu").superfish({ 
		animation: {height:'show'},   
		delay:     1200               
	}); 
});  
</script>
<ul class="sf-menu sf-vertical sf-js-enabled sf-shadow">
	<!-- BEGIN: loopcat1 -->
	<li{mainloop.current}><a href="{CAT1.link}" class="sf-with-ul"><strong>{CAT1.title}</strong></a>						
		<!-- BEGIN: cat2 -->
		<ul>			
			{HTML_CONTENT}	
		</ul>		
		<!-- END: cat2 -->
	</li>
	<!-- END: loopcat1 -->
</ul>
<div class="clear"></div>
<!-- END: main -->
