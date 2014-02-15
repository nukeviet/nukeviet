<!-- BEGIN: main -->
<div id="toolbar">
	<ul class="info level{ADMIN_INFO.level} fl">
		<li>
			{GLANG.your_account}: <strong>{ADMIN_INFO.username}</strong>
		</li>
		<!-- BEGIN: memory_time_usage -->
		<li style="margin-left:10px" id="id_queries_count">
			[MEMORY_TIME_USAGE]
		</li>
		<!-- END: memory_time_usage -->
	</ul>
	<div class="action fr">
		<a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span class="icons icon-sitemanager">{GLANG.admin_page}</span></a>
		<!-- BEGIN: is_spadadmin -->
		<a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span class="icons icon-drag">{LANG_DBLOCK}</span></a>
		<!-- END: is_spadadmin -->
		<!-- BEGIN: is_modadmin -->
		<a href="{URL_MODULE}" title="{GLANG.admin_module_sector}"><span class="icons icon-module">{GLANG.admin_module_sector}</span></a>
		<!-- END: is_modadmin -->
		<a href="{URL_AUTHOR}" title="{GLANG.your_account}"><span class="icons icon-users">{GLANG.your_account}</span></a>
		<a href="javascript:void(0);" onclick="nv_admin_logout();" title="{GLANG.logout}"><span class="icons icon-logout">{GLANG.logout}</span></a>
	</div>
</div>
<!-- END: main -->