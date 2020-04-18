<form action="{USER_LOGIN}" method="post" onsubmit="return login_validForm(this);" autocomplete="off" novalidate>
    <div class="nv-info margin-bottom" data-default="{GLANG.logininfo}">{GLANG.logininfo}</div>
    <div class="form-detail">
        <div class="form-group loginstep1">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.username_email}" value="" name="nv_login" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.username_empty}">
            </div>
        </div>

        <div class="form-group loginstep1">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
            </div>
        </div>

        <div class="form-group loginstep2 hidden">
            <label class="margin-bottom">{GLANG.2teplogin_totppin_label}</label>
            <div class="input-group margin-bottom">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_totppin_placeholder}" value="" name="nv_totppin" maxlength="6" data-pattern="/^(.){6,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.2teplogin_totppin_placeholder}">
            </div>
            <div class="text-center">
                <a href="#" onclick="login2step_change(this);">{GLANG.2teplogin_other_menthod}</a>
            </div>
        </div>

        <div class="form-group loginstep3 hidden">
            <label class="margin-bottom">{GLANG.2teplogin_code_label}</label>
            <div class="input-group margin-bottom">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.2teplogin_code_placeholder}" value="" name="nv_backupcodepin" maxlength="8" data-pattern="/^(.){8,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.2teplogin_code_placeholder}">
            </div>
            <div class="text-center">
                <a href="#" onclick="login2step_change(this);">{GLANG.2teplogin_other_menthod}</a>
            </div>
        </div>

        <!-- BEGIN: captcha -->
        <div class="form-group loginCaptcha">
            <div class="middle text-center clearfix">
                <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.bsec');"></em><input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
            </div>
        </div>
        <!-- END: captcha -->

        <!-- BEGIN: recaptcha -->
        <div class="form-group loginCaptcha">
            <div class="middle text-center clearfix">
                <!-- BEGIN: default --><div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha"></div></div><!-- END: default -->
                <!-- BEGIN: compact --><div class="nv-recaptcha-compact"><div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha"></div></div><!-- END: compact -->
                <script type="text/javascript">
                nv_recaptcha_elements.push({
                    id: "{RECAPTCHA_ELEMENT}",
                    <!-- BEGIN: smallbtn -->size: "compact",<!-- END: smallbtn -->
                    btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent()),
                    pnum: 4,
                    btnselector: '[type="submit"]'
                });
                </script>
            </div>
        </div>
        <!-- END: recaptcha -->

        <div class="text-center margin-bottom-lg">
            <!-- BEGIN: header --><input name="nv_header" value="{NV_HEADER}" type="hidden" /><!-- END: header -->
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{NV_REDIRECT}" type="hidden" /><!-- END: redirect -->
            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
            <button class="bsubmit btn btn-primary" type="submit">{GLANG.loginsubmit}</button>
       	</div>

        <!-- BEGIN: allowuserreg2_form -->
        <div class="form-group">
            <div class="text-right clearfix">
                <a href="#" onclick="modalShowByObj('#guestReg_{BLOCKID}', 'recaptchareset')">{GLANG.register}</a>
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
       	<div class="text-center openid-btns">
      		<!-- BEGIN: server -->
            <div class="btn-group m-bottom btn-group-justified">
                <button class="btn openid-{OPENID.server} disabled" type="button" tabindex="-1"><i class="fa fa-fw fa-{OPENID.icon}"></i></button>
                <a class="btn openid-{OPENID.server}" href="{OPENID.href}" onclick="return openID_load(this);">{LANG.login_with} {OPENID.title}</a>
            </div>
            <!-- END: server -->
       	</div>
       	<!-- END: openid -->
    </div>
</form>