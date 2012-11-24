<!-- BEGIN: tree -->
<li><a title="{MENUTREE.note}" href="{MENUTREE.link}"{MENUTREE.target}>{MENUTREE.title}</a>	
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul><!-- END: tree_content -->                
</li><!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddsmoothmenu.css" />
<script type="text/javascript"	src="{NV_BASE_SITEURL}js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	arrowimages: {down:['downarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/down.gif', 23], right:['rightarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/right.gif']},
	mainmenuid: "smoothmenu_2",
	zIndex: 200,
	orientation: 'v',
	classname: 'ddsmoothmenu-v',
	contentsource: "markup"
});
</script>
<div class="ddsmoothmenu-v" id="smoothmenu_2">
	<ul>
		<!-- BEGIN: loopcat1 -->
		<li{CAT1.class}><a{CAT1.class} title="{CAT1.note}" href="{CAT1.link}"{CAT1.target}>{CAT1.title}</a>				
			<!-- BEGIN: cat2 -->
			<ul>			
				{HTML_CONTENT}	
			</ul><!-- END: cat2 -->	
		</li><!-- END: loopcat1 -->
	</ul>
</div>
<div class="clear"></div>
<!-- END: main -->
