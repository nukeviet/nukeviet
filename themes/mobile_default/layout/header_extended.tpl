	<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
	<header>
		<div id="header">
			<div class="row">
				<div class="col-xs-5">
					<div id="logo">
						 <a title="{SITE_NAME}" href="{THEME_SITE_HREF}">
						 	<!-- BEGIN: image -->
						 	<img src="{LOGO_SRC}" alt="{SITE_NAME}" />
						 	<!-- END: image -->
							<!-- BEGIN: swf -->
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" >
								<param name="wmode" value="transparent" />
								<param name="movie" value="{LOGO_SRC}" />
								<param name="quality" value="high" />
								<param name="menu" value="false" />
								<param name="seamlesstabbing" value="false" />
								<param name="allowscriptaccess" value="samedomain" />
								<param name="loop" value="true" />
								<!--[if !IE]> <-->
								<object type="application/x-shockwave-flash" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" data="{LOGO_SRC}" >
									<param name="wmode" value="transparent" />
									<param name="pluginurl" value="http://www.adobe.com/go/getflashplayer" />
									<param name="loop" value="true" />
									<param name="quality" value="high" />
									<param name="menu" value="false" />
									<param name="seamlesstabbing" value="false" />
									<param name="allowscriptaccess" value="samedomain" />
								</object>
								<!--> <![endif]-->
							</object>
							<!-- END: swf -->

							<!-- BEGIN: main_h1 -->
							<h1 class="hidden">{SITE_NAME}</h1>
							<!-- END: main_h1 -->
							<!-- BEGIN: main_none_h1 -->
							<span class="hidden">{SITE_NAME}</span>
							<!-- END: main_none_h1 -->
						</a>
					</div>
				</div>
				<div class="col-xs-19 text-right">
					<ul class="list-inline list-control">
						<li id="btn-search"><em class="fa fa-search fa-lg fa-pointer">&nbsp;</em></li>
						<li id="btn-bars"><em class="fa fa-bars fa-lg fa-pointer">&nbsp;</em></li>
					</ul>
				</div>
			</div>
		</div>
	</header>
	<nav id="nav" <!-- BEGIN: no_drag_block -->style="display: none"<!-- END: no_drag_block -->>
		[MENU_SITE]
	</nav>
	<div id="search" class="container-fluid" style="display: none">
		<form action="{NV_BASE_SITEURL}" method="get" onsubmit="return {THEME_SEARCH_SUBMIT_ONCLICK}">
			<div class="input-group">
				<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
				<input type="hidden" name="{NV_NAME_VARIABLE}" value="seek" />
				<input type="text" class="form-control" name="q" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" placeholder="{LANG.search}..."/>
				<span class="input-group-btn">
					<button class="btn btn-info" type="submit" id="topmenu_search_submit"><em class="fa fa-lg fa-search">&nbsp;</em></button>
				</span>
			</div>
		</form>
	</div>
	<section>
		<div id="body">
		    <div class="row">
    			<!-- BEGIN: breadcrumbs -->
    			<ol class="breadcrumb">
    				<li class="home" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><em class="fa fa-lg fa-home">&nbsp;</em><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></li>
    				<!-- BEGIN: loop --><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span itemprop="title">{BREADCRUMBS.title}</span></a></li><!-- END: loop -->
    			</ol>
			</div>
			<!-- END: breadcrumbs -->
			[THEME_ERROR_INFO]