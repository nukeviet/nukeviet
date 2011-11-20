<!-- BEGIN: main -->
<div class="nvshowquery"></div>
<div id="toolbar">
	<ul class="info level{ADMIN_INFO.level} fl">
		<li>
			{GLANG.your_account}: <strong>{ADMIN_INFO.username}</strong>
		</li>
		<!-- BEGIN: is_spadadmin2 -->
		<li style="margin-left:10px" id="id_queries_count">
			{GLANG.db_num_queries}: {COUNT_SHOW_QUERIES}'. <a href="javascript:void(0);" onclick="nv_show_queries();">{GLANG.show_queries}</a>
		</li>
		<!-- END: is_spadadmin2 -->
	</ul>
	<div class="action fr">
		<a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php" title="{GLANG.admin_page}"><span class="icons icon-sitemanager">{GLANG.admin_page}</span></a>
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
<!-- BEGIN: is_spadadmin3 -->
<div id="div_queries" style="visibility: hidden; display: none;">
	<div style="height: 400px; background: #ffffff; overflow: auto;padding: 5px;">
		<div class="queries">
			<!-- BEGIN: queries -->
			<div class="clearfix {DATA.class}">
				<p>
					<span class="first"><img alt="{DATA.imgalt}" title="{DATA.imgalt}" src="{DATA.imgsrc}" height="16" width="16"></span>
					<span class="second">{DATA.queries}</span>
				</p>
			</div>
			<!-- END: queries -->
		</div>
	</div>
</div>
<script type="text/javascript">
function nv_show_queries(){
	Shadowbox.open({
		content : $("div#div_queries").html(),
		player : 'html',
		height : 400,
		width : 960
	});
}
</script>
<!-- END: is_spadadmin3 -->
<!-- END: main -->