<!-- BEGIN: main -->
<div class="nav1 ac">
	<select class="half" onchange="window.location=this.value;">
		<option value="{THEME_SITE_HREF}">{LANG.Home}</option>
		<!-- BEGIN: top_menu -->
		<option value="{TOP_MENU.link}">{TOP_MENU.title}</option>
		<!-- BEGIN: sub -->
		<!-- BEGIN: item -->
		<option value="{SUB.link}">&nbsp;&nbsp;&nbsp;&nbsp;{SUB.title}</option>
		<!-- END: item -->
		<!-- END: sub -->
		<!-- END: top_menu -->
	</select>
</div>
<!-- END: main -->