<!-- BEGIN: tree -->
<li>
	<!-- BEGIN: icon -->
	<img src="{MENUTREE.icon}" alt="{MENUTREE.note}" />&nbsp;
	<!-- END: icon -->
	<a title="{MENUTREE.note}" href="{MENUTREE.link}" {MENUTREE.target}>{MENUTREE.title_trim}</a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/slimmenu.css" />

<ul class="slimmenu">
	<li>
		<a title="{LANG.Home}" href="{THEME_SITE_HREF}"><em class="fa fa-lg fa-home">&nbsp;</em> <span class="hidden-sm"> {LANG.Home} </span></a>
	</li>
	<!-- BEGIN: loopcat1 -->
	<li {CAT1.class}>
		<!-- BEGIN: icon -->
        <img src="{CAT1.icon}" alt="{CAT1.note}" />&nbsp;
        <!-- END: icon -->
		<a title="{CAT1.note}" href="{CAT1.link}" {CAT1.target}>{CAT1.title_trim}</a>
		<!-- BEGIN: cat2 -->
			<ul>
				{HTML_CONTENT}
			</ul>
		<!-- END: cat2 -->
	</li>
	<!-- END: loopcat1 -->
</ul>
<script	type="text/javascript" src="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/js/jquery.slimmenu.js"></script>
<script src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">
	$('ul.slimmenu').slimmenu({
	    resizeWidth: (theme_responsive == '1') ? 768 : 0,
		collapserTitle: '',
		easingEffect:'easeInOutQuint',
		animSpeed: 'medium',
		indentChildren: true,
		childrenIndenter: '&nbsp;&nbsp; '
	});
</script>
<!-- END: main -->