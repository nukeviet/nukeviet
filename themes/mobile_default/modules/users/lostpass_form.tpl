<form action="{FORM_ACTION}" method="post" onsubmit="return lostpass_validForm(this);" autocomplete="off" novalidate>
    <div class="nv-info margin-bottom" data-default="{LANG.lostpass_info1}">{LANG.lostpass_info1}</div>
    <div class="form-detail">
        <div class="step1">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                    <input type="text" class="required form-control" placeholder="{GLANG.username_email}" value="" name="userField" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.lostpass_no_info1}">
                </div>
            </div>
            
            <!-- BEGIN: captcha -->
            <div class="form-group">
                <div class="middle text-right clearfix">
                    <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.bsec');"></em><input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
                </div>
            </div>
            <!-- END: captcha -->
            
            <!-- BEGIN: recaptcha -->
            <div class="form-group">
                <div class="middle text-right clearfix">
                    <div class="nv-recaptcha-default">
                        <div id="{RECAPTCHA_ELEMENT}"></div>
                        <input type="hidden" value="" name="gcaptcha_session"/>
                    </div>
                    <script type="text/javascript">
                    nv_recaptcha_elements.push({
                        id: "{RECAPTCHA_ELEMENT}",
                        btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent().parent())
                    })
                    </script>
                </div>
            </div>
            <!-- END: recaptcha -->
        </div>
        
        <div class="step2" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-pencil-square-o fa-lg"></em></span>
                    <input type="text" class="form-control" placeholder="{LANG.answer_question}" value="" name="answer" maxlength="255" onkeypress="validErrorHidden(this);" data-mess="{LANG.answer_empty}">
                </div>
            </div>
        </div>
        
        <div class="step3" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                    <input type="text" class="form-control" placeholder="{LANG.lostpass_key}" value="" name="verifykey" maxlength="10" data-pattern="/^[a-zA-Z0-9]{10,10}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.lostpass_active_error}">
                </div>
            </div>
        </div>
        
        <div class="step4" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" autocomplete="off" class="form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" autocomplete="off" class="form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.passwordsincorrect}">
                </div>
            </div>
        </div>
        
        <div class="text-center margin-bottom-lg">
             <input type="hidden" name="step" value="step1" />
             <input type="hidden" name="checkss" value="{DATA.checkss}" />
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <button class="bsubmit btn btn-primary" type="submit">{LANG.lostpass_submit}</button>
       	</div>
    </div>
</form>