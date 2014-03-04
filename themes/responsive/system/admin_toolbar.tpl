<!-- BEGIN: main -->
<div id="toolbar">
	<ul class="pull-right clearfix">
		<li><i class="fa fa-lg fa-cog">&nbsp;</i><a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span class="icons icon-sitemanager">{GLANG.admin_page}</span></a></li>
		<!-- BEGIN: is_spadadmin -->
		<li><i class="fa fa-lg fa-arrows">&nbsp;</i><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span class="icons icon-drag">{LANG_DBLOCK}</span></a></li>
		<!-- END: is_spadadmin -->
		<!-- BEGIN: is_modadmin -->
		<li><i class="fa fa-lg fa-key">&nbsp;</i><a href="{URL_MODULE}" title="{GLANG.admin_module_sector}"><span class="icons icon-module">{GLANG.admin_module_sector}</span></a></li>
		<!-- END: is_modadmin -->
		<li><i class="fa fa-lg fa-user">&nbsp;</i><a href="{URL_AUTHOR}" title="{GLANG.your_account}"><span class="icons icon-users">{GLANG.your_account}</span></a></li>
		<li><i class="fa fa-lg fa-sign-out">&nbsp;</i><a href="javascript:void(0);" onclick="nv_admin_logout();" title="{GLANG.logout}"><span class="icons icon-logout">{GLANG.logout}</span></a></li>
	</ul>
	<ul class="pull-left clearfix">
		<li>
			<!-- BEGIN: lev1 -->
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<!-- END: lev1 -->
			<!-- BEGIN: lev2 -->
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<i class="fa fa-lg fa-user no-level">&nbsp;</i>
			<!-- END: lev2 -->
			<!-- BEGIN: lev3 -->
			<i class="fa fa-lg fa-user">&nbsp;</i>
			<i class="fa fa-lg fa-user no-level">&nbsp;</i>
			<i class="fa fa-lg fa-user no-level">&nbsp;</i>
			<!-- END: lev3 -->
			{GLANG.your_account}: <strong>{ADMIN_INFO.username}</strong>
		</li>
		<!-- BEGIN: memory_time_usage -->
		<li id="id_queries_count">
			<i class="fa fa-lg fa-cogs">&nbsp;</i> 
			[MEMORY_TIME_USAGE]
		</li>
		<!-- END: memory_time_usage -->
	</ul>
</div>
<!-- END: main -->