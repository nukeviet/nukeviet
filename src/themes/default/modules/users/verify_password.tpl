<!-- BEGIN: main -->
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
            <div class="panel-body">
                <h2 class="text-center margin-bottom-lg">{LANG.verify_password_title}</h2>
                <form action="{DATA.form_action}" method="post" data-toggle="verify_password_validForm" data-precheck="verify_password_precheck"
                    <!-- BEGIN: captcha -->data-captcha="nv_seccode"<!-- END: captcha -->
                    <!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha -->
                    <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->
                    <!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->
                >
                    <input type="hidden" name="_csrf" value="{CHECKSS}">
                    <input type="hidden" name="nv_redirect" value="{DATA.redirect}">
                    <input type="hidden" name="area" value="{DATA.area}">
                    <div class="nv-info margin-bottom" data-default="{LANG.verify_password_note}.">{LANG.verify_password_note}.</div>
                    <div class="form-detail">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                                <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="100" data-pattern="/^(.){3,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                            </div>
                        </div>
                        <div class="text-center margin-bottom-lg">
                            <button class="bsubmit btn btn-primary" type="submit">{GLANG.verify}</button>
                            <button type="button" class="btn btn-default" data-toggle="validReset">{GLANG.reset}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
