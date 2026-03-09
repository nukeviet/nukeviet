<form action="{USER_LOGIN}" method="post" data-toggle="userLogin" data-precheck="login_form_precheck" autocomplete="off" novalidate<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 --><!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile --> data-note-webview1="{LANG.note_webview1}" data-note-webview2="{LANG.note_webview2}" data-note-webview3="{LANG.note_webview3}">
    <input type="hidden" name="_csrf" value="{CSRF}">
    <input type="hidden" name="cant_do_2step" value="0">
    <!-- BEGIN: header --><input name="nv_header" value="{NV_HEADER}" type="hidden"><!-- END: header -->
    <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden"><!-- END: redirect -->
    <div class="nv-info margin-bottom" data-default="{GLANG.logininfo}">{GLANG.logininfo}</div>
    <div class="form-detail">
        <div class="loginstep1">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                    <input type="text" class="required form-control" placeholder="{GLANG.username_email}" value="" name="nv_login" maxlength="100" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.username_empty}">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="100" data-pattern="/^(.){3,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                </div>
            </div>
            <div class="text-center margin-bottom-lg">
                <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                <button class="bsubmit btn btn-primary" type="submit">{GLANG.loginsubmit}</button>
            </div>
            <div class="text-center margin-bottom-lg hidden" data-toggle="passkey-ctn">
                <button class="btn btn-default btn-block hidden" type="button" data-toggle="passkey-btn"><i class="fa fa-key" data-icon="fa-key" aria-hidden="true"></i> {GLANG.passkey_login}</button>
                <a class="hidden" href="#" data-toggle="passkey-link">{GLANG.passkey_login}</a>
                <div class="text-danger margin-top-sm hidden" data-toggle="passkey-error"></div>
            </div>
        </div>
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
