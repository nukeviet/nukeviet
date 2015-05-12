<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.metisMenu.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.metisMenu.js"></script>

<div class="clearfix" style="padding: 0px 0px 10px 0px">
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