<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}" class="sf-with-ul"{MENUTREE.target}><strong>{MENUTREE.title_trim}</strong></a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css"	href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/jquery.metisMenu.css" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.metisMenu.js"></script>

<div class="clearfix">
	<aside class="sidebar">
		<nav class="sidebar-nav">
			<ul id="menu_{MENUID}">
				<!-- BEGIN: loopcat1 -->
					<li>
						<a title="{CAT1.note}" href="{CAT1.link}"{CAT1.target}>{CAT1.title_trim}</a>
						<!-- BEGIN: expand -->
						<span class="arrow" id="expand">+</span>
						<!-- END: expand -->

						<!-- BEGIN: cat2 -->
						<ul>
							{HTML_CONTENT}
						</ul>
						<!-- END: cat2 -->
					</li>
				<!-- END: loopcat1 -->
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