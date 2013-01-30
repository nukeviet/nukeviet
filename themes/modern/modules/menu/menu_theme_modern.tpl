<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-navbar.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/hoverIntent.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/superfish.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("ul.sf-menu").superfish({
			pathClass : 'current',
			autoArrows : false,
			speed : 'fast'
		});
	}); 
</script>
<div class="nav">
	<!-- BEGIN: news_cat -->
	<ul class="sf-menu sf-navbar">
		<!-- BEGIN: mainloop -->
		<li {mainloop.current}>
			<a title="{mainloop.title}" href="{mainloop.link}">{mainloop.title}</a>
			<!-- BEGIN: sub -->
			<ul>
				<!-- BEGIN: loop -->
				<li {loop.current}>
					<a title="{loop.title}" href="{loop.link}">{loop.title}</a>
				</li>
				<!-- END: loop -->
				<!-- BEGIN: null -->
				<li>
					&nbsp;
				</li>
				<!-- END: null -->
			</ul>
			<!-- END: sub -->
		</li>
		<!-- END: mainloop -->
	</ul>
	<!-- END: news_cat -->
	<span id="digclock" class="small update fr">{THEME_DIGCLOCK_TEXT} </span>
	<div class="clear"></div>
</div>
<!-- END: main -->