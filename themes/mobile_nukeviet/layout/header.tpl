<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		{THEME_PAGE_TITLE} {THEME_META_TAGS}
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
		<meta name="MobileOptimized" content="100" />
		<link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/template.css" />
		{THEME_CSS}
		{THEME_SITE_RSS}
		{THEME_SITE_JS}
	</head>
	<body>
		<div id="header">
			<a href="{THEME_SITE_HREF}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/logo.png" height="45"/></a>
			<!-- BEGIN: language -->
			<div class="language">
				<select onchange="document.location=this.value">
					<!-- BEGIN: langitem -->
					<option value="{LANGSITEURL}">{LANGSITENAME}</option>
					<!-- END: langitem -->
					<!-- BEGIN: langcuritem -->
					<option value="{LANGSITEURL}" selected="selected">{LANGSITENAME}</option>
					<!-- END: langcuritem -->
				</select>
				<!-- END: language -->
			</div>
		</div>
		[MENU_SITE]
		<form class="gsebg" action="{NV_BASE_SITEURL}index.php" method="get">
			<input type="hidden" name="{NV_NAME_VARIABLE}" value="seek"/>
			<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
			<input type="text" name="q"/>
			<input type="submit" class="smbt" value="{LANG.search}"/>
		</form>
		<!-- BEGIN: mod_title -->
		<h3 class="brcl"><a href="{NV_BASE_SITEURL}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/icons/home.png"/></a>
		<!-- BEGIN: breakcolumn -->
		<a class="it" href="{BREAKCOLUMN.link}">{BREAKCOLUMN.title}</a>
		<!-- END: breakcolumn -->
		</h3>
		<!-- END: mod_title -->
		<div class="clear"></div>