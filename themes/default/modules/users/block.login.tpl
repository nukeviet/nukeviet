<!-- BEGIN: main -->
<div id="nv-block-login" class="text-center">
	<em class="fa fa-sign-in">&nbsp;</em> <a href="{USER_LOGIN}" class="login">{LANG.loginsubmit}</a> 
	<!-- BEGIN: allowuserreg -->
	<em class="fa fa-user-plus">&nbsp;</em> <a href="{USER_REGISTER}" class="register">{LANG.register}</a> 
	<!-- END: allowuserreg -->
</div>
<script type="text/javascript">
$.fn.user.defaults = $.extend({}, $.fn.user.defaults, {
	captchaW: {GFX_WIDTH},
	captchaH: {GFX_HEIGHT},
	captchaLen: {GFX_MAXLENGTH},
	timeStamp: {NV_CURRENTTIME},
	checkss: '{CHECKSESS}',
	<!-- BEGIN: captcha_login -->isCaptchaLogin: true,<!-- END: captcha_login -->
	<!-- BEGIN: captcha_reg -->isCaptchaReg: true,<!-- END: captcha_reg -->
	<!-- BEGIN: allowuserreg1 -->allowreg: true,<!-- END: allowuserreg1 -->
	lostpassLink: '{USER_LOSTPASS}',
});
$.fn.user.defaults.lang = $.extend({}, $.fn.user.defaults.lang, {
	close: '{LANG.cancel}',
	login: '{LANG.loginsubmit}',
	loginSubmit: '{LANG.loginsubmit}',
	securitycode: '{LANG.securitycode}',
	username: '{LANG.username}',
	password: '{LANG.password}',
	lostpass: '{LANG.lostpass}',
	<!-- BEGIN: allowuserreg2 -->
	register: '{LANG.register}',
	firstName: '{LANG.first_name}',
	lastName: '{LANG.last_name}',
	email: '{LANG.email}',
	account: '{LANG.username}',
	rePassword: '{LANG.password2}',
	question: '{LANG.question}',
	yourQuestion: '{LANG.your_question}',
	answerYourQuestion: '{LANG.answer_your_question}',
	inGroup: '{LANG.in_group}',
	usageTerms: '{LANG.usage_terms}',
	captcha: '{LANG.securitycode}',
	accept: '{LANG.accept}',
	<!-- END: allowuserreg2 -->
	openidLogin: '{LANG.openid_login}'
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
		<div class="col-xs-8 text-center">
			<a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail" /></a>
		</div>
		<div class="col-xs-16">
		    <ul class="nv-list-item sm">
		    	<li class="active"><a href="{URL_MODULE}">{LANG.user_info}</a></li>
		    	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
		    	<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
		    	<li><a href="{URL_HREF}changequestion">{LANG.question2}</a></li>
		    	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
		    	<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
		    	<li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li>
		    </ul>
		</div>
	</div>
</div>
<!-- END: signed -->