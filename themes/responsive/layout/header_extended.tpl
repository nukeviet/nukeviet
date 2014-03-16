<body>
	<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
	[THEME_ERROR_INFO]
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
										<input type="text" class="form-control" name="topmenu_search_query" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}">
										<span class="input-group-btn">
											<button class="btn btn-info" type="submit"><i class="fa fa-lg fa-search">&nbsp;</i></button>
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
				<ol class="breadcrumb">
					<li class="home"><i class="fa fa-lg fa-home">&nbsp;</i><a href="{THEME_SITE_HREF}">{LANG.Home}</a></li>
					<!-- BEGIN: loop --><li><a href="{BREADCRUMBS.link}">{BREADCRUMBS.title}</a></li><!-- END: loop -->
				</ol>
				<!-- END: breadcrumbs -->
				<div class="row">