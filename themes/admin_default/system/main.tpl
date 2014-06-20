<!-- BEGIN: main -->
{FILE "header.tpl"}
<div class="container-fluid nvwrap">
	<div id="left-menu-bg"></div>
	<header id="header" class="row">
		<div class="logo">
			<a title="{NV_SITE_NAME}" href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}">
				<img class="logo-md" alt="{NV_SITE_NAME}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/logo_small.png" width="240" height="50"/>
				<img class="logo-xs" alt="{NV_SITE_NAME}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/logo-xs.png" width="50" height="50"/>
			</a>
		</div>
		<ul class="menu pull-right">
			<!-- BEGIN: langdata -->
			<li title="{NV_LANGDATA}">
				<a href="javascript:void(0);" data-toggle="dropdown">
					<span class="screen-lg">{NV_LANGDATA_CURRENT} <em class="fa fa-caret-down">&nbsp;</em></span>
					<span class="screen-xs"><em class="fa fa-magic fa-2x fix logout">&nbsp;</em><span>
				</a> 
				<ul class="dropdown-menu" role="menu">
					<!-- BEGIN: option -->
					<li{DISABLED}><a href="{LANGOP}">{LANGVALUE}</a></li>
					<!-- END: option -->
				</ul>
			</li>
			<!-- END: langdata -->
			<li class="tip" data-toggle="tooltip" data-placement="bottom" title="{NV_GO_CLIENTSECTOR}">
				<a href="{NV_GO_CLIENTSECTOR_URL}"> <em class="fa fa-home fa-2x fix">&nbsp;</em></a>
			</li>
			<li class="tip" data-toggle="tooltip" data-placement="bottom" title="{NV_LOGOUT}">
				<a href="javascript:void(0);" onclick="nv_admin_logout();"> <em class="fa fa-power-off fa-2x fix logout">&nbsp;</em></a>
			</li>
			<li class="tip admin-info" data-toggle="tooltip" data-placement="bottom" title="<!-- BEGIN: hello_admin -->{HELLO_ADMIN1}<!-- END: hello_admin --><!-- BEGIN: hello_admin3 -->{HELLO_ADMIN3}<!-- END: hello_admin3 --><!-- BEGIN: hello_admin2 -->{HELLO_ADMIN2}<!-- END: hello_admin2 -->">
				<a href="javascript:void(0);">
					<img src="{ADMIN_PHOTO}" alt="{ADMIN_USERNAME}" width="32" height="32"/>
				</a>
			</li>
		</ul>
	</header>
	<div class="row">
		<div class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-horizontal">
						<span class="sr-only">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
					</button>
					<button id="left-menu-toggle" type="button" class="navbar-toggle" data-target="#left-menu">
						<span class="sr-only">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
						<span class="icon-bar">&nbsp;</span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="menu-horizontal">
					<ul class="nav navbar-nav">
						<li class="hidden-md hidden-sm hidden-xs">
							<a title="{LANG.Home}" href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}"><em class="fa fa-lg fa-home">&nbsp;</em> {LANG.Home}</a>
						</li>
						<!-- BEGIN: top_menu_loop -->
						<li{TOP_MENU_CLASS}>
							<a href="{NV_BASE_SITEURL}{NV_ADMINDIR}/index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={TOP_MENU_HREF}">{TOP_MENU_NAME}<!-- BEGIN: has_sub --> <strong class="caret">&nbsp;</strong><!-- END: has_sub --></a>
							<!-- BEGIN: submenu -->
							<ul class="dropdown-menu">
								<!-- BEGIN: submenu_loop --><li><a href="{SUBMENULINK}" title="{SUBMENUTITLE}">{SUBMENUTITLE}</a></li><!-- END: submenu_loop -->
							</ul>
							<!-- END: submenu -->
						</li>
						<!-- END: top_menu_loop -->
					</ul>
					<p class="navbar-text navbar-right" id="digclock">{NV_DIGCLOCK}</p>
				</div>
			</div>
		</div>
	</div>
	<section id="middle" class="row">
		<aside id="left-menu">
			<ul class="nav nav-pills nav-stacked">
				<!-- BEGIN: menu_loop -->
				<li{MENU_CLASS}>
					<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_HREF}">{MENU_NAME}</a>
					<!-- BEGIN: submenu -->
					<ul class="dropdown-menu">
						<!-- BEGIN: submenu_loop -->
						<li>
							<a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={MENU_SUB_OP}">{MENU_SUB_NAME}</a>
						</li>
						<!-- END: submenu_loop -->
					</ul>
					<!-- END: submenu -->
					<span class="arrow">&nbsp;</span>
				</li>
				<!-- BEGIN: current -->
				<li>
					<a class="{MENU_SUB_CURRENT}" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MENU_SUB_HREF}&amp;{NV_OP_VARIABLE}={MENU_SUB_OP}">{MENU_SUB_NAME}</a>
				</li>
				<!-- END: current -->
				<!-- END: menu_loop -->
			</ul>
			<div class="clearfix"></div>
		</aside>
		<div id="container" class="clearfix">
			<div id="info_tab" class="clearfix">
				<!-- BEGIN: breadcrumbs -->
				<ol class="breadcrumb">
					<!-- BEGIN: loop -->
					<li<!-- BEGIN: active --> class="active"<!-- END: active -->><!-- BEGIN: text -->{BREADCRUMBS.title}<!-- END: text --><!-- BEGIN: linked --><a href="{BREADCRUMBS.link}">{BREADCRUMBS.title}</a><!-- END: linked --></li>
					<!-- END: loop -->
				</ol>
				<!-- END: breadcrumbs -->
				<!-- BEGIN: select_option -->
				<div class="pull-right btn-group">
					<button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
						{PLEASE_SELECT} <span class="caret">&nbsp;</span>
					</button>
					<ul class="dropdown-menu">
						<!-- BEGIN: select_option_loop -->
						<li><a href="{SELECT_VALUE}">{SELECT_NAME}</a></li>
						<!-- END: select_option_loop -->
					</ul>
				</div>
				<!-- END: select_option -->
				<!-- BEGIN: site_mods -->
				<span class="pull-right"><a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}"><em class="fa fa-globe fa-lg">&nbsp;</em>{NV_GO_CLIENTMOD}</a></span>
				<!-- END: site_mods -->
			</div>
			<div id="contentmod">
				{THEME_ERROR_INFO}
				{MODULE_CONTENT}
			</div>
		</div>
	</section>
	<footer id="footer" class="row">
		<div class="footer-content">
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
		</div>
	</footer>
</div>
<div id="timeoutsess" class="chromeframe">
	{LANG_TIMEOUTSESS_NOUSER}, <a onclick="timeoutsesscancel();" href="#">{LANG_TIMEOUTSESS_CLICK}</a>. {LANG_TIMEOUTSESS_TIMEOUT}: <span id="secField"> 60 </span> {LANG_TIMEOUTSESS_SEC}
</div>
{FILE "footer.tpl"}
<!-- END: main -->