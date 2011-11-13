<!-- BEGIN: main -->
<form action="{USER_LOGIN}" method="post" class="login clearfix">
    <fieldset>
        <p>
            <label for="block_login_iavim">
                {LANG.username} 
            </label>
            <input id="block_login_iavim" name="nv_login" value="" type="text" class="txt" maxlength="{NICK_MAXLENGTH}" />
        </p>
        <p>
            <label for="block_password_iavim">
                {LANG.password} 
            </label>
            <input id="block_password_iavim" type="password" name="nv_password" value="" class="txt" maxlength="{PASS_MAXLENGTH}" />
        </p><!-- BEGIN: captcha -->
        <p>
            <label for="block_vimg">
                {LANG.securitycode} 
            </label>
            <img id="block_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('block_vimg','block_seccode_iavim');"/>
            <label for="block_seccode_iavim">
                {LANG.securitycode} 
            </label>
            <input id="block_seccode_iavim" name="nv_seccode" type="text" class="txt" maxlength="{GFX_MAXLENGTH}" />
        </p><!-- END: captcha -->
        <div style="padding-top: 10px;" class="clearfix">
            <div class="submit">
                <input name="nv_redirect" value="{REDIRECT}" type="hidden" />
                <input type="submit" value="{LANG.loginsubmit}" />
            </div><a class="forgot fl" title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}?</a>
        </div>
        <!-- BEGIN: openid -->
        <div style="padding-top:10px;">
            <label>
                <img style="margin-right:3px;vertical-align:middle;" alt="{LANG.openid_login}" title="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" /> {LANG.openid_login} 
            </label>
            <!-- BEGIN: server -->
            <a class="forgot fl" title="{OPENID.title}" href="{OPENID.href}"><img style="margin-right:3px;vertical-align:middle;" alt="{OPENID.title}" title="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
            <!-- END: server -->
        </div>
        <!-- END: openid -->
    </fieldset>
</form>
<!-- END: main -->
<!-- BEGIN: signed -->
<div class="content signed clearfix">
    <p>
        {LANG.wellcome}: <strong>{USER.full_name}</strong>
    </p>
    <a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="fl" /></a>
    <!-- BEGIN: admin -->
    	<a title="{LANG.logout}" href="{LOGOUT_ADMIN}">{LANG.logout}</a>
    <!-- END: admin -->
    <a title="{LANG.changpass}" href="{CHANGE_PASS}">{LANG.changpass}</a>
    <a title="{LANG.edituser}" href="{CHANGE_INFO}">{LANG.edituser}</a>
    {in_group} 
</div>
<!-- END: signed -->
