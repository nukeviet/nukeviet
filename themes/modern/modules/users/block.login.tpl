<!-- BEGIN: main -->
<form action="{USER_LOGIN}" method="post">
	<div class="block-login">
		<div class="box clearfix">
			<input id="block_login_iavim" name="nv_login" type="text" class="input fl" onblur="if(this.value=='')this.value='{LANG.username} ';" onclick="if(this.value=='{LANG.username} ')this.value='';" value="{LANG.username} " /><input id="block_password_iavim" type="password" name="nv_password" value="" class="input fr" maxlength="{PASS_MAXLENGTH}" />
		</div>
		<!-- BEGIN: captcha -->
		<div class="box clearfix">
			<input id="block_seccode_iavim" name="nv_seccode" type="text" class="input fl" onblur="if(this.value=='')this.value='{LANG.securitycode}';" onclick="if(this.value=='{LANG.securitycode}')this.value='';" value="{LANG.securitycode}" />
			<img class="captcha fl" id="block_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" /><img src="{CAPTCHA_REFR_SRC}" class="refresh fl" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('block_vimg','block_seccode_iavim');"/>
		</div>
		<!-- END: captcha -->
		<div class="f-action">
			<input name="nv_redirect" value="{REDIRECT}" type="hidden" /><input type="submit" class="button" value="{LANG.loginsubmit}" /><a title="{LANG.lostpass}" href="{USER_LOSTPASS}">&nbsp; {LANG.lostpass}</a>
		</div>
		<!-- BEGIN: openid -->
		<div class="box openid clearfix">
			<!-- BEGIN: server -->
			<a class="forgot" title="{OPENID.title}" href="{OPENID.href}"><img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" />{OPENID.title}</a>
			<!-- END: server -->
		</div>
		<!-- END: openid -->
	</div>
</form>
<!-- END: main -->

<!-- BEGIN: signed -->
<div class="block-signed clearfix">
	<p>
		{LANG.wellcome}: <strong>{USER.full_name}</strong>
	</p>
	<a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="s-border fl" /></a>
	<!-- BEGIN: admin -->
	<a title="{LANG.logout}" href="{LOGOUT_ADMIN}">{LANG.logout}</a>
	<!-- END: admin -->
	<a title="{LANG.changpass}" href="{CHANGE_PASS}">{LANG.changpass}</a>
	<a title="{LANG.edituser}" href="{CHANGE_INFO}">{LANG.edituser}</a>
	{in_group}
	<div class="clear"></div>
</div>
<!-- END: signed -->