<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <div class="alert alert-info">
            <h2 class="text-center margin-bottom-lg">
                {LANG.mode_login_2}
            </h2>
            <div class="row">
                <div class="nv-info margin-bottom-lg">
                    {INFO}
                </div>
                <!-- BEGIN: allowuserreg -->
                <form action="{USER_LOGIN}" method="post" role="form" class="form-horizontal text-center margin-top-lg margin-bottom-lg">
                    <span class="btn btn-primary btn-sm margin-right-lg pointer" data-toggle="loginFormShow">
                        {LANG.openid_note5}
                    </span>
                    <input type="hidden" name="nv_reg" value="1" />
                    <!-- BEGIN: redirect2 --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect2 -->
                    <input type="submit" class="btn btn-danger btn-sm" value="{LANG.openid_note4}">
                </form>
                <!-- END: allowuserreg -->
            </div>
            <form id="loginForm" action="{USER_LOGIN}" method="post" role="form" class="form-horizontal margin-bottom-lg<!-- BEGIN: allowuserreg2 --> hidden<!-- END: allowuserreg2 -->"<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <em class="fa fa-user fa-lg">
                            </em>
                        </span>
                        <input type="text" class="required form-control" placeholder="{GLANG.username}" value="" name="login" maxlength="100" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <em class="fa fa-key fa-lg fa-fix">
                            </em>
                        </span>
                        <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="100" />
                    </div>
                </div>
                <div class="text-center margin-bottom-lg">
                    <input name="nv_login" value="1" type="hidden" />
                    <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
                    <input type="reset" value="{GLANG.reset}" class="btn btn-default" />
                    <button class="bsubmit btn btn-primary" type="submit">
                        {GLANG.loginsubmit}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function() {
    $('[data-toggle=loginFormShow]').on('click', function(e) {
        e.preventDefault();
        $('#loginForm').toggleClass('hidden')
    })
})
</script>
<!-- END: main -->