<!-- BEGIN: main -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={CHARSET}" />
		<meta http-equiv="expires" content="0" />
		<meta name="resource-type" content="document" />
		<meta name="distribution" content="global" />
		<meta name="copyright" content="Copyright (c) {SITE_NAME}" />
		<meta name="robots" content="noindex, nofollow" />
		<title>{SITE_NAME} {NV_TITLEBAR_DEFIS} {PAGE_TITLE}</title>
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/bootstrap.min.css">
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/font-awesome.min.css">
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/main.css">
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/login.css" />
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{SITELANG}.js"></script>
		<script type="text/javascript">
			function nv_checkadminlogin_submit() {
				var password = document.getElementById('password');
				if (password.value == '') {
					return false;
				}
				return true;
			}

			function nv_admin_logout() {
				if (confirm(nv_admlogout_confirm[0])) {
					window.location.href = '{NV_BASE_SITEURL}index.php?second=admin_logout&ok=1';
				}
				return false;
			}
		</script>
		<!--[if IE 6]>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
		<script type="text/javascript">
		DD_belatedPNG.fix('.submitform, img');
		</script>
		<![endif]-->
	</head>
	<body>
		<div id="wrapper">
			<div id="logo">
				<a title="{SITE_NAME}" href="{NV_BASE_SITEURL}"><img alt="{SITE_NAME}" src="{LOGO_SRC}" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" /></a>
			</div>
			<div id="login-content">
				<h3>{LOGIN_TITLE}</h3>
				<div class="inner-message">{LOGIN_INFO}</div>
					<form class="loginform" method="post" onsubmit="return nv_checkadminlogin_submit();">
						<p>
							<label for="nv_password">{N_PASSWORD}:</label>
							<input class="form-control" name="nv_password" type="password" id="password" />
						</p>
						<input name="redirect" value="{REDIRECT}" type="hidden"/>
						<input name="save" id="save" type="hidden" value="1" />
						<input class="submitform btn btn-primary" type="submit" value="{N_SUBMIT}" />
					</form>
					<p class="text-right" style="padding:10px;">
						<a class="lostpass" href="javascript:void(0);" onclick="nv_admin_logout();">{NV_LOGOUT}</a>
					</p>
			</div>
			<div id="copyright">
				<p>Copyright &copy; <a href="{NV_BASE_SITEURL}" title="{SITE_NAME}">{SITE_NAME}</a>. All rights reserved.</p>
			</div>
		</div>
	</body>
</html>
<!-- END: main -->