<!-- BEGIN: main -->
	<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/smooth_navigational_menu.css" />
        <script src="{NV_BASE_SITEURL}js/ddsmoothmenu.js" type="text/javascript"></script>
		<script type="text/javascript">
			ddsmoothmenu.init({
				arrowimages: {down: ['downarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/mtop_down.gif', 23],right: ['rightarrowclass', '{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/ddsmoothmenu/mtop_right.gif']},
				mainmenuid: "smooth_navigational_menu", //Menu DIV id
				orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
				classname: 'smoothmenu', //class added to menu's outer DIV
				customtheme: ["#1c5a80", "#18374a"],
				contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
			});
		</script>        
<div id="smooth_navigational_menu" class="smoothmenu">
	<ul>
		<li class="home"><a title="{LANG.Home}" href="{THEME_SITE_HREF}"><span>{LANG.Home}</span></a></li>
		<!-- BEGIN: top_menu -->
		<li>
			<a title="{TOP_MENU.title}" href="{TOP_MENU.link}">{TOP_MENU.title}</a>
			{TOP_MENU.submenu}
		</li>
		<!-- END: top_menu -->
	</ul>
	<br style="clear: left" />
</div>      
<!-- END: main -->