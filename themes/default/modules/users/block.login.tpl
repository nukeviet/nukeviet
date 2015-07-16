<!-- BEGIN: main -->
<div id="nv-block-login" class="text-center">
	<em class="fa fa-sign-in">&nbsp;</em> <a href="" class="login">{LANG.loginsubmit}</a> 
	<!-- BEGIN: allowuserreg -->
	<em class="fa fa-user-plus">&nbsp;</em> <a href="{USER_REGISTER}" class="register">{LANG.register}</a> 
	<!-- END: allowuserreg -->
</div>
<script type="text/javascript">
$.fn.user.defaults = $.extend({}, $.fn.user.defaults, {
	<!-- BEGIN: captcha -->
	isCaptcha: true,
	captchaW: {GFX_WIDTH},
	captchaH: {GFX_HEIGHT},
	captchaLen: {GFX_MAXLENGTH},
	<!-- END: captcha -->
	lostpassLink: '{USER_LOSTPASS}',
	lang: {
		close: '{LANG.cancel}',
		login: '{LANG.loginsubmit}',
		loginSubmit: '{LANG.loginsubmit}',
		securitycode: '{LANG.securitycode}',
		username: '{LANG.username}',
		password: '{LANG.password}',
		lostpass: '{LANG.lostpass}',
		openidLogin: '{LANG.openid_login}',
	}
});
<!-- BEGIN: openid -->
$.fn.user.defaults.isOpenID = true;
<!-- BEGIN: server -->
$.fn.user.defaults.openIDSV.push({
	title: '{OPENID.title}',
	href: '{OPENID.href}',
	imgSRC: '{OPENID.img_src}',
	imgW: '{OPENID.img_width}',
	imgH: '{OPENID.img_height}'
});
<!-- END: server -->
<!-- END: openid -->
</script>
<!-- END: main -->
<!-- BEGIN: signed -->
<div class="content signed clearfix">
	<p class="text-center">{LANG.wellcome}:
	    <strong>
	           {USER.full_name}
	    </strong>
	</p>
	<hr />
	<div class="row">
		<div class="col-xs-12 text-center">
			<a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail" /></a>
		</div>
		<div class="col-xs-12">
			<ul class="nv-list-item">
				<!-- BEGIN: admin --><li><a title="{LANG.logout}" href="{LOGOUT_ADMIN}">{LANG.logout}</a></li><!-- END: admin -->
				<li><a title="{LANG.changpass}" href="{CHANGE_PASS}">{LANG.changpass}</a></li>
				<li><a title="{LANG.edituser}" href="{CHANGE_INFO}">{LANG.edituser}</a></li>
				<li>{in_group}</li>
			</ul>
		</div>
	</div>
</div>
<!-- END: signed -->