<form action="{FORM_ACTION}" method="post" data-toggle="lostPass" autocomplete="off" novalidate<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 --><!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->>
    <div class="nv-info margin-bottom" data-default="{LANG.lostpass_info1}">{LANG.lostpass_info1}</div>
    <div class="form-detail">
        <div class="step1">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                    <input type="text" class="required form-control" placeholder="{LANG.username_or_email}" value="" name="userField" maxlength="100" data-pattern="/^(.){3,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.lostpass_no_info1}">
                </div>
            </div>
        </div>

        <div class="step2" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-pencil-square-o fa-lg"></em></span>
                    <input type="text" class="form-control" placeholder="{LANG.answer_question}" value="" name="answer" maxlength="255" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.answer_empty}">
                </div>
            </div>
        </div>

        <div class="step3" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                    <input type="text" class="form-control" placeholder="{LANG.lostpass_key}" value="" name="verifykey" maxlength="10" data-pattern="/^[a-zA-Z0-9]{10,10}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.lostpass_active_error}">
                </div>
            </div>
        </div>

        <div class="step4" style="display:none">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" autocomplete="off" class="form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="{PASS_MAXLENGTH}" data-pattern="{PASSWORD_PATTERN}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{PASSWORD_RULE}">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                    <input type="password" autocomplete="off" class="form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.re_password_empty}">
                </div>
            </div>
        </div>

        <div class="text-center margin-bottom-lg">
             <input type="hidden" name="step" value="step1" />
             <input type="hidden" name="checkss" value="{DATA.checkss}" />
             <input type="hidden" name="gcaptcha_session" value="" />
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <button class="bsubmit btn btn-primary" type="submit">{LANG.lostpass_submit}</button>
       	</div>
    </div>
</form>
