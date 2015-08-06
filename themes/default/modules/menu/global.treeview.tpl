<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}"{MENUTREE.target}>{MENUTREE.title}</a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.css" type="text/css"/>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js" type="text/javascript"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#navigation{MENUID}").treeview({
			collapsed : true,
			unique : true,
			persist : "location"
		});
	});
</script>
<style type="text/css">
	#navigation{MENUID} a {
		background-color: transparent !important
	}

	#navigation .current, #navigation .current a {
		font-weight: bold
	}

	#navigation .current ul a {
		font-weight: normal
	}
</style>
<ul id="navigation{MENUID}">
	<!-- BEGIN: loopcat1 -->
	<li {CAT1.class}>
		<a title="{CAT1.note}" href="{CAT1.link}"{CAT1.target}>{CAT1.title}</a>
		<!-- BEGIN: cat2 -->
		<ul>
			{HTML_CONTENT}
		</ul>
		<!-- END: cat2 -->
	</li>
	<!-- END: loopcat1 -->
</ul>
<div class="clear"></div>
<!-- END: main -->