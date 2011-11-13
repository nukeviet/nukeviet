<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		{THEME_PAGE_TITLE} {THEME_META_TAGS}
		<link rel="icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
		<link rel="shortcut icon" href="{NV_BASE_SITEURL}favicon.ico" type="image/vnd.microsoft.icon" />
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/template.css" />
		{THEME_CSS}
		{THEME_SITE_RSS}
		{THEME_SITE_JS}
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div id="logo">
					<a title="{THEME_LOGO_TITLE}" href="{THEME_SITE_HREF}"><img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/logo.gif" alt="{THEME_LOGO_TITLE}" /></a>
				</div>
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
                        $(function()
                        {
                            $("select[name=lang]").change(function()
                            {
                                var reurl = $("select[name=lang]").val();
                                document.location = reurl;
                            });
                        });

					</script>
				</div>
				<!-- END: language -->
			</div>
			[MENU_SITE]
