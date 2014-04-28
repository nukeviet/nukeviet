	<body>
		<noscript>
			<div id="nojavascript">
				{THEME_NOJS}
			</div>
		</noscript>
		<div class="wrapper">
			<div class="topbar">
				<div class="top">
					<ul class="module-menu fl">
						<!-- BEGIN: top_menu -->
						<li>
							<a {TOP_MENU.current} title="{TOP_MENU.title}" href="{TOP_MENU.link}">{TOP_MENU.title}</a>
						</li>
						<!-- END: top_menu -->
					</ul>
					<a title="View RSS" href="{THEME_RSS_INDEX_HREF}" class="rss fr">Rss Feed</a>
					<form action="{NV_BASE_SITEURL}" method="get" onsubmit="return {THEME_SEARCH_SUBMIT_ONCLICK}">
						<div class="q-search fr">
							<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
							<input type="hidden" name="{NV_NAME_VARIABLE}" value="seek" />
							<input type="text" class="txt-qs" name="q" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" /><input type="submit" class="submit-qs" value="" name="submit" id="topmenu_search_submit"/>
						</div>
					</form>
					<div class="clear"></div>
				</div>
			</div>
			<div class="header">
				<div class="logo">
					<a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}">
						<!-- BEGIN: show_logo -->
						<img src="{LOGO_SRC}" alt="{THEME_LOGO_TITLE}" height="84" />
						<!-- END: show_logo -->
						<!-- BEGIN: show_site_name -->
						<strong>{THEME_LOGO_TITLE}</strong>
						<!-- END: show_site_name -->
					</a>
				</div>
				<div class="topadv">
					[TOPADV]
				</div>
				<div class="clear"></div>
			</div>
			[MENU_SITE]
			<!-- BEGIN: mod_title -->
			<div class="main breadcrumbs">
                <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a title="{LANG.Home}" href="{NV_BASE_SITEURL}" itemprop="url"><span itemprop="title" class="home">{LANG.Home}</span></a>
                </div>
                <!-- BEGIN: breakcolumn -->
                <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                › <a class="highlight" href="{BREAKCOLUMN.link}" title="{BREAKCOLUMN.title}" itemprop="url"><span itemprop="title">{BREAKCOLUMN.title}</span></a>
                </div>
                <!-- END: breakcolumn -->
                <div class="clear"></div>
			</div>
			<!-- END: mod_title -->
			<div class="main">
				[BELOW_NAV]
			</div>
			[THEME_ERROR_INFO]