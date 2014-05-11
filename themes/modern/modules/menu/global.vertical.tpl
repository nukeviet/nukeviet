<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}" class="sf-with-ul"{MENUTREE.target}><strong>{MENUTREE.title}</strong></a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/ddsmoothmenu-v.css" />
<script type="text/javascript"	src="{NV_BASE_SITEURL}js/ddsmoothmenu.js"></script>
<script type="text/javascript">
	ddsmoothmenu.init({
		arrowimages : {
			down : ['downarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/down.gif', 23],
			right : ['rightarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/right.gif']
		},
		mainmenuid : "smoothmenu_{MENUID}", //Menu DIV id
		zIndex : 200,
		orientation : 'v', //Horizontal or vertical menu: Set to "h" or "v"
		classname : 'ddsmoothmenu-v', //class added to menu's outer DIV
		contentsource : "markup" //"markup" or ["container_id", "path_to_menu_file"]
	})
</script>

<div id="smoothmenu_{MENUID}" class="ddsmoothmenu">
	<ul>
		<!-- BEGIN: loopcat1 -->
		<li {CAT1.class}>
			<a title="{CAT1.note}" href="{CAT1.link}" class="sf-with-ul"{CAT1.target}><strong>{CAT1.title}</strong></a>
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