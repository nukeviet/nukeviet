<form action="{USER_LOGIN}" method="post" data-toggle="userLogin" autocomplete="off" novalidate<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <input type="hidden" name="_csrf" value="{CSRF}" />
    <div class="nv-info margin-bottom" data-default="{GLANG.logininfo}">{GLANG.logininfo}</div>
    <div class="form-detail">
        <div class="form-group loginstep1">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.username_email}" value="" name="nv_login" maxlength="100" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.username_empty}">
            </div>
        </div>

        <div class="form-group loginstep1">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="100" data-pattern="/^(.){3,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
            </div>
        </div>

        <div class="form-group loginstep2 hidden">
            <label class="margin-bottom">{GLANG.2teplogin_totppin_label}</label>
            <div class="input-group margin-bottom">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_totppin_placeholder}" value="" name="nv_totppin" maxlength="6" data-pattern="/^(.){6,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.2teplogin_totppin_placeholder}">
            </div>
            <div class="text-center">
                <a href="#" data-toggle="login2step_change">{GLANG.2teplogin_other_menthod}</a>
            </div>
        </div>

        <div class="form-group loginstep3 hidden">
            <label class="margin-bottom">{GLANG.2teplogin_code_label}</label>
            <div class="input-group margin-bottom">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_code_placeholder}" value="" name="nv_backupcodepin" maxlength="8" data-pattern="/^(.){8,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.2teplogin_code_placeholder}">
            </div>
            <div class="text-center">
                <a href="#" data-toggle="login2step_change">{GLANG.2teplogin_other_menthod}</a>
            </div>
        </div>

        <div class="text-center margin-bottom-lg">
            <!-- BEGIN: header --><input name="nv_header" value="{NV_HEADER}" type="hidden" /><!-- END: header -->
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
            <button class="bsubmit btn btn-primary" type="submit">{GLANG.loginsubmit}</button>
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

        <!-- BEGIN: openid -->
        <!-- BEGIN: google_identity_onload -->
        <div class="margin-bottom-lg" style="display:flex;justify-content:center;">
            <div id="g_id_onload" data-client_id="{GOOGLE_CLIENT_ID}" data-context="signin" data-ux_mode="popup" data-callback="GIDHandleCredentialResponse" data-itp_support="true" data-url="{GOOGLE_IDENTITY_URL}" data-csrf="{CHECKSS}">
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