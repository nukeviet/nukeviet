<!-- BEGIN: main -->
<script type="text/javascript">
	var imageslist	= {down:['downarrowclass', '{THEME_TEM}/images/promenu/down.gif', 23], right:['rightarrowclass', '{THEME_TEM}/images/promenu/right.gif']};
</script>
<script type="text/javascript"	src="{THEME_TEM}/js/promenu.js"></script>
<script type="text/javascript">
	ddsmoothmenu.init({
		mainmenuid: "smoothmenu2", //Menu DIV id
		orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
		classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
		//customtheme: ["#804000", "#482400"],
		contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
	})
</script>
<link rel="stylesheet" type="text/css"	href="{THEME_TEM}/css/promenu.css" />
<div id="smoothmenu2" class="ddsmoothmenu-v">
  <ul>
      {CONTENT}
  </ul>
  <br style="clear: left"/>
</div>
<!-- END: main -->