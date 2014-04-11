<div id="choose-color-lang" class="clearfix">
	<div class="fr cl">
		<!-- BEGIN: color_select -->
		<div class="fl">
			<a href="#" rel="styles1" class="styleswitch red">&nbsp;</a>
			<a href="#" rel="styles2" class="styleswitch blue">&nbsp;</a>
		</div>
		<!-- END: color_select -->
		<!-- BEGIN: language -->
		<div class="fl" style="margin-top:3px;">
			<a href="#" rel="styles1" class="styleswitch red">&nbsp;</a>
			<a href="#" rel="styles2" class="styleswitch blue">&nbsp;</a>
		</div>
		<form class="select_lang" name="select_language" action="" method="get">
			<p>
				<select class="fl" name="language" onchange="location.href=select_language.language.options[selectedIndex].value">
					<!-- BEGIN: langitem -->
					<option value="{LANGSITEURL}"{SELECTED}>{LANGSITENAME}</option>
					<!-- END: langitem -->
				</select>
			</p>
		</form>
		<!-- END: language -->
	</div>
</div>
<div class="main">
	[FOOTER]
</div>
</div>
<div id="footer" class="clearfix">
	<div class="fl div2">
		[FOOTER_SITE]
		<div class="clear"></div>
		<!-- BEGIN: theme_type -->
		{LANG.theme_type_select}:
		<!-- BEGIN: loop -->
		<!-- BEGIN: other -->
		<a href="{STHEME_TYPE}" title="{STHEME_INFO}">{STHEME_TITLE}</a>
		<!-- END: other -->
		<!-- BEGIN: current -->
		{STHEME_TITLE}
		<!-- END: current -->
		<!-- BEGIN: space -->
		|
		<!-- END: space -->
		<!-- END: loop -->
		<!-- END: theme_type -->
	</div>
	<div class="fr div2 aright">
		<!-- BEGIN: bottom_menu -->
		<a title="{TOP_MENU.title}" href="{TOP_MENU.link}">{TOP_MENU.title}</a>
		<!-- BEGIN: spector -->
		<span class="spector">&nbsp;</span>
		<!-- END: spector -->
		<!-- END: bottom_menu -->
		<br />
		<a href="http://nukeviet.vn">NukeViet</a> is registered trademark of <a href="http://vinades.vn">VINADES.,JSC</a>
	</div>
	<div class="clear"></div>
</div>
</body>