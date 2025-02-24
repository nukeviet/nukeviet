{include file='header.tpl'}
<div class="login-page bg-body-tertiary">
    <div class="d-flex flex-column min-vh-100">
        <div class="flex-shrink-1 flex-grow-1 d-flex justify-content-center align-items-center">
            <div>
                <div class="card card-login">
                    <div class="login-header card-header text-center fw-medium fs-4 text-bg-primary border-bottom-0">
                        {if empty($PRE_DATA)}
                        <i class="fa-solid fa-right-to-bracket"></i> {$LANG->getGlobal('adminlogin')}
                        {else}
                        <i class="fa-solid fa-unlock-keyhole"></i> {$LANG->getGlobal('2teplogin')}
                        {/if}
                    </div>
                    <div class="card-body">
                        <div class="login-box">
                            {if empty($PRE_DATA)}
                            {* Form đăng nhập bằng tài khoản (bước 1) *}
                            <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php" data-toggle="preForm" data-passkey-allowed="{$PASSKEY_ALLOWED ? 1 : 0}">
                                <div class="mb-3 border-3 border-start ps-2" data-toggle="message">{$LANG->getGlobal('adminlogininfo')}</div>
                                <div data-toggle="form">
                                    <div class="mb-3">
                                        <label for="nv_login" class="form-label text-dark fw-medium">{$LANG->getGlobal('login_name')}</label>
                                        <input class="form-control" name="nv_login" type="text" id="nv_login" value="{$V_LOGIN}" data-error-mess="{$LANG->getGlobal('username_empty')}" autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex gap-2">
                                            <label for="nv_password" class="form-label text-dark fw-medium">{$LANG->getGlobal('password')}</label>
                                            <div class="ms-auto">
                                                <a title="{$LANG->getGlobal('lostpass')}" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$GCONFIG.site_lang}&amp;{$smarty.const.NV_NAME_VARIABLE}=users&amp;{$smarty.const.NV_OP_VARIABLE}=lostpass" tabindex="-1">{$LANG->getGlobal('lostpass')}?</a>
                                            </div>
                                        </div>
                                        <input class="form-control" name="nv_password" type="password" id="nv_password" value="{$V_PASSWORD}" data-error-mess="{$LANG->getGlobal('password_empty')}" autocomplete="off">
                                    </div>
                                    {if $GFX_CHK}
                                    {if $CAPTCHA_TYPE eq 'captcha'}
                                    <div class="mb-3">
                                        <label for="seccode" class="form-label text-dark fw-medium">{$LANG->getGlobal('securitycode1')}</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input name="nv_seccode" type="text" id="seccode" maxlength="{$smarty.const.NV_GFX_NUM}" class="form-control captcha" data-error-mess="{$LOGIN_ERROR_SECURITY}" autocomplete="off">
                                            <img id="vimg" class="captcha-img" alt="{$LANG->getGlobal('securitycode1')}" src="{$smarty.const.SRC_CAPTCHA}">
                                            <a href="#" data-toggle="nv_change_captcha" title="{$LANG->getGlobal('refresh')}" aria-label="{$LANG->getGlobal('refresh')}"><i class="fa-solid fa-rotate fa-lg"></i></a>
                                        </div>
                                    </div>
                                    {elseif $CAPTCHA_TYPE eq 'recaptcha'}
                                    {if $GCONFIG.recaptcha_ver eq 2}
                                    <div class="mb-3">
                                        <div id="reCaptcha" class="recaptcha-holder"></div>
                                        <script src="https://www.google.com/recaptcha/api.js?hl={$smarty.const.NV_LANG_INTERFACE}&amp;onload=onloadCallback&amp;render=explicit"></script>
                                        <script type="text/javascript">
                                        var reCaptcha2,
                                            onloadCallback = function() {
                                            $('[type=submit]').prop('disabled', true);
                                            reCaptcha2 = grecaptcha.render('reCaptcha', {
                                                'sitekey': '{$GCONFIG.recaptcha_sitekey}',
                                                'type': '{$GCONFIG.recaptcha_type}',
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
                                    {elseif $GCONFIG.recaptcha_ver eq 3}
                                    <input type="hidden" name="g-recaptcha-response" value="">
                                    <script src="https://www.google.com/recaptcha/api.js?hl={$smarty.const.NV_LANG_INTERFACE}&amp;render={$GCONFIG.recaptcha_sitekey}"></script>
                                    <script>
                                        var sitekey = '{$GCONFIG.recaptcha_sitekey}';
                                        grecaptcha.ready(() => {
                                            window.recaptcha3Ready = true;
                                            document.dispatchEvent(new Event('nv.recaptcha3.ready'));
                                        });
                                    </script>
                                    {/if}
                                    {elseif $CAPTCHA_TYPE eq 'turnstile'}
                                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
                                    <div class="mb-3">
                                        <div id="cf-turnstile" class="cf-turnstile"></div>
                                    </div>
                                    <script>
                                    turnstile.ready(function () {
                                        $('[type=submit]').prop('disabled', true);
                                        turnstile.render("#cf-turnstile", {
                                            'sitekey': "{$GCONFIG.turnstile_sitekey}",
                                            'callback': function(res) {
                                                $('[type=submit]').prop('disabled', false);
                                            },
                                            'expired-callback': function() {
                                                $('[type=submit]').prop('disabled', true);
                                            },
                                            'error-callback': function() {
                                                $('[type=submit]').prop('disabled', true);
                                            },
                                            'language': "{$smarty.const.NV_LANG_INTERFACE}"
                                        });
                                    });
                                    </script>                                    
                                    {/if}
                                    {/if}
                                    <div class="d-grid">
                                        <input class="btn btn-primary" type="submit" value="{$LANG->getGlobal('loginsubmit')}">
                                    </div>
                                    <div class="d-none" data-toggle="passkey-btn">
                                        <div class="d-flex align-items-center my-2">
                                            <div class="flex-grow-1 border-top"></div>
                                            <span class="mx-3">{$LANG->getGlobal('or')}</span>
                                            <div class="flex-grow-1 border-top"></div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-secondary"><i class="fa-solid fa-user-shield" data-icon="fa-user-shield"></i> {$LANG->getGlobal('passkey_login')}</button>
                                        </div>
                                        <div class="text-danger mt-2 d-none" data-toggle="passkey-error"></div>
                                    </div>
                                    <div class="mt-2 text-center d-none" data-toggle="passkey-link">
                                        <a href="#">{$LANG->getGlobal('passkey_login')}</a>
                                    </div>
                                </div>
                                <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                                {if $SV->getOriginalProtocol() neq 'https'}
                                <div class="mt-3">
                                    <small><strong class="text-danger">{$LANG->getGlobal('warning_ssl')}:</strong> {$LANG->getGlobal('content_ssl')}</small>
                                </div>
                                {/if}
                            </form>
                            {else}
                            {* Step xác thực hai bước *}
                            <form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php" data-toggle="step2Form" data-passkey-allowed="{$PASSKEY_ALLOWED ? 1 : 0}">
                                {if empty($ERROR)}
                                <div class="mb-3 border-3 border-start ps-2" data-toggle="message">{$LANG->getGlobal('admin_hello_2step', $PRE_DATA.full_name)}.</div>
                                {else}
                                <div class="{if not empty($CFG_2STEP.opts)}mb-3 {/if}border-3 border-start ps-2 border-danger text-danger" data-toggle="message">{$ERROR}.</div>
                                {/if}
                                <div data-toggle="form">
                                    {if not empty($CFG_2STEP.opts)}
                                    {if $CFG_2STEP.count_active lt 1}
                                    <p class="text-danger">{$LANG->getGlobal('admin_mactive_2step')}. {$LANG->getGlobal($CFG_2STEP.count_opts gt 1 ? 'admin_mactive_2step_choose1' : 'admin_mactive_2step_choose0')}:</p>
                                    <div class="d-grid gap-2 mb-3">
                                        {assign var="isRegularMethod" value=0 nocache}
                                        {foreach from=$CFG_2STEP.opts item=opt}
                                        {if ($opt eq 'code' or $opt eq 'key')}
                                        {if $isRegularMethod eq 0}
                                        {assign var="isRegularMethod" value=($isRegularMethod + 1) nocache}
                                        {if in_array('code', $CFG_2STEP.opts) and in_array('key', $CFG_2STEP.opts)}
                                            {assign var="setupTitle" value=$LANG->getGlobal('admin_setup_2fa_keycode') nocache}
                                        {else}
                                            {assign var="setupTitle" value={$LANG->getGlobal("admin_2step_opt_`$opt`")} nocache}
                                        {/if}
                                        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?auth={$opt}" class="btn btn-secondary">{$setupTitle}</a>
                                        {/if}
                                        {else}
                                        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?auth={$opt}" class="btn btn-info btn-{$opt}">{$LANG->getGlobal("admin_2step_opt_`$opt`")}</a>
                                        {/if}
                                        {/foreach}
                                    </div>
                                    {else}
                                    <div class="d-grid gap-2 mb-3">
                                        {$HTML_DEFAULT}
                                    </div>
                                    {if not empty($HTML_OTHER)}
                                    <p class="mb-2"><strong>{$LANG->getGlobal('admin_2step_other')}:</strong></p>
                                    <div class="d-grid gap-2 mb-3">
                                        {$HTML_OTHER}
                                    </div>
                                    {/if}
                                    {/if}
                                    {/if}
                                    <div class="text-center">
                                        <a href="#" data-href="{$smarty.const.NV_BASE_ADMINURL}index.php?pre_logout=1&amp;checkss={$smarty.const.NV_CHECK_SESSION}" data-toggle="preLogout">{$LANG->getGlobal('admin_pre_logout')}</a>
                                    </div>
                                </div>
                            </form>
                            {/if}
                        </div>
                    </div>
                    <div class="login-footer card-footer bg-body-tertiary d-flex gap-2">
                        {if not empty($GCONFIG.lang_multi)}
                        <select id="langinterface" name="langinterface" data-toggle="changeLang" class="form-select form-select-sm w-auto mw-100">
                            {foreach from=$LANGS item=lmuti}
                            <option value="{$smarty.const.NV_BASE_ADMINURL}index.php?langinterface={$lmuti.lang}"{if $lmuti.lang eq $smarty.const.NV_LANG_INTERFACE} selected{/if}>{$lmuti.name}</option>
                            {/foreach}
                        </select>
                        {/if}
                        <a id="adm-redirect" class="btn btn-secondary btn-sm d-none" href="#"><i class="fa-solid fa-star text-warning"></i> {$LANG->getGlobal('acp')}</a>
                        <a class="btn btn-secondary btn-sm ms-auto" href="{$GCONFIG.site_url}"><i class="fa-solid fa-house"></i> {$LANG->getGlobal('go_clientsector')}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center p-3">
            Copyright &copy; <a href="{$GCONFIG.site_url}">{$GCONFIG.site_name}</a>. All rights reserved.
        </div>
    </div>
</div>
<script type="text/javascript" src="{$smarty.const.NV_BASE_SITEURL}themes/{$ADMIN_THEME}/js/nv.login.js"></script>
{include file='footer.tpl'}
