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
    <meta name="viewport" content="width=device-width">
    <title>{SITE_NAME} {NV_TITLEBAR_DEFIS} {GLANG.admin_page}</title>
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/bootstrap.min.css">
    <link rel="stylesheet" href="{ASSETS_STATIC_URL}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/style.css">
    <link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/login.css" />
    <script type="text/javascript">
        var base_siteurl = '{NV_BASE_SITEURL}';
    </script>
    <script type="text/javascript" src="{ASSETS_STATIC_URL}/js/global{AUTO_MINIFIED}.js"></script>
    <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/js/login.js"></script>
    <!-- BEGIN: passshow_button -->
    <link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/show-pass-btn/bootstrap3-show-pass.css">
    <script type="text/javascript" src="{ASSETS_STATIC_URL}/js/show-pass-btn/bootstrap3-show-pass.js"></script>
    <!-- END: passshow_button -->
</head>

<body>
    <div class="wrapper" style="display:none;">
        <div class="login-content">
            <div class="login-header"><em class="fa fa-sign-in"></em> {ADMIN_LOGIN_TITLE}</div>
            <div class="login-body" id="login-content">
            [-CONTENT-]
            </div>
            <div class="login-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- BEGIN: lang_multi -->
                        <select id="langinterface" name="langinterface" data-toggle="changeLang" class="form-control input-sm muti-lang">
                            <!-- BEGIN: option -->
                            <option value="{LANGOP}" {SELECTED}>{LANGVALUE} </option>
                            <!-- END: option -->
                        </select>
                        <!-- END: lang_multi -->
                        <a id="adm-redirect" class="btn btn-default btn-sm hidden" href="#"><em class="fa fa-star"></em> {GLANG.acp}</a>
                    </div>
                    <div class="col-xs-12 text-right">
                        <a class="btn btn-default btn-sm" href="{SITEURL}"><em class="fa fa-home"></em> {GLANG.go_clientsector}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>Copyright &copy; <a href="{SITEURL}">{SITE_NAME}</a>. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
<!-- END: main -->

<!-- BEGIN: pre_form -->
<form class="loginform form-horizontal" method="post" action="{NV_BASE_ADMINURL}index.php" data-toggle="preForm">
    <div class="inner-message normal">{GLANG.adminlogininfo}</div>
    <div class="form-detail">
        <div class="form-group">
            <label for="nv_login" class="col-xs-9 control-label form-label">{GLANG.login_name}:</label>
            <div class="col-xs-15"><input autocomplete="off" class="form-control" name="nv_login" type="text" id="nv_login" value="{V_LOGIN}" data-error-mess="{GLANG.username_empty}" /></div>
        </div>
        <div class="form-group">
            <label for="nv_password" class="col-xs-9 control-label form-label">{GLANG.password}:</label>
            <div class="col-xs-15"><input autocomplete="off" class="form-control" name="nv_password" type="password" id="nv_password" value="{V_PASSWORD}" data-error-mess="{GLANG.password_empty}" /></div>
        </div>
        <!-- BEGIN: captcha -->
        <div class="form-group">
            <div class="col-xs-12 col-xs-offset-12 text-right">
                <img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
                <em class="fa fa-refresh fa-lg" data-toggle="nv_change_captcha">&nbsp;</em>
            </div>
        </div>
        <div class="form-group">
            <label for="seccode" class="col-xs-9 control-label form-label">{N_CAPTCHA}:</label>
            <div class="col-xs-15"><input autocomplete="off" name="nv_seccode" type="text" id="seccode" maxlength="{GFX_NUM}" class="form-control captcha" data-error-mess="{LOGIN_ERROR_SECURITY}" /></div>
        </div>
        <!-- END: captcha -->
        <!-- BEGIN: recaptcha -->
        <!-- BEGIN: recaptcha2 -->
        <div class="m-bottom">
            <div id="reCaptcha"></div>
            <script src="https://www.google.com/recaptcha/api.js?hl={SITELANG}&onload=onloadCallback&render=explicit"></script>
            <script type="text/javascript">
                var reCaptcha2,
                    onloadCallback = function() {
                    $('[type=submit]').prop('disabled', true);
                    reCaptcha2 = grecaptcha.render('reCaptcha', {
                        'sitekey': '{RECAPTCHA_SITEKEY}',
                        'type': '{RECAPTCHA_TYPE}',
                        'callback': function(res) {
                            $('[type=submit]').prop('disabled', false);
                        },
                        'expired-callback': function() {
                            $('[type=submit]').prop('disabled', true);
                        },
                        'error-callback': function() {
                            $('[type=submit]').prop('disabled', true);
                        }
                    });
                };
            </script>
        </div>
        <!-- END: recaptcha2 -->
        <!-- BEGIN: recaptcha3 -->
        <input type="hidden" name="g-recaptcha-response" value="" />
        <script src="https://www.google.com/recaptcha/api.js?hl={SITELANG}&amp;render={RECAPTCHA_SITEKEY}"></script>
        <script>var sitekey = '{RECAPTCHA_SITEKEY}';</script>
        <!-- END: recaptcha3 -->
        <!-- END: recaptcha -->
        <!-- BEGIN: warning_ssl -->
        <div class="form-group">
            <small><strong class="error">{GLANG.warning_ssl}:</strong> {GLANG.content_ssl}</small>
        </div>
        <!-- END: warning_ssl -->
        <div class="form-group">
            <div class="col-xs-10 col-xs-offset-9">
                <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}">
                <input class="btn btn-primary btn-block" type="submit" value="{GLANG.loginsubmit}">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-15 col-xs-offset-9 text-right">
                <a title="{LANGLOSTPASS}" href="{LINKLOSTPASS}">{LANGLOSTPASS}?</a>
            </div>
        </div>
    </div>
