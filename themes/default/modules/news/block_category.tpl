<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}js/jquery/jquery.metisMenu.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.metisMenu.js"></script>

<div class="clearfix">
	<aside class="sidebar">
		<nav class="sidebar-nav">
			<ul id="menu_{MENUID}">
				{HTML_CONTENT}
			</ul>
		</nav>
	</aside>
</div>

<script>
$(function () {
	$('#menu_{MENUID}').metisMenu({
        toggle: false
    });
});
</script>
<!-- END: main -->