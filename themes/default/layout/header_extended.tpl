	<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
	<div id="wraper">
		<header>
			<div class="container">
				<div class="row" id="header-wraper">
					<div id="header">
						<div id="logo">
							<a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" alt="{SITE_NAME}" /><span>{SITE_NAME}</span></a>
						</div>
						<div class="pull-right">
							<div id="social-icons">
								[SOCIAL_ICONS]
							</div>
							<div id="search">
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
						</div>
					</div>
					<div id="banner">
						<img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/banner.jpg" alt="{SITE_NAME}"/>
					</div>
				</div>
			</div>
		</header>
		<nav>
			<div class="container">
				<div class="row">
					[MENU_SITE]
				</div>
			</div>
		</nav>
		<section>
			<div class="container" id="body">
				<!-- BEGIN: breadcrumbs -->
				<ol class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
					<li class="home"><em class="fa fa-lg fa-home">&nbsp;</em><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></li>
					<!-- BEGIN: loop --><li><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span itemprop="title">{BREADCRUMBS.title}</span></a></li><!-- END: loop -->
				</ol>
				<!-- END: breadcrumbs -->
				[THEME_ERROR_INFO]
