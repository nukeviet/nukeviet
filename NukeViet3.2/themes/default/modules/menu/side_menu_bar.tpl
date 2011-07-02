<!-- BEGIN: tree -->
	<li class="{MENUTREE.class1}"><a href="{MENUTREE.link}">{MENUTREE.title}</a>	
		<!-- BEGIN: tree_content -->
			<ul>
			{TREE_CONTENT}
			</ul>
		<!-- END: tree_content -->                
	</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/ddsmoothmenu.css" />
<script type="text/javascript"	src="{NV_BASE_SITEURL}js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	arrowimages: {down:['downarrowclass', '{NV_BASE_SITEURL}themes/{TEMPLATE}/images/ddsmoothmenu/down.gif', 23], right:['rightarrowclass', '{NV_BASE_SITEURL}themes/{TEMPLATE}/images/ddsmoothmenu/right.gif']},
	mainmenuid: "smoothmenu_2", //Menu DIV id
	zIndex: 200,
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
});
</script>
<div class="ddsmoothmenu-v" id="smoothmenu_2">
	<ul>
		<!-- BEGIN: loopcat1 -->
		<li{CAT1.current}><a href="{CAT1.link}">{CAT1.title}</a>				
			<!-- BEGIN: cat2 -->
			<ul>			
				{HTML_CONTENT}	
			</ul>		
			<!-- END: cat2 -->	
		</li>
		<!-- END: loopcat1 -->
	</ul>
</div>
<div class="clear"></div>
<!-- END: main -->
