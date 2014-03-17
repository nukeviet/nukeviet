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
							<input type="text" class="txt-qs" name="topmenu_search_query" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" /><input type="submit" class="submit-qs" value="" name="topmenu_search_submit" id="topmenu_search_submit"/>
						</div>
					</form>
					<div class="clear"></div>
				</div>
			</div>
			<div class="header">
				<div class="logo">
					<a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" alt="{THEME_LOGO_TITLE}" height="84" /> <strong>{THEME_LOGO_TITLE}</strong></a>
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