<!-- BEGIN: main -->
<div id="toolbar">
	<ul class="pull-right clearfix">
		<li><em class="fa fa-lg fa-cog">&nbsp;</em><a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}" title="{GLANG.admin_page}"><span>{GLANG.admin_page}</span></a></li>
		<!-- BEGIN: is_spadadmin -->
		<li><em class="fa fa-lg fa-arrows">&nbsp;</em><a href="{URL_DBLOCK}" title="{LANG_DBLOCK}"><span>{LANG_DBLOCK}</span></a></li>
		<!-- END: is_spadadmin -->
		<!-- BEGIN: is_modadmin -->
		<li><em class="fa fa-lg fa-key">&nbsp;</em><a href="{URL_MODULE}" title="{GLANG.admin_module_sector}"><span>{GLANG.admin_module_sector}</span></a></li>
		<!-- END: is_modadmin -->
		<li><em class="fa fa-lg fa-user">&nbsp;</em><a href="{URL_AUTHOR}" title="{GLANG.your_account}"><span>{GLANG.your_account}</span></a></li>
		<li><em class="fa fa-lg fa-sign-out">&nbsp;</em><a href="javascript:void(0);" onclick="nv_admin_logout();" title="{GLANG.logout}"><span>{GLANG.logout}</span></a></li>
	</ul>
	<ul class="pull-left clearfix">
		<li>
			<!-- BEGIN: lev1 -->
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<!-- END: lev1 -->
			<!-- BEGIN: lev2 -->
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<em class="fa fa-lg fa-user no-level">&nbsp;</em>
			<!-- END: lev2 -->
			<!-- BEGIN: lev3 -->
			<em class="fa fa-lg fa-user">&nbsp;</em>
			<em class="fa fa-lg fa-user no-level">&nbsp;</em>
			<em class="fa fa-lg fa-user no-level">&nbsp;</em>
			<!-- END: lev3 -->
			{GLANG.your_account}: <strong>{ADMIN_INFO.username}</strong>
		</li>
		<!-- BEGIN: memory_time_usage -->
		<li id="id_queries_count">
			<em class="fa fa-lg fa-cogs">&nbsp;</em> 
			[MEMORY_TIME_USAGE]
		</li>
		<!-- END: memory_time_usage -->
	</ul>
</div>
<!-- END: main -->