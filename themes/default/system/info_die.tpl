<!-- BEGIN: main -->
<!DOCTYPE html>
<html lang="{LANG.Content_Language}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={SITE_CHARSET}" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{PAGE_TITLE}</title>
		<link rel="shortcut icon" href="{SITE_FAVICON}" />
		<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/bootstrap.min.css" rel="stylesheet" />
		<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/css/font-awesome.min.css" rel="stylesheet" />
		<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.css" rel="stylesheet" />
		<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/style.responsive.css" rel="stylesheet" />
        <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/{LANG.Content_Language}.js"></script>
        <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/global.js?t=1"></script>
        <script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/main.js"></script>
	</head>
	<body>
		<div class="nv-infodie text-center">
			<div class="panel-body">
				<!-- BEGIN: image -->
				<p><a href="{HOME_LINK}"><img class="logo" src="{LOGO}" width="{WIDTH}" height="{HEIGHT}" alt="{SITE_NAME}" /></a></p>
				<!-- END: image -->
				<!-- BEGIN: swf -->
				<div class="m-bottom">
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="{WIDTH}" height="{HEIGHT}" >
						<param name="wmode" value="transparent" />
						<param name="movie" value="{LOGO}" />
						<param name="quality" value="high" />
						<param name="menu" value="false" />
						<param name="seamlesstabbing" value="false" />
						<param name="allowscriptaccess" value="samedomain" />
						<param name="loop" value="true" />
						<!--[if !IE]> <-->
						<object type="application/x-shockwave-flash" width="{WIDTH}" height="{HEIGHT}" data="{LOGO}" >
							<param name="wmode" value="transparent" />
							<param name="pluginurl" value="http://www.adobe.com/go/getflashplayer" />
							<param name="loop" value="true" />
							<param name="quality" value="high" />
							<param name="menu" value="false" />
							<param name="seamlesstabbing" value="false" />
							<param name="allowscriptaccess" value="samedomain" />
						</object>
						<!--> <![endif]-->
					</object>
				</div>
				<!-- END: swf -->
				<div class="m-bottom">
					<h2>{INFO_TITLE}</h2>
					{INFO_CONTENT}
				</div>
				<div>
					<hr />
					<!-- BEGIN: adminlink -->
					<em class="fa fa-gears fa-lg">&nbsp;</em> <a href="{ADMIN_LINK}">{GO_ADMINPAGE}</a>
					<span class="defis">&nbsp;</span>
					<!-- END: adminlink -->
					<!-- BEGIN: sitelink -->
					<em class="fa fa-home fa-lg">&nbsp;</em> <a href="{SITE_LINK}">{GO_SITEPAGE}</a>
					<!-- END: sitelink -->
				</div>
			</div>
		</div>
	</body>
</html>
<!-- END: main -->
