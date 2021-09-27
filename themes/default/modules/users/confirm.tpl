<!-- BEGIN: main -->
<div class="page panel panel-default">
    <div class="panel-body">
        <div class="alert alert-info">
            <h2 class="text-center margin-bottom-lg">
                {LANG.mode_login_2}
            </h2>
            <form action="{OPENID_LOGIN}" method="post" role="form" class="form-horizontal margin-bottom-lg"<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
                <div class="row">
                    <div class="nv-info margin-bottom">
                        {LANG.openid_confirm_info}
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
                    <input name="openid_account_confirm" value="1" type="hidden" />
                    <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
                    <input type="reset" value="{GLANG.reset}" class="btn btn-default" />
                    <input class="bsubmit btn btn-primary" type="submit" value="{GLANG.loginsubmit}"/>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: main -->