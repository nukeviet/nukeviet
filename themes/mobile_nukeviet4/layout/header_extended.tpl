	<!--[if lt IE 7]>
	<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<noscript>
		<div class="alert alert-danger">{LANG.nojs}</div>
	</noscript>
	<header>
		<div class="container-fluid">
			<div class="row">
				[MENU_SITE]
			</div>
		</div>
	</header>
	<div id="search" class="hidden">
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
		<div class="container" id="body">
		    <div class="row">
    			<!-- BEGIN: breadcrumbs -->
    			<ol class="breadcrumb">
    				<li class="home" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><em class="fa fa-lg fa-home">&nbsp;</em><a href="{THEME_SITE_HREF}" itemprop="url" title="{LANG.Home}"><span itemprop="title">{LANG.Home}</span></a></li>
    				<!-- BEGIN: loop --><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{BREADCRUMBS.link}" itemprop="url" title="{BREADCRUMBS.title}"><span itemprop="title">{BREADCRUMBS.title}</span></a></li><!-- END: loop -->
    			</ol>
			</div>
			<!-- END: breadcrumbs -->
			[THEME_ERROR_INFO]