<!-- BEGIN: main -->
<script type="text/javascript" data-show="after">
	var imageslist = {
		down : ['downarrowclass', '{THEME_TEM}/images/promenu/down.gif', 23],
		right : ['rightarrowclass', '{THEME_TEM}/images/promenu/right.gif']
	};
</script>
<script type="text/javascript" data-show="after" src="{THEME_TEM}/js/promenu.js"></script>
<script type="text/javascript" data-show="after">
	ddsmoothmenu.init({
		mainmenuid : "smoothmenu2",
		orientation : 'v',
		classname : 'ddsmoothmenu-v',
		contentsource : "markup"
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