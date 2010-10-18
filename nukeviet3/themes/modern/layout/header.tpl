<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {THEME_PAGE_TITLE}
        {THEME_META_TAGS}
		<meta name="robots" content="index, archive, follow, noodp" />
        <meta name="googlebot" content="index,archive,follow,noodp" />
        <meta name="msnbot" content="all,index,follow" />
		<link rel="icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/screen.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/red.css" media="screen" title="styles1"/>
        <link rel="alternate stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/blue.css" media="screen" title="styles2"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/superfish.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/superfish-navbar.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/icons.css" />
        {THEME_CSS}
        {THEME_SITE_RSS}
        {THEME_SITE_JS}
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/hoverIntent.js">
        </script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/superfish.js">
        </script>
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/styleswitch.js">
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("ul.sf-menu").superfish({
                    pathClass: 'current',
                    autoArrows: false,
                    speed: 'fast'
                });
            });
        </script>
        <!--[if IE 6]>
            <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/ie.css" />
            <script src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
            <script>
				DD_belatedPNG.fix('#');
            </script>
        <![endif]-->
    </head>
    <body>
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
                    <h1><a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}">NUKEVIET <span class="highlight"><strong>NEWS</strong></span> DEMO</a></h1>
                </div>
                <div class="topadv">
                    [TOPADV]
                </div>
                <div class="clear">
                </div>
            </div>
            <div class="nav">
                <!-- BEGIN: news_cat -->
                <ul class="sf-menu sf-navbar">
                    <!-- BEGIN: mainloop -->
                    <li {mainloop.current}>
                        <a title="{mainloop.title}" href="{mainloop.link}">{mainloop.title}</a>
                        <!-- BEGIN: sub -->
                        <ul>
                            <!-- BEGIN: loop -->
                            <li {loop.current}>
                                <a title="{loop.title}" href="{loop.link}">{loop.title}</a>
                            </li>
                            <!-- END: loop --><!-- BEGIN: null -->
                            <li>
                                &nbsp;
                            </li>
                            <!-- END: null -->
                        </ul>
                        <!-- END: sub -->
                    </li>
                    <!-- END: mainloop -->
                </ul>
                <!-- END: news_cat --><span id="digclock" class="small update fr">{THEME_DIGCLOCK_TEXT} </span>
                <div class="clear">
                </div>
            </div>
            <div class="main">
                [BELOW_NAV]
            </div>
            {THEME_ERROR_INFO}