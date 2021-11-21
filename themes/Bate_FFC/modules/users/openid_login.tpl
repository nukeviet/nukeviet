<!-- BEGIN: main -->
<div class="page" style="margin:20px !important">
    <h2 class="text-center margin-bottom-lg">
        {LANG.mode_login_2}
    </h2>
    <div class="text-center">
        <div class="margin-bottom-lg">
            {INFO}
        </div>
        <!-- BEGIN: choose_action -->
        <div class="margin-bottom-lg">
            <select class="form-control" data-toggle="choose_action">
                <!-- BEGIN: option -->
                <option value="{ACTION.key}">{ACTION.name}</option>
                <!-- END: option -->
            </select>
        </div>
        <!-- END: choose_action -->
    </div>
    <!-- BEGIN: allowuserreg -->
    <div id="regForm" class="panel panel-default<!-- BEGIN: isHide --> hidden<!-- END: isHide -->">
        <form action="{USER_LOGIN}" method="post" role="form" class="form-horizontal panel-body" autocomplete="off" novalidate>
            <input type="email" name="email_hidden" class="hidden" />
            <input type="password" name="password_hidden" class="hidden" />

            <div class="form-group">
                <label for="reg_username" class="col-xs-8 control-label">{GLANG.username}</label>
                <div class="col-xs-16">
                    <input type="text" class="required form-control" value="{USER_NAME}" name="reg_username" id="reg_username" maxlength="50" data-mess="{GLANG.username_empty}" />
                </div>
            </div>
            <div class="form-group">
                <label for="reg_email" class="col-xs-8 control-label">{GLANG.email}</label>
                <div class="col-xs-16">
                    <input type="text" class="required form-control" value="{USER_EMAIL}" name="reg_email" id="reg_email" maxlength="100" data-mess="{GLANG.email_incorrect}" <!-- BEGIN: readonly --> readonly
                    <!-- END: readonly -->/>
                </div>
            </div>
            <!-- BEGIN: email_verify -->
            <div class="form-group">
                <div class="col-xs-24 text-right">
                    <button class="btn btn-default" type="button" data-toggle="verifykey_send">{LANG.verifykey_send}</button>
                </div>
            </div>
            <div class="form-group">
                <label for="verify_code" class="col-xs-8 control-label">{LANG.verifykey}</label>
                <div class="col-xs-16">
                    <input type="text" class="required form-control" value="" name="verify_code" id="verify_code" maxlength="8" data-mess="{LANG.verifykey_empty}" />
                </div>
            </div>
            <!-- END: email_verify -->
            <div class="form-group">
                <label for="reg_password" class="col-xs-8 control-label">{GLANG.password}</label>
                <div class="col-xs-16">
                    <input type="password" autocomplete="off" class="required form-control" value="" name="reg_password" id="reg_password" maxlength="{PASS_MAXLENGTH}" data-minlength="{PASS_MINLENGTH}" data-mess="{GLANG.password_empty}" />
                </div>
            </div>
            <div class="form-group">
                <label for="reg_repassword" class="col-xs-8 control-label">{GLANG.password2}</label>
                <div class="col-xs-16">
                    <input type="password" autocomplete="off" class="required form-control" value="" name="reg_repassword" id="reg_repassword" maxlength="{PASS_MAXLENGTH}" data-minlength="{PASS_MINLENGTH}" data-mess="{GLANG.passwordsincorrect}" />
                </div>
            </div>
            <input type="hidden" name="nv_reg" value="1" />
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <input type="submit" class="btn btn-primary btn-sm" value="{GLANG.register}">
            <input type="reset" value="{GLANG.reset}" class="btn btn-default" />
        </form>
    </div>
    <!-- END: allowuserreg -->
    <!-- BEGIN: userlogin -->
    <div id="loginForm" class="panel panel-default<!-- BEGIN: isHide --> hidden<!-- END: isHide -->">
        <form action="{USER_LOGIN}" method="post" role="form" class="form-horizontal panel-body" autocomplete="off" novalidate>
            <div class="form-group">
                <label for="login" class="col-xs-8 control-label">{GLANG.username}</label>
                <div class="col-xs-16">
                    <input type="text" class="required form-control" value="" name="login" id="login" maxlength="100" />
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-xs-8 control-label">{GLANG.password}</label>
                <div class="col-xs-16">
                    <input type="password" autocomplete="off" class="required form-control" value="" name="password" id="password" maxlength="100" />
                </div>
            </div>
            <div class="text-center">
                <input name="nv_login" value="1" type="hidden" />
                <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
                <button class="bsubmit btn btn-primary" type="submit">
                    {GLANG.loginsubmit}
                </button>
                <input type="reset" value="{GLANG.reset}" class="btn btn-default" />
            </div>
        </form>
    </div>
    <!-- END: userlogin -->
    <!-- BEGIN: auto -->
    <div id="autoForm">
        <form action="{USER_LOGIN}" method="post" role="form">
            <input name="nv_auto" value="1" type="hidden" />
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
        </form>
    </div>
    <!-- END: auto -->