</form>
<!-- END: pre_form -->

<!-- BEGIN: 2step_form -->
<form class="loginform form-horizontal" method="post" action="{NV_BASE_ADMINURL}index.php" data-toggle="step2Form">
    <div class="inner-message normal">{ADMIN_2STEP_HELLO}.</div>
    <div class="form-detail">
        <!-- BEGIN: must_activate -->
        <p class="text-danger">{GLANG.admin_mactive_2step}. {LANG_CHOOSE}:</p>
        <!-- BEGIN: loop -->
        <a href="{BTN.link}" class="btn btn-block m-bottom btn-info btn-{BTN.key}">{BTN.title}</a>
        <!-- END: loop -->
        <!-- END: must_activate -->

        <!-- BEGIN: choose_method -->
        {HTML_DEFAULT}
        <!-- BEGIN: others -->
        <p class="login-2step-others"><strong>{GLANG.admin_2step_other}:</strong></p>
        {HTML_OTHER}
        <!-- END: others -->
        <!-- END: choose_method -->

        <div class="text-center">
            <a class="btn btn-link" href="#" data-href="{ADMIN_PRE_LOGOUT}" data-toggle="preLogout">{GLANG.admin_pre_logout}</a>
        </div>
    </div>
</form>
<!-- END: 2step_form -->

<!-- BEGIN: code -->
<div class="m-bottom">
    <div class="stepipt m-bottom{SHOW_TOTPPIN}">
        <p>{GLANG.2teplogin_totppin_label} <a class="label label-default" href="#" data-toggle="login2step_change">{GLANG.2teplogin_other_menthod}</a></p>
    </div>
    <div class="stepipt m-bottom{SHOW_BACKUPCODEPIN}">
        <p>{GLANG.2teplogin_code_label} <a class="label label-default" href="#" data-toggle="login2step_change">{GLANG.2teplogin_other_menthod}</a></p>
    </div>
    <div class="form-group">
        <div class="col-xs-16">
            <div class="stepipt{SHOW_TOTPPIN}">
                <input type="text" class="form-control" placeholder="{GLANG.2teplogin_totppin_placeholder}" value="" name="nv_totppin" id="nv_totppin" maxlength="6" autocomplete="off" data-error-mess="{GLANG.2teplogin_error_opt}">
            </div>
            <div class="stepipt{SHOW_BACKUPCODEPIN}">
                <input type="text" class="form-control" placeholder="{GLANG.2teplogin_code_placeholder}" value="" name="nv_backupcodepin" id="nv_backupcodepin" maxlength="8" autocomplete="off" data-error-mess="{GLANG.2teplogin_error_backup}">
            </div>
        </div>
        <div class="col-xs-8">
            <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}">
            <input type="hidden" name="submit2scode" value="1">
            <input class="btn btn-primary btn-block" type="submit" value="{GLANG.confirm}">
        </div>
    </div>
</div>
<!-- END: code -->

<!-- BEGIN: facebook -->
<div class="oauth-facebook m-bottom">
    <a class="btn btn-facebook btn-block" href="{URL}">{GLANG.admin_2step_opt_facebook}</a>
</div>
<!-- END: facebook -->

<!-- BEGIN: google -->
<div class="oauth-google m-bottom">
    <a class="btn btn-google btn-block" href="{URL}">{GLANG.admin_2step_opt_google}</a>
</div>
<!-- END: google -->

<!-- BEGIN: zalo -->
<div class="oauth-zalo m-bottom">
    <a class="btn btn-zalo btn-block" href="{URL}">{GLANG.admin_2step_opt_zalo}</a>
</div>
<!-- END: zalo -->