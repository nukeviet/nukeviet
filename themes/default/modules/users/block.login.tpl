<!-- BEGIN: main -->
<form action="{USER_LOGIN}" method="post" role="form" class="form-tooltip">
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><em class="fa fa-user fa-lg">&nbsp;</em></span>
			<input type="text" class="form-control" id="block_login_iavim" name="nv_login" value="" placeholder="{LANG.username}">
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix">&nbsp;</em></span>
			<input type="password" class="form-control" id="block_password_iavim" name="nv_password" value="" placeholder="{LANG.password}">
		</div>
	</div>
	<!-- BEGIN: captcha -->
	<div class="form-group text-right">
		<img id="block_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" />
		&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('block_vimg','block_seccode_iavim');">&nbsp;</em>
	</div>
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><em class="fa fa-shield fa-lg fa-fix">&nbsp;</em></span>
			<input id="block_seccode_iavim" name="nv_seccode" type="text" class="form-control" maxlength="{GFX_MAXLENGTH}" placeholder="{LANG.securitycode}"/>
		</div>
	</div>
	<!-- END: captcha -->
	<div class="form-group">
		<input name="nv_redirect" value="{REDIRECT}" type="hidden" />
		<input type="submit" value="{LANG.loginsubmit}" class="btn btn-primary"/>
		<a class="pull-right" title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}?</a>
	</div>
	<!-- BEGIN: openid -->
	<hr />
	<p class="text-center">
		<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" /> {LANG.openid_login} 
	</p>
	<div class="text-center">
		<!-- BEGIN: server -->
		<a title="{OPENID.title}" href="{OPENID.href}">
	 		&nbsp;<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>&nbsp;
		</a>
		<!-- END: server -->
	</div>
	<!-- END: openid -->
</form>
<!-- END: main -->
<!-- BEGIN: signed -->
<div class="content signed clearfix">
	<p class="text-center">{LANG.wellcome}: <strong>{USER.full_name}</strong></p>
	<hr />
	<div class="row">
		<div class="col-xs-6 text-center">
			<a title="{LANG.edituser}" href="{CHANGE_INFO}"><img src="{AVATA}" alt="{USER.full_name}" class="img-thumbnail" /></a>
		</div>
		<div class="col-xs-6">
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