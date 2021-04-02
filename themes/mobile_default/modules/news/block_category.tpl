<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.metisMenu.css" />
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery/jquery.metisMenu.js"></script>

<div class="clearfix panel">
	<aside class="sidebar">
		<nav class="sidebar-nav">
			<ul id="menu_{MENUID}">
				{HTML_CONTENT}
			</ul>
		</nav>
	</aside>
</div>

<script type="text/javascript">
$(function () {
	$('#menu_{MENUID}').metisMenu({
        toggle: false
    });
});
</script>
<!-- END: main -->