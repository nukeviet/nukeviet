<!DOCTYPE html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		{THEME_PAGE_TITLE} {THEME_META_TAGS}
        <!-- BEGIN: viewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- END: viewport -->
		{THEME_SITE_RSS}

		<link rel="shortcut icon" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/favicon.ico" />
		<link rel="apple-touch-icon" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/apple-touch-icon.png" />

        <!-- BEGIN: responsive -->
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.css" rel="stylesheet" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.responsive.css" rel="stylesheet" />
        <!-- END: responsive -->

        <!-- BEGIN: non_responsive -->
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap.non-responsive.css" rel="stylesheet" />
        <link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.css" rel="stylesheet" />
        <!-- END: non_responsive -->

		<link href="{NV_BASE_SITEURL}themes/default/css/font-awesome.min.css" rel="stylesheet" />
		{THEME_CSS}
		{THEME_SITE_JS}
		<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/main.js"></script>
		<!-- BEGIN: lt_ie9 -->
		<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/html5shiv.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/respond.min.js"></script>
		<!-- END: lt_ie9 -->
	</head>
	<body>
		<div id="timeoutsess" class="chromeframe">
		{LANG_TIMEOUTSESS_NOUSER}, <a onclick="timeoutsesscancel();" href="#">{LANG_TIMEOUTSESS_CLICK}</a>. {LANG_TIMEOUTSESS_TIMEOUT}: <span id="secField"> 60 </span> {LANG_TIMEOUTSESS_SEC}
		</div>
