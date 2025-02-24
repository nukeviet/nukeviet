<!-- BEGIN: main -->
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
            <div class="panel-body">
                <p><strong>{LANG.pass_confirm}</strong></p>
                <form action="{FORM_ACTION}" method="post" role="form" data-toggle="confirm_pass_validForm" data-precheck="confirm_pass_precheck"
                    <!-- BEGIN: captcha -->data-captcha="nv_seccode"<!-- END: captcha -->
                    <!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha -->
                    <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->
                    <!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->
                >
                    <div class="nv-info margin-bottom" data-default="{LANG.pass_confirm_info}">{LANG.pass_confirm_info}</div>
                    <div class="form-detail">
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="ele_password" value="" autocomplete="off" placeholder="{LANG.password}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary">{GLANG.confirm}</button>
                            </div>
                        </div>
                        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}">
                        <input type="hidden" name="confirm_pass" value="1">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
