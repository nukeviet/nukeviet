<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {THEME_PAGE_TITLE}
        {THEME_META_TAGS}
		<link rel="icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/screen.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/red.css" media="screen" title="styles1"/>
        <link rel="alternate stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/blue.css" media="screen" title="styles2"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/icons.css" />
        {THEME_CSS}
        {THEME_SITE_RSS}
        {THEME_SITE_JS}
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/styleswitch.js"></script>
    </head>
    <body>
    <noscript>
        <div id="nojavascript">{THEME_NOJS}</div>
    </noscript>
        <div class="wrapper">
            <div class="topbar">
                <div class="top">
                    <ul class="module-menu fl">
                        <!-- BEGIN: top_menu -->
                        <li>
                        <a{TOP_MENU.current} title="{TOP_MENU.title}" href="{TOP_MENU.link}">{TOP_MENU.title}</a>
                    </li>
                    <!-- END: top_menu -->
                    </ul>
                    <a title="View RSS" href="{THEME_RSS_INDEX_HREF}" class="rss fr">Rss Feed</a>
                    <form action="{NV_BASE_SITEURL}" method="get" onsubmit="return {THEME_SEARCH_SUBMIT_ONCLICK}">
                        <div class="q-search fr">
                            <input type="text" class="txt-qs" name="topmenu_search_query" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" /><input type="hidden" id="topmenu_search_checkss" value="{CHECKSS}" /><input type="submit" class="submit-qs" value="" name="topmenu_search_submit" id="topmenu_search_submit"/>
                        </div>
                    </form>
                    <div class="clear">
                    </div>
                </div>
            </div>
            <div class="header">
                <div class="logo">
					<h1><a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" alt="{THEME_LOGO_TITLE}" height="84" /> {THEME_LOGO_TITLE}</a></h1>
                </div>
                <div class="topadv">
                    [TOPADV]
                </div>
                <div class="clear">
                </div>
            </div>
            [MENU_SITE]
 			<!-- BEGIN: mod_title -->
			<div class="main">
				<div class="breadcrumbs">
					<a title="{LANG.Home}" href="{NV_BASE_SITEURL}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/icons/home.png" alt="{LANG.Home}" /></a>
					<!-- BEGIN: breakcolumn -->
						<span class="spector">&nbsp;</span>
						<a class="highlight" href="{BREAKCOLUMN.link}" title="{BREAKCOLUMN.title}">{BREAKCOLUMN.title}</a>
					<!-- END: breakcolumn -->
				</div>
			</div>
			<!-- END: mod_title -->
            <div class="main">
                [BELOW_NAV]
            </div>
            [THEME_ERROR_INFO]