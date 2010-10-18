<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {THEME_PAGE_TITLE} {THEME_META_TAGS}
        <meta name="robots" content="index, archive, follow, noodp" />
        <meta name="googlebot" content="index,archive,follow,noodp" />
        <meta name="msnbot" content="all,index,follow" />
        <link rel="icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/template.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/icons.css" />
        {THEME_CSS}
        {THEME_SITE_RSS}
        {THEME_SITE_JS}
        <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.dropdownPlain.js">
        </script>
        <!--[if IE 6]>
            <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/ie6.css" />
            <script type="text/javascript" src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
            <script type="text/javascript">
                DD_belatedPNG.fix('#');
            </script>
        <![endif]-->
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div id="logo">
                    <a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/logo.gif" alt="{THEME_LOGO_TITLE}" /></a>
                </div><!-- BEGIN: language -->
                <div class="language">
                	Language:
                    <select name="lang">
                        <!-- BEGIN: langitem --><option value="{LANGSITEURL}" title="{SELECTLANGSITE}">{LANGSITENAME}</option>
                        <!-- END: langitem --><!-- BEGIN: langcuritem --><option value="{LANGSITEURL}" title="{SELECTLANGSITE}" selected="selected">{LANGSITENAME}</option>
                        <!-- END: langcuritem -->
                    </select>
                    <script type="text/javascript">
                        $(function(){
                            $("select[name=lang]").change(function(){
                                var reurl = $("select[name=lang]").val();
                                document.location = reurl;
                            });
                        });
                    </script>
                </div>
                <!-- END: language -->
            </div>
            <div class="nav">
                <ul class="nav fl">
                    <li class="home">
                        <a title="{LANG.Home}" href="{THEME_SITE_HREF}"><span>{LANG.Home}</span></a>
                    </li>
                    <!-- BEGIN: top_menu -->
                    <li {TOP_MENU.current}>
                        <a title="{TOP_MENU.title}" href="{TOP_MENU.link}"><span><strong>&bull;</strong>{TOP_MENU.title} </span></a>
                        <!-- BEGIN: sub -->
                        <ul class="subnav">
                            <!-- BEGIN: item -->
                            <li>
                                <a title="{SUB.title}" href="{SUB.link}">&raquo; {SUB.title}</a>
                            </li>
                            <!-- END: item -->
                        </ul>
                        <!-- END: sub -->
                    </li>
                    <!-- END: top_menu -->
                </ul>
                <div class="rss fr">
                    <a title="RSS" href="{THEME_RSS_INDEX_HREF}">&nbsp;</a>
                </div>
            </div>
            <div id="message">
                <div class="clock fl">
                    <span id="digclock" style="font-weight: 700;">{THEME_DIGCLOCK_TEXT}</span>
                </div>
                <form action="{NV_BASE_SITEURL}" method="get" class="search fr" onsubmit="return {THEME_SEARCH_SUBMIT_ONCLICK}">
                    <fieldset>
                    	<input type="hidden" id="topmenu_search_checkss" value="{CHECKSS}" />
                        <input class="txt" type="text" name="topmenu_search_query" id="topmenu_search_query" maxlength="{THEME_SEARCH_QUERY_MAX_LENGTH}" />
                        <input class="submit" type="button" value="Go" name="topmenu_search_submit" id="topmenu_search_submit" onclick="{THEME_SEARCH_SUBMIT_ONCLICK}"/>
                    </fieldset>
                </form>
            </div>
            {THEME_ERROR_INFO}