</div>
<script>
    $(function() {
        $('[data-toggle=choose_action]').on('change', function(e) {
            e.preventDefault();
            if ($(this).val() == 'connect') {
                $('#regForm').addClass('hidden');
                $('#loginForm').removeClass('hidden')
            } else if ($(this).val() == 'create') {
                $('#loginForm').addClass('hidden');
                $('#regForm').removeClass('hidden')
            } else if ($(this).val() == 'auto') {
                $('#loginForm, #regForm').addClass('hidden');
                $('#autoForm form').submit()
            }
        });
        $('[data-toggle=verifykey_send]').on('click', function(e) {
            e.preventDefault();
            var form = $(this).parents('form'),
                reg_email = $('[name=reg_email]', form).val();
            if (!nv_mailfilter.test(reg_email)) {
                alert($('[name=reg_email]', form).data('mess'));
                $('[name=reg_email]', form).focus();
                return !1
            }
            $.ajax({
                type: 'POST',
                cache: !1,
                url: form.attr('action'),
                data: {'verify_send':1,'reg_email':reg_email},
                dataType: "json",
                success: function(b) {
                    alert(b.mess);
                    $('[name=verify_code]', form).focus();
                }
            })
        });
        $('#regForm form').on('submit', function() {
            var reg_username = $('[name=reg_username]', this).val()
            reg_email = $('[name=reg_email]', this).val(),
                reg_password = $('[name=reg_password]', this).val(),
                reg_repassword = $('[name=reg_repassword]', this).val(),
                pass_min = $('[name=reg_password]', this).data('minlength'),
                pass_max = $('[name=reg_password]', this).attr('maxlength');
            if (!required_uname_check(reg_username)) {
                alert($('[name=reg_username]', this).data('mess'));
                $('[name=reg_username]', this).focus();
                return !1
            }
            if (!nv_mailfilter.test(reg_email)) {
                alert($('[name=reg_email]', this).data('mess'));
                $('[name=reg_email]', this).focus();
                return !1
            }
            if ($('[name=verify_code]', this).length) {
                var verify_code = $('[name=verify_code]', this).val();
                if (verify_code.length != 8) {
                    alert($('[name=verify_code]', this).data('mess'));
                    $('[name=verify_code]', this).focus();
                    return !1
                }
            }
            if (reg_password.length < pass_min || reg_password.length > pass_max) {
                alert($('[name=reg_password]', this).data('mess'));
                $('[name=reg_password]', this).focus();
                return !1
            }
            if (reg_repassword != reg_password) {
                alert($('[name=reg_repassword]', this).data('mess'));
                $('[name=reg_repassword]', this).focus();
                return !1
            }
        })
    })
</script>
<!-- END: main -->