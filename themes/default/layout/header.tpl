<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        {THEME_PAGE_TITLE} {THEME_META_TAGS}
        <link rel="icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/template.css" />
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/icons.css" />
        {THEME_CSS}
        {THEME_SITE_RSS}
        {THEME_SITE_JS}
    </head>
    <body>
    <noscript>
        <div id="nojavascript">{THEME_NOJS}</div>
    </noscript>
        <div id="container">
            <div id="header">
                <div id="logo">
                    <a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" alt="{THEME_LOGO_TITLE}" /></a>
                </div>
                <span class="sitename">
                	{SITE_NAME}
                </span>
                <!-- BEGIN: language -->
	                <div class="language">
	                	Language:
	                    <select name="lang">
	                        <!-- BEGIN: langitem -->
	                        	<option value="{LANGSITEURL}" title="{SELECTLANGSITE}">{LANGSITENAME}</option>
	                        <!-- END: langitem -->
	                        <!-- BEGIN: langcuritem -->
	                        	<option value="{LANGSITEURL}" title="{SELECTLANGSITE}" selected="selected">{LANGSITENAME}</option>
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
			[MENU_SITE]
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
            [THEME_ERROR_INFO]