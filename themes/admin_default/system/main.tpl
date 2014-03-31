<!-- BEGIN: main -->
{FILE "header.tpl"}
<div id="wrapper">
	<header id="header">
		<div class="logo">
			<a title="{NV_SITE_NAME}" href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}"><img alt="{NV_SITE_NAME}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/logo_small.png" width="240" height="50" /></a>
		</div>
		<div class="logout">
			<a class="bthome" href="{NV_GO_CLIENTSECTOR_URL}"><span><em class="icon-home icon-large">&nbsp;</em> {NV_GO_CLIENTSECTOR}</span></a>
			<a class="bthome" href="javascript:void(0);" onclick="nv_admin_logout();"><span><em class="icon-power-off icon-large">&nbsp;</em> {NV_LOGOUT}</span></a>
		</div>
		<!-- BEGIN: langdata -->
		<div class="lang">
			<strong>{NV_LANGDATA}</strong>:
			<select id="lang" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
				<!-- BEGIN: option -->
				<option value="{LANGOP}"{SELECTED}>{LANGVALUE} </option>
				<!-- END: option -->
			</select>
		</div>
		<!-- END: langdata -->
	</header>
	<!-- #header-->

	<nav id="smoothmenu" class="ddsmoothmenu clearfix">
		<ul>
			<!-- BEGIN: top_menu_loop -->
			<li>
				<a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={TOP_MENU_HREF}">{TOP_MENU_NAME}</a>
				<!-- BEGIN: submenu -->
				<ul>
					<!-- BEGIN: submenu_loop -->
					<li>
						<a href="{SUBMENULINK}">{SUBMENUTITLE}</a>
					</li>
					<!-- END: submenu_loop -->
				</ul>
				<!-- END: submenu -->
			</li>
			<!-- END: top_menu_loop -->
		</ul>
	</nav>

	<div id="top_message">
		<div class="clock_container">
			<div class="clock">
				<label> <span id="digclock">{NV_DIGCLOCK}</span> </label>
			</div>
		</div>
		<div class="info">
			<!-- BEGIN: hello_admin -->
			{HELLO_ADMIN1}
			<!-- END: hello_admin -->
			<!-- BEGIN: hello_admin3 -->
			{HELLO_ADMIN3}
			<!-- END: hello_admin3 -->
			<!-- BEGIN: hello_admin2 -->
			{HELLO_ADMIN2}
			<!-- END: hello_admin2 -->
		</div>
		<div class="clearfix"></div>
	</div>

	<section id="middle">
		<div id="contentwrapper">
			<div id="container">
				<div id="info_tab">
					<span id="cs_menu" onclick="ver_menu_click()"><em class="icon-circle-arrow-left icon-large">&nbsp;</em></span>
					<!-- BEGIN: empty_page_title -->
					<span class="cell_left">{PAGE_TITLE}</span>
					<!-- END: empty_page_title -->

					<!-- BEGIN: select_option -->
					<span class="cell_right">
						<select name="select_options" onchange="top.location.href=this.options[this.selectedIndex].value;return;">
							<option value="">{PLEASE_SELECT}</option>
							<!-- BEGIN: select_option_loop -->
							<option value="{SELECT_VALUE}">{SELECT_NAME}</option>
							<!-- END: select_option_loop -->
						</select> </span>
					<!-- END: select_option -->
					<!-- BEGIN: site_mods -->
					<span class="cell_right"> <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}">{NV_GO_CLIENTMOD}</a> </span>
					<!-- END: site_mods -->
					<div class="clearfix"></div>
				</div>
				<div id="contentmod">
					{THEME_ERROR_INFO}
					{MODULE_CONTENT}
					<div class="clearfix">
						&nbsp;
					</div>
				</div>
				<!-- #content-->
			</div>
			<!-- #container-->

			<aside id="left_menu" class="ddsmoothmenu-v">
				<ul id="ver_menu">
					<!-- BEGIN: menu_loop -->
					<li>
						<a {MENU_CURRENT} href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_HREF}">{MENU_NAME}</a>
						<!-- BEGIN: submenu -->
						<ul>
							<!-- BEGIN: submenu_loop -->
							<li>
								<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={MENU_SUB_OP}">{MENU_SUB_NAME}</a>
							</li>
							<!-- END: submenu_loop -->
						</ul>
						<!-- END: submenu -->
					</li>
					<!-- BEGIN: current -->
					<li>
						<a class="{MENU_SUB_CURRENT}" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={MENU_SUB_OP}">{MENU_SUB_NAME}</a>
					</li>
					<!-- END: current -->
					<!-- END: menu_loop -->
				</ul>
			</aside>
			<!-- #left_menu -->
		</div>
		<!-- #contentwrapper-->
	</section>
	<!-- #middle-->

	<footer id="footer">
		<div class="copyright">
			<!-- BEGIN: memory_time_usage -->
			[MEMORY_TIME_USAGE]
			<br/>
			<!-- END: memory_time_usage -->
			<strong>{NV_COPYRIGHT}</strong>
		</div>
		<div class="imgstat">
			<a title="NUKEVIET CMS" href="http://nukeviet.vn" target="_blank"><img alt="NUKEVIET CMS" src="{NV_BASE_SITEURL}images/banner_nukeviet_88x15.jpg" width="88" height="15" /></a>
			<br/>
		</div>
	</footer>
	<!-- #footer -->
</div>
<!-- #wrapper -->
<div id="timeoutsess" class="chromeframe">
	{LANG_TIMEOUTSESS_NOUSER}, <a onclick="timeoutsesscancel();" href="#">{LANG_TIMEOUTSESS_CLICK}</a>. {LANG_TIMEOUTSESS_TIMEOUT}: <span id="secField"> 60 </span> {LANG_TIMEOUTSESS_SEC}
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ddsmoothmenu.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/main.js"></script>
<!-- BEGIN: ckeditor -->
<script type="text/javascript">
	for (var i in CKEDITOR.instances) {
		CKEDITOR.instances[i].on('key', function(e) {
			$(window).bind('beforeunload', function() {
				return '{MSGBEFOREUNLOAD}';
			});
		});
	}
</script>
<!-- END: ckeditor -->
{FILE "footer.tpl"}
<!-- END: main -->