<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}" {MENUTREE.target}>{MENUTREE.title}</a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/slimmenu.css" />
<script	type="text/javascript" src="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/js/jquery.slimmenu.js"></script>
<script src="{NV_BASE_SITEURL}js/ui/jquery.ui.effect.js"></script>
<ul class="slimmenu">
	<!-- BEGIN: loopcat1 -->
	<li>
		<a title="{CAT1.note}" href="{CAT1.link}" {CAT1.target}>{CAT1.title}</a>
		<!-- BEGIN: cat2 -->
			<ul>
				{HTML_CONTENT}
			</ul>
		<!-- END: cat2 -->
	</li>
	<!-- END: loopcat1 -->
</ul>
<script type="text/javascript">
$('ul.slimmenu').slimmenu(
{
	collapserTitle: 'Main Menu',
	easingEffect:'easeInOutQuint',
	animSpeed: 'medium',
	indentChildren: true,
	childrenIndenter: '&raquo;'
});
</script>
<!-- END: main -->