<!doctype html>
<html lang="{$LANG->get('Content_Language')}" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <head>
        <title>{$NV_SITE_NAME} {$NV_TITLEBAR_DEFIS} {$LANG->get('admin_page')}</title>
        <meta http-equiv="content-type" content="text/html; charset={$SITE_CHARSET}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="{$SITE_DESCRIPTION}">
        <meta name="author" content="{$NV_SITE_COPYRIGHT}">
        <meta name="generator" content="{$NV_SITE_NAME}">
        <meta name="robots" content="noindex, nofollow">
        <meta http-equiv="expires" content="0" />
        <meta name="resource-type" content="document" />
        <meta name="distribution" content="global" />
        <meta name="copyright" content="Copyright (c) {$NV_SITE_NAME}" />
        <link rel="shortcut icon" href="{$SITE_FAVICON}">

        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/fonts/fonts.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/css/jquery.gritter.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.core.css">
        <link rel="stylesheet" href="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/css/nv.style.css">

        <script type="text/javascript">
        var  nv_base_siteurl = '{$NV_BASE_SITEURL}',
             nv_lang_data = '{$NV_LANG_DATA}',
             nv_lang_interface = '{$NV_LANG_INTERFACE}',
             nv_lang_variable = '{$NV_LANG_VARIABLE}',
             nv_my_ofs = {$NV_SITE_TIMEZONE_OFFSET},
             nv_my_abbr = '{$NV_CURRENTTIME}',
             nv_cookie_prefix = '{$NV_COOKIE_PREFIX}',
             nv_check_pass_mstime = '{$NV_CHECK_PASS_MSTIME}';
        </script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/language/{$NV_LANG_INTERFACE}.js"></script>
        <script type="text/javascript" src="{$NV_BASE_SITEURL}{$NV_ASSETS_DIR}/js/global.js"></script>

        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/bootstrap.bundle.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/libs/gritter/js/jquery.gritter.min.js"></script>

        <!--[if lt IE 9]>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/html5shiv.min.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="nv-splash-page">
        <div class="wrapper admin-login">
            <div class="main-content-wrapper">
                <div class="main-content">
                    <div class="page-body container-fluid">
                        <div class="splash-container">
                            <div class="card card-border-color card-border-color-primary">
                                <div class="card-header"><img class="logo-img" src="{$NV_BASE_SITEURL}{$NV_SITE_LOGO}" alt="{$NV_SITE_NAME}"></div>
                                <div class="card-body">
                                    {if !empty($ERROR)}
                                    <div role="alert" class="alert alert-contrast alert-danger alert-dismissible">
                                        <div class="message">{$ERROR}</div>
                                    </div>
                                    {/if}
                                    {if !empty($LANG_MULTI) and $DATA['login_step'] eq 1}
                                    <div class="form-group text-right">
                                        {$LANG->get('langinterface')}
                                        <button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle">{$LANG_CURRENT_NAME} <span class="icon-dropdown fas fa-chevron-down"></span></button>
                                        <div role="menu" class="dropdown-menu">
                                            {foreach from=$LANG_MULTI item=lang}
                                            <a href="{$lang.link}" class="dropdown-item">{$lang.title}</a>
                                            {/foreach}
                                        </div>
                                    </div>
                                    {/if}
                                    <form method="post" action="{$NV_BASE_ADMINURL}index.php" autocomplete="off" id="admin-login-form">
                                        <div class="loginStep1{if $DATA['login_step'] neq 1} d-none{/if}">
                                            <div class="form-group">
                                                <input class="form-control" id="nv_login" name="nv_login" value="{$DATA.username}" type="text" placeholder="{$LANG->get('username_email')}" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="nv_password" name="nv_password" value="{$DATA.password}" type="password" placeholder="{$LANG->get('password')}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="loginStep2{if $DATA['login_step'] neq 2} d-none{/if}">
                                            <div class="nv-login2step-opt form-group mb-0{if empty($DATA['totppin']) and !empty($DATA['backupcodepin'])} d-none{/if}">
                                                <label for="nv_totppin">{$LANG->get('2teplogin_totppin_label')}</label>
                                                <input class="form-control" id="nv_totppin" name="nv_totppin" value="" type="text" placeholder="{$LANG->get('2teplogin_totppin_placeholder')}" autocomplete="off" maxlength="6">
                                                <div class="text-center mt-2">
                                                    <a href="#" class="login2step-change">{$LANG->get('2teplogin_other_menthod')}</a>
                                                </div>
                                            </div>
                                            <div class="nv-login2step-opt form-group mb-0{if !empty($DATA['totppin']) or empty($DATA['backupcodepin'])} d-none{/if}">
                                                <label for="nv_totppin">{$LANG->get('2teplogin_code_label')}</label>
                                                <input class="form-control" id="nv_backupcodepin" name="nv_backupcodepin" value="" type="text" placeholder="{$LANG->get('2teplogin_code_placeholder')}" autocomplete="off" maxlength="8">
                                                <div class="text-center mt-2">
                                                    <a href="#" class="login2step-change">{$LANG->get('2teplogin_other_menthod')}</a>
                                                </div>
                                            </div>
                                        </div>
                                        {if $DATA['captcha_require']}
                                        <div class="form-group">
                                            {if $DATA['captcha_type'] eq 2}
                                            <div class="nv-row-recaptcha">
                                                <div id="reCaptcha"></div>
                                            </div>
                                            <script src="https://www.google.com/recaptcha/api.js?hl={$NV_LANG_INTERFACE}&onload=onloadCallback&render=explicit"></script>
                                            <script type="text/javascript">
                                            var onloadCallback = function() {
                                                $('[type="submit"]').prop('disabled', true);
                                                grecaptcha.render('reCaptcha', {
                                                    'sitekey': '{$RECAPTCHA_SITEKEY}',
                                                    'type': '{$RECAPTCHA_TYPE}',
                                                    'callback': function(res) {
                                                        $('[type="submit"]').prop('disabled', false);
                                                    }
                                                });
                                            };
                                            </script>
                                            {else}
                                            <div class="nv-row-captcha">
                                                <div class="ipt">
                                                    <input class="form-control" id="nv_seccode" name="nv_seccode" value="" type="text" autocomplete="off" maxlength="{$GFX_NUM}" placeholder="{$LANG->get('securitycode')}">
                                                </div>
                                                <div class="img">
                                                    <img id="vimg" alt="{$LANG->get('securitycode')}" src="{$NV_BASE_SITEURL}index.php?scaptcha=captcha&amp;t={$NV_CURRENTTIME}" width="{$GFX_WIDTH}" height="{$GFX_HEIGHT}" />
                                                </div>
                                                <div class="rfs">
                                                    <a href="#" id="changevimg"><i class="fas fa-sync"></i></a>
                                                </div>
                                            </div>
                                            {/if}
                                        </div>
                                        {/if}
                                        {if $DATA['login_step'] eq 1}
                                        <div class="form-group row login-tools">
                                            <div class="col-6 login-remember">
                                                <label class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" name="login_remember" id="login_remember"{if $DATA['login_remember']} checked="checked"{/if}><span class="custom-control-label"><abbr title="{$LANG->get('login_remember_admin')}">{$LANG->get('login_remember')}</abbr></span>
                                                </label>
                                            </div>
                                            <div class="col-6 login-forgot-password"><a href="{$LINKLOSTPASS}">{$LANG->get('lostpass')}?</a></div>
                                        </div>
                                        {/if}
                                        <div class="form-group login-submit{if $DATA['login_step'] eq 3} d-none{/if}">
                                            <input type="hidden" name="checkss" value="{$NV_CHECK_SESSION}">
                                            <input type="submit" class="btn btn-primary btn-xl" value="{$LANG->get('loginsubmit')}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="splash-footer"><span>Copyright © <a href="{$THEME_SITE_HREF}">{$NV_SITE_NAME}</a>. All rights reserved.</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.core.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.main.js"></script>
        <script src="{$NV_BASE_SITEURL}themes/{$NV_ADMIN_THEME}/js/nv.login.js"></script>
    </body>
</html>
