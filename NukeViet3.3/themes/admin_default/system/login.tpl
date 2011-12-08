<!-- BEGIN: main --> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset={CHARSET}" />
        <meta http-equiv="expires" content="0" />
        <meta name="resource-type" content="document" />
        <meta name="distribution" content="global" />
        <meta name="copyright" content="Copyright (c) {SITE_NAME}" />
        <meta name="robots" content="noindex, nofollow" />
        <title>{SITE_NAME} | {PAGE_TITLE}</title>
        <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/login.css" />
        <script type="text/javascript">
            var jsi = new Array('{SITELANG}', '{NV_BASE_SITEURL}', '{CHECK_SC}', '{GFX_NUM}');
            <!-- BEGIN: jscaptcha -->
            	var login_error_security = '{LOGIN_ERROR_SECURITY}';
            <!-- END: jscaptcha -->
        </script>
        <script type="text/javascript">
            var nv_cookie_prefix = '{NV_COOKIE_PREFIX}';
        </script>        
        <script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>          
        <script type="text/javascript" src="{NV_BASE_SITEURL}js/admin_login.js"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
            <script type="text/javascript">
            DD_belatedPNG.fix('#');
            </script>
        <![endif]-->
    </head>
    <body>
        <div id="wrapper">
            <div id="logo">
                <a title="{SITE_NAME}" href="{NV_BASE_SITEURL}"><img alt="{SITE_NAME}" title="{SITE_NAME}" src="{LOGO_SRC}" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" /></a>
            </div>
            <div id="login-header">
                <div id="login-header-left"></div>
                <div id="login-header-center">
                    <h3><strong>{LOGIN_TITLE}</strong></h3>
            	</div>
            <div id="login-header-right"></div>
        </div>
        <div id="login-content">
            <div>
                <div>
                    {LOGIN_INFO}
                    <form class="loginform" method="post" action="{NV_BASE_ADMINURL}index.php" onsubmit="return nv_checkadminlogin_submit();">
                        <ul>
                            <!-- BEGIN: lang_multi -->
                            <li>
                                <label>{LANGTITLE}:</label>
                                <select id="langinterface" name="langinterface" onchange="top.location.href=this.options[this.selectedIndex].value;">
                                    <!-- BEGIN: option -->
                                    	<option value="{LANGOP}" {SELECTED}>{LANGVALUE}  </option>
                                    <!-- END: option -->
                                </select>
                            </li>
                            <!-- END: lang_multi -->
                            <li>
                                <label>{N_LOGIN}:</label>
                                <input name="nv_login" type="text" id="login" value="{V_LOGIN}" maxlength="{NICKMAX}" />
                            </li>
                            <li>
                                <label>{N_PASSWORD}:</label>
                                <input name="nv_password" type="password" id="password" maxlength="{PASSMAX}" />
                            </li>
                            <!-- BEGIN: captcha -->
                            <li>
                                <label>{N_CAPTCHA}:</label>
                                <input name="nv_seccode" type="text" id="seccode" maxlength="{GFX_NUM}" style="width:60px;"/><img id="vimg" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img alt="{CAPTCHA_REFRESH}" title="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha();"/>
                            </li><!-- END: captcha -->
                        </ul>
                        <div id="smb">
                            <input class="submitform" type="submit" value="{N_SUBMIT}" />
                        </div>
                    </form>
                    <p style="text-align:right; padding:10px;">
                        <a class="lostpass" title="{LANGLOSTPASS}" href="{LINKLOSTPASS}">{LANGLOSTPASS}?</a>
                    </p>
                </div>
            </div>
        </div>
        <div id="login-footer">
            <div id="login-footer-left"></div>
            <div id="login-footer-center"></div>
            <div id="login-footer-right"></div>
        </div>
        <div id="copyright">
            <p>
                Copyright &copy; <a href="{SITEURL}">{SITE_NAME}</a>. All rights reserved.
            </p>
        </div>
        </div>
        <script type="text/javascript">
            document.getElementById('login').focus();
        </script>
    </body>
</html>
<!-- END: main -->