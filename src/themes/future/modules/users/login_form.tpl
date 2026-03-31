
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
                <label class="form-label" for="nv_login">{$LANG->getGlobal('username_email')}:</label>
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
                <label class="form-label" for="nv_password">{$LANG->getGlobal('password')}:</label>
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
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-title="{$LANG->getGlobal('reset')}" data-bs-trigger="hover" title="{$LANG->getGlobal('reset')}" data-toggle="nv-reset-form">
                    <i class="fa-solid fa-rotate-left"></i><span class="visually-hidden-focusable">{$LANG->getGlobal('reset')}</span>
                </button>
                <button type="submit" class="btn btn-primary flex-fill">
                    {$LANG->getGlobal('loginsubmit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </div>
            <div class="text-center mb-3 d-none" data-area="passkey-ctn">
                <button class="btn btn-outline-primary w-100 d-none" type="button" data-toggle="passkey-btn">
                    <i class="fa-solid fa-key" data-icon="fa-key"></i> {$LANG->getGlobal('passkey_login')}
                </button>
                <a class="d-none" href="#" data-toggle="passkey-link">{$LANG->getGlobal('passkey_login')}</a>
                <div class="text-danger mt-1 d-none" data-area="passkey-error"></div>
            </div>
        </div>
        <div data-area="step2" class="d-none border-top pt-3">
            <div class="d-none" data-item="app">
                <div class="mb-3 position-relative">
                    <label class="form-label" for="nv_totppin">{$LANG->getGlobal('2teplogin_totppin_label')}:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-with-fw-icon" placeholder="{$LANG->getGlobal('2teplogin_totppin_placeholder')}"
                            value="" name="nv_totppin" id="nv_totppin" minlength="6" maxlength="6"
                            data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getGlobal('2teplogin_totppin_placeholder')}"
                        >
                        <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="validReset2fa">{$LANG->getGlobal('reset')}</button>
                    <button class="btn btn-primary" type="submit">{$LANG->getGlobal('verify')}</button>
                </div>
            </div>
            <div class="d-none" data-item="code">
                <div class="mb-3 position-relative">
                    <label class="form-label" for="nv_backupcodepin">{$LANG->getGlobal('2teplogin_code_label')}:</label>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-with-fw-icon" placeholder="{$LANG->getGlobal('2teplogin_code_placeholder')}"
                            value="" name="nv_backupcodepin" id="nv_backupcodepin" minlength="8" maxlength="8"
                            data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getGlobal('2teplogin_code_placeholder')}"
                        >
                        <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="validReset2fa">{$LANG->getGlobal('reset')}</button>
                    <button class="btn btn-primary" type="submit">{$LANG->getGlobal('verify')}</button>
                </div>
            </div>
            <div class="d-none" data-item="key">
                <div class="mb-3">
                    <div class="mb-1">{$LANG->getGlobal('2fa_method_key2')}</div>
                    <div class="text-danger d-none mb-1" data-area="passkey-error"></div>
                    <button class="btn w-100 btn-success" type="button" data-toggle="passkey-verify">
                        <i class="fa-solid fa-key" data-icon="fa-key" aria-hidden="true"></i> {$LANG->getGlobal('2fa_method_key1')}
                    </button>
                </div>
                <input type="hidden" name="auth_assertion" value="">
            </div>
            <div data-area="step2-methods" data-is-key="0">
                <div class="mb-1 fw-medium">{$LANG->getGlobal('2fa_problems')}:</div>
                <ul>
                    <li data-area="2fa-cctn"><a href="#" data-toggle="2fa-choose" data-method="key">{$LANG->getGlobal('2fa_method_key')}</a></li>
                    <li data-area="2fa-cctn"><a href="#" data-toggle="2fa-choose" data-method="app">{$LANG->getGlobal('2fa_method_app')}</a></li>
                    <li data-area="2fa-cctn"><a href="#" data-toggle="2fa-choose" data-method="code">{$LANG->getGlobal('2fa_method_code')}</a></li>
                    <li data-area="2fa-cctn"><a href="#" data-toggle="2fa-choose-recovery">{$LANG->getGlobal('2fa_recovery')}</a></li>
                </ul>
            </div>
        </div>

        <div class="alert alert-warning d-none" role="alert" data-toggle="webview-warning"></div>

        {if $smarty.const.NV_OPENID_ALLOWED}
        {* Nếu cho phép đăng nhập Oauth *}
        {if in_array('google-identity', $GCONFIG.openid_servers, true)}
        <div id="g_id_onload" data-client_id="{$GCONFIG.google_client_id}" data-context="signin"
            data-ux_mode="popup" data-callback="GIDHandleCredentialResponse"
            data-itp_support="true" data-use_fedcm_for_prompt="true"
            data-url="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=oauth&amp;server=google-identity"
            data-csrf="{$OAUTH_CHECKSS}" data-redirect="{$NV_REDIRECT}">
        </div>
        <div class="g_id_signin" data-type="standard" data-shape="rectangular"
            data-theme="outline" data-text="signin_with" data-size="large"
            data-locale="{$smarty.const.NV_LANG_INTERFACE}" data-logo_alignment="center" data-width="300">
        </div>
        <div id="g_id_confirm" class="d-none">
            <div class="alert alert-info" role="alert">
                <p>{$LANG->getModule('g_id_confirm')}</p>
                <div class="text-center">
                    <a href="" class="btn btn-primary">{$LANG->getModule('g_id_confirm2')}</a>
                </div>
            </div>
        </div>
        {/if}
        <div class="vstack mt-2 gap-2">
            {assign var="oauth_icons" value=[
                'single-sign-on' => '<i class="fa-solid fa-building-lock"></i>',
                'google' => '<i class="fa-brands fa-google"></i>',
                'facebook' => '<i class="fa-brands fa-facebook"></i>',
                'zalo' => '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="1.2em" height="1.2em" viewBox="0,0,256,256"><g fill="currentColor" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h32c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h6.58008c-3.57109,3.71569 -5.58008,8.51808 -5.58008,13.5c0,5.16 2.11016,10.09984 5.91016,13.83984c0.12,0.21 0.21977,1.23969 -0.24023,2.42969c-0.29,0.75 -0.87023,1.72961 -1.99023,2.09961c-0.43,0.14 -0.70969,0.56172 -0.67969,1.01172c0.03,0.45 0.36078,0.82992 0.80078,0.91992c2.87,0.57 4.72852,-0.2907 6.22852,-0.9707c1.35,-0.62 2.24133,-1.04047 3.61133,-0.48047c2.8,1.09 5.77938,1.65039 8.85938,1.65039c4.09369,0 8.03146,-0.99927 11.5,-2.88672v3.88672c0,1.66848 -1.33152,3 -3,3h-32c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM33,15c0.55,0 1,0.45 1,1v9c0,0.55 -0.45,1 -1,1c-0.55,0 -1,-0.45 -1,-1v-9c0,-0.55 0.45,-1 1,-1zM18,16h5c0.36,0 0.70086,0.19953 0.88086,0.51953c0.17,0.31 0.15875,0.69977 -0.03125,1.00977l-4.04883,6.4707h3.19922c0.55,0 1,0.45 1,1c0,0.55 -0.45,1 -1,1h-5c-0.36,0 -0.70086,-0.19953 -0.88086,-0.51953c-0.17,-0.31 -0.15875,-0.69977 0.03125,-1.00977l4.04883,-6.4707h-3.19922c-0.55,0 -1,-0.45 -1,-1c0,-0.55 0.45,-1 1,-1zM27.5,19c0.61,0 1.17945,0.16922 1.68945,0.44922c0.18,-0.26 0.46055,-0.44922 0.81055,-0.44922c0.55,0 1,0.45 1,1v5c0,0.55 -0.45,1 -1,1c-0.35,0 -0.63055,-0.18922 -0.81055,-0.44922c-0.51,0.28 -1.07945,0.44922 -1.68945,0.44922c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM38.5,19c1.93,0 3.5,1.57 3.5,3.5c0,1.93 -1.57,3.5 -3.5,3.5c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM27.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.19551,0.03988 -0.37754,0.11691 -0.53711,0.22461c-0.15957,0.1077 -0.2966,0.24473 -0.4043,0.4043c-0.10769,0.15957 -0.18473,0.3416 -0.22461,0.53711c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.15957,0.10769 0.3416,0.18473 0.53711,0.22461c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5zM38.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.09775,0.01994 -0.19149,0.04805 -0.28125,0.08594c-0.08977,0.03789 -0.17607,0.08482 -0.25586,0.13867c-0.07979,0.05385 -0.15289,0.11578 -0.2207,0.18359c-0.13562,0.13563 -0.24648,0.29703 -0.32227,0.47656c-0.03789,0.08976 -0.066,0.1835 -0.08594,0.28125c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.07979,0.05385 0.16609,0.10078 0.25586,0.13867c0.08976,0.03789 0.1835,0.066 0.28125,0.08594c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5z"></path></g></g></svg>'
            ]}
            {foreach from=$GCONFIG.openid_servers item=server}
            {if $server == 'google-identity'}
                {continue}
            {/if}
            <a class="btn btn-{$server} d-flex align-items-center justify-content-center gap-2" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=oauth&amp;server={$server}&amp;nv_redirect={$NV_REDIRECT ?: $DEFAULT_REDIRECT}" data-toggle="openID_load">
                {$oauth_icons[$server]|default:''} {$LANG->getModule('login_with')} {$server|capitalize}
            </a>
            {/foreach}
        </div>
        {/if}
    </div>
</form>
