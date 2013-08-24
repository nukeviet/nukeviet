<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={SITE_CHERSET}" />
		<meta http-equiv="expires" content="0" />
		<title>{PAGE_TITLE}</title>
		<!--[if IE 6]>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
		<script type="text/javascript">
		DD_belatedPNG.fix('img');
		</script>
		<![endif]-->
	</head>

	<body>
		<div style="width: 500px; margin-right: auto; margin-left: auto; margin-top: 30px;margin-bottom: 20px;text-align: center;">
			<!-- BEGIN: image -->
			<a href="{HOME_LINK}"><img border="0" src="{LOGO}" width="{WIDTH}" height="{HEIGHT}" alt="" /></a>
			<!-- END: image -->
			<!-- BEGIN: swf -->
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
			<!-- END: swf -->
		</div>
		<div style="width: 500px; margin-right: auto; margin-left: auto; margin-bottom: 20px;color: #dd3e31; font-weight: bold; text-align: center;">
			{INFO_TITLE}
			<br />
			<span style="color: #1a264e;">{INFO_CONTENT}</span>
		</div>
		<div style="width: 500px; margin-right: auto; margin-left: auto;text-align:center">
			<!-- BEGIN: adminlink -->
			<a href="{ADMIN_LINK}"><font color="#616161">{GO_ADMINPAGE}</font></a>&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;&nbsp;
			<!-- END: adminlink -->
			<!-- BEGIN: sitelink -->
			<a href="{SITE_LINK}"><font color="#616161">{GO_SITEPAGE}</font></a>
			<!-- END: sitelink -->
		</div>
	</body>
</html>
<!-- END: main -->