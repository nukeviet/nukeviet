
{if $smarty.const.NV_OPENID_ALLOWED and in_array('google-identity', $GCONFIG.openid_servers, true)}
<script src="https://accounts.google.com/gsi/client" async defer></script>
{/if}
<script src="{$CSS_JS.js}?t={$GCONFIG.timestamp}"></script>
<link rel="stylesheet" type="text/css" href="{$CSS_JS.css}?t={$GCONFIG.timestamp}">

<form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=login" method="post" class="fw-300"
    data-toggle="userLogin" data-precheck="nv_precheck_form"
    autocomplete="off" novalidate {$CAPTCHA_ATTRS}
    data-note-webview1="{$LANG->getModule('note_webview1')}"
    data-note-webview2="{$LANG->getModule('note_webview2')}"
    data-note-webview3="{$LANG->getModule('note_webview3')}"
>
    <input type="hidden" name="_csrf" value="{$CSRF}">
    <input type="hidden" name="cant_do_2step" value="0">
    {if not empty($NV_HEADER)}
    <input type="hidden" name="nv_header" value="{$NV_HEADER}">
    {/if}
    {if not empty($NV_REDIRECT)}
    <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
    {/if}
    <div data-area="info" data-default="{$LANG->getGlobal('logininfo')}" class="mb-3">{$LANG->getGlobal('logininfo')}</div>
    <div data-area="form">
        <div data-area="step1">
            <div class="mb-3 position-relative">
                <label class="form-label fw-medium" for="nv_login">{$LANG->getGlobal('username_email')}:</label>
                <div class="position-relative">
                    <input type="text" autocomplete="username" class="form-control ps-with-fw-icon" id="nv_login" name="nv_login" maxlength="100" value=""
                        placeholder="{$LANG->getGlobal('username_email')}"
                        data-error-mess="{$LANG->getGlobal('username_empty')}"
                        data-valid data-error-type="tooltip"
                    >
                    <i class="z-10 text-center fa-fw fa-solid fa-user position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                </div>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label fw-medium" for="nv_password">{$LANG->getGlobal('password')}:</label>
                <div class="position-relative">
                    <input type="password" autocomplete="current-password" class="form-control ps-with-fw-icon" id="nv_password" name="nv_password" maxlength="100" value=""
                        placeholder="{$LANG->getGlobal('password')}"
                        data-error-mess="{$LANG->getGlobal('password_empty')}"
                        data-valid data-error-type="tooltip"
                    >
                    <i class="z-10 text-center fa-fw fa-solid fa-lock position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                </div>
            </div>
            <div class="mb-3 d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('reset')}" title="{$LANG->getGlobal('reset')}" data-toggle="nv-reset-form">
                    <i class="fa-solid fa-rotate-left"></i><span class="visually-hidden-focusable">{$LANG->getGlobal('reset')}</span>
                </button>
                <button type="submit" class="btn btn-primary flex-fill">
                    {$LANG->getGlobal('loginsubmit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </div>
            <div class="text-center mb-3 d-none" data-area="passkey-ctn">
                <button class="btn btn-outline-secondary w-100 d-none" type="button" data-toggle="passkey-btn">
                    <i class="fa-solid fa-key" data-icon="fa-key"></i> {$LANG->getGlobal('passkey_login')}
                </button>
                <a class="d-none" href="#" data-toggle="passkey-link">{$LANG->getGlobal('passkey_login')}</a>
                <div class="text-danger mt-1 d-none" data-area="passkey-error"></div>
            </div>
        </div>
        <div data-area="step2">

        </div>
    </div>
</form>

{*
    <div class="form-detail">
        <div class="loginstep2 hidden">
            <div class="loginstep2-item loginstep2-app hidden">
                <div class="form-group">
                    <label class="margin-bottom">{GLANG.2teplogin_totppin_label}</label>
                    <div class="input-group margin-bottom">
                        <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                        <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_totppin_placeholder}" value="" name="nv_totppin" maxlength="6" data-pattern="/^(.){6,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.2teplogin_totppin_placeholder}">
                    </div>
                </div>
                <div class="text-center margin-bottom-lg">
                    <button type="button" class="btn btn-default" data-toggle="validReset2fa">{GLANG.reset}</button>
                    <button class="bsubmit btn btn-primary" type="submit">{GLANG.verify}</button>
                </div>
            </div>
            <div class="loginstep2-item loginstep2-code hidden">
                <div class="form-group">
                    <label class="margin-bottom">{GLANG.2teplogin_code_label}</label>
                    <div class="input-group margin-bottom">
                        <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                        <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_code_placeholder}" value="" name="nv_backupcodepin" maxlength="8" data-pattern="/^(.){8,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.2teplogin_code_placeholder}">
                    </div>
                </div>
                <div class="text-center margin-bottom-lg">
                    <button type="button" class="btn btn-default" data-toggle="validReset2fa">{GLANG.reset}</button>
                    <button class="bsubmit btn btn-primary" type="submit">{GLANG.verify}</button>
                </div>
            </div>
            <div class="loginstep2-item loginstep2-key hidden">
                <div class="margin-bottom-lg">
                    <div class="margin-bottom-sm">{GLANG.2fa_method_key2}</div>
                    <div class="text-danger hidden margin-bottom-sm" data-toggle="passkey-error"></div>
                    <button class="btn btn-block btn-success" type="button" data-toggle="passkey-verify"><i class="fa fa-key" data-icon="fa-key" aria-hidden="true"></i> {GLANG.2fa_method_key1}</button>
                </div>
                <input type="hidden" name="auth_assertion" value="">
            </div>
            <div class="loginstep2-methods methods" data-is-key="0">
                <div class="margin-bottom-sm">
                    <strong>{GLANG.2fa_problems}:</strong>
                </div>
                <ul class="list-default">
                    <li class="item"><a href="#" data-toggle="2fa-choose" data-method="key">{GLANG.2fa_method_key}</a></li>
                    <li class="item"><a href="#" data-toggle="2fa-choose" data-method="app">{GLANG.2fa_method_app}</a></li>
                    <li class="item"><a href="#" data-toggle="2fa-choose" data-method="code">{GLANG.2fa_method_code}</a></li>
                    <li class="item"><a href="#" data-toggle="2fa-choose-recovery">{GLANG.2fa_recovery}</a></li>
                </ul>
            </div>
        </div>

        <!-- BEGIN: allowuserreg2_form -->
        <div class="form-group">
            <div class="text-right clearfix">
                <a href="#" data-toggle="modalShowByObj" data-obj="#guestReg_{BLOCKID}" data-callback="recaptchareset">{GLANG.register}</a>
            </div>
        </div>
        <!-- END: allowuserreg2_form -->

        <!-- BEGIN: allowuserreg_linkform -->
        <div class="form-group">
            <div class="text-right clearfix">
                <a href="{USER_REGISTER}">{GLANG.register}</a>
            </div>
        </div>
        <!-- END: allowuserreg_linkform -->

        <div class="alert alert-warning hidden" data-toggle="webview-warning"></div>

        <!-- BEGIN: openid -->
        <!-- BEGIN: google_identity_onload -->
        <div id="g_id_onload" data-client_id="{GOOGLE_CLIENT_ID}" data-context="signin" data-ux_mode="popup" data-callback="GIDHandleCredentialResponse" data-itp_support="true" data-use_fedcm_for_prompt="true" data-url="{GOOGLE_IDENTITY_URL}" data-csrf="{CHECKSS}" data-redirect="{REDIRECT}">
        </div>

        <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="signin_with" data-size="large" data-locale="{NV_LANG_INTERFACE}" data-logo_alignment="center" data-width="300">
        </div>

        <div id="g_id_confirm" class="hidden">
            <div class="alert alert-info">
                <p class="m-bottom">{LANG.g_id_confirm}</p>
                <div class="text-center">
                    <a href="" class="btn btn-primary">{LANG.g_id_confirm2}</a>
                </div>
            </div>
        </div>
        <!-- END: google_identity_onload -->
        <div class="text-center openid-btns">
            <!-- BEGIN: server -->
            <div class="btn-group m-bottom btn-group-justified">
                <button class="btn openid-{OPENID.server} disabled" type="button" tabindex="-1"><i class="fa fa-fw fa-{OPENID.icon}"></i></button>
                <a class="btn openid-{OPENID.server}" href="{OPENID.href}" data-toggle="openID_load">{LANG.login_with} {OPENID.title}</a>
            </div>
            <!-- END: server -->
        </div>
        <!-- END: openid -->
    </div>
</form>
*}
