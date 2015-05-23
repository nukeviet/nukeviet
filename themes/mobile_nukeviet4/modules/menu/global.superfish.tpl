<!-- BEGIN: tree -->
<li>
	<a title="{MENUTREE.note}" href="{MENUTREE.link}" class="sf-with-ul"{MENUTREE.target}><strong>{MENUTREE.title}</strong></a>
	<!-- BEGIN: tree_content -->
	<ul>
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-navbar.css" />
<link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{BLOCK_THEME}/css/superfish-vertical.css" />
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/hoverIntent.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/superfish.js"></script>
<script	type="text/javascript" src="{NV_BASE_SITEURL}js/superfish/supersubs.js"></script>
<style type="text/css">
	.style_nav {
		position: relative;
		background: url("{NV_BASE_SITEURL}themes/{BLOCK_THEME}/images/menu/nen.png") repeat-x;
		height: 60px;
		line-height: 32px;
		z-index: 990
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$("ul.sf-menu").superfish({
			pathClass : 'current'
		});
	}); 
</script>
<div class="style_nav">
	<ul id="sample-menu-4" class="sf-menu sf-navbar sf-js-enabled sf-shadow">
		<!-- BEGIN: loopcat1 -->
		<li {CAT1.class}>
			<a title="{CAT1.note}" class="sf-with-ul" href="{CAT1.link}"{CAT1.target}><strong>{CAT1.title}</strong></a>
			<!-- BEGIN: cat2 -->
			<ul>
				{HTML_CONTENT}
			</ul>
			<!-- END: cat2 -->
		</li>
		<!-- END: loopcat1 -->
	</ul>
</div>
<div class="clear"></div>
<!-- END: main -->