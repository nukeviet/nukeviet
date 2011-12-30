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
        <link rel="stylesheet" type="text/css" href="{CSS}" />
        <script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{SITELANG}.js"></script>
        <script type="text/javascript">
            function nv_checkadminlogin_submit()
            {
               var password = document.getElementById( 'password' );
               if(password.value=='')
               {
            	   return false;
               }
               return true;
            } 

           function nv_admin_logout()
           {
              if (confirm(nv_admlogout_confirm[0]))
              {
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
            <div id="login-header">
                <div id="login-header-left">
                </div>
                <div id="login-header-center">
                    <h3>
                    <strong>
                    {LOGIN_TITLE}</h3>
                </strong>
            </div>
            <div id="login-header-right">
            </div>
        </div>
        <div id="login-content">
            <div>
                <div>
                    {LOGIN_INFO}
                    <form class="loginform" method="post" onsubmit="return nv_checkadminlogin_submit();">
                        <ul>
                            <li>
                                <label>
                                    {N_PASSWORD}:
                                </label>
                                <input name="nv_password" type="password" id="password" />
                            </li>
                        </ul>
                        <input name="redirect" value="{REDIRECT}" type="hidden"/>
                        <input name="save" id="save" type="hidden" value="1" />
                        <input class="submitform" type="submit" value="{N_SUBMIT}" />
                    </form>
                    <p align="right" style="padding:10px;">
                        <a class="lostpass" href="javascript:void(0);" onclick="nv_admin_logout();">{NV_LOGOUT}</a>
                    </p>                    
                </div>
            </div>
        </div>
        <div id="login-footer">
            <div id="login-footer-left">
            </div>
            <div id="login-footer-center">
            </div>
            <div id="login-footer-right">
            </div>
        </div>
        <div id="copyright">
            <p>
                Copyright &copy; <a href="{NV_BASE_SITEURL}" title="{SITE_NAME}">{SITE_NAME}</a>. All rights reserved.
            </p>
        </div>
        </div>
    </body>
</html>
<!-- END: main -->
