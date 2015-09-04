<form action="{USER_LOGIN}" method="post" onsubmit="return login_validForm(this);" autocomplete="off" novalidate>
    <div class="nv-info margin-bottom" data-default="{GLANG.logininfo}">{GLANG.logininfo}</div>
    <div class="form-detail">
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                <input type="text" class="required form-control" placeholder="{GLANG.username}" value="" name="nv_login" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.username_empty}">
            </div>
        </div>
        
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                <input type="password" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="100" data-pattern="/^(.){3,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
            </div>
        </div>
        
        <!-- BEGIN: captcha -->
        <div class="form-group">
            <div class="middle text-center clearfix">
                <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.bsec');"></em><input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
            </div>
        </div>
        <!-- END: captcha -->
        
        <div class="text-center margin-bottom-lg">
            <!-- BEGIN: header --><input name="nv_header" value="{NV_HEADER}" type="hidden" /><!-- END: header -->
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
            <button class="bsubmit btn btn-primary" type="submit">{GLANG.loginsubmit}</button>
       	</div>

        <!-- BEGIN: openid -->
       	<hr />
       	<div class="text-center">
      		<!-- BEGIN: server -->
      		<a title="{OPENID.title}" href="{OPENID.href}" class="openid margin-right" onclick="return openID_load(this);"><img alt="{OPENID.title}" title="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /></a>
      		<!-- END: server -->
       	</div>
       	<!-- END: openid -->
    </div>
</form>