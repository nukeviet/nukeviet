<!-- BEGIN: main -->
<h2>{LANG.openid_login}</h2>
<p class="text-center">
	<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
</p>
<form id="loginForm" action="{USER_LOGIN}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<div class="m-bottom text-center">
		{DATA.login_info}
	</div>
	<div class="form-group">
		<label for="login_iavim" class="col-sm-3 control-label">{LANG.account}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="text" id="login_iavim" name="nv_login" value="{DATA.nv_login}" class="required form-control" maxlength="{NICK_MAXLENGTH}" />
		</div>
	</div>
	<div class="form-group">
		<label for="password_iavim" class="col-sm-3 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" id="password_iavim" name="nv_password" value="{DATA.nv_password}" class="required password form-control" maxlength="{PASS_MAXLENGTH}" />
		</div>
	</div>
	<!-- BEGIN: captcha -->
	<div class="form-group">
		<label for="seccode_iavim" class="col-sm-3 control-label">{LANG.captcha}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-4">
			<input type="text" name="nv_seccode" id="seccode_iavim" class="required form-control" maxlength="{GFX_MAXLENGTH}" />			
		</div>
		<div class="col-sm-5">
			<label class="control-label">
				<img id="vimglogin" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimglogin','seccode_iavim');">&nbsp;</em>
			</label>
		</div>
	</div>
	<!-- END: captcha -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input name="nv_redirect" value="{DATA.nv_redirect}" type="hidden" />
			<input type="submit" value="{LANG.login_submit}" class="btn btn-primary" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a> - <a title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}</a>
		</div>
	</div>
	<div class="text-center m-bottom">
		<!-- BEGIN: server -->
		<a href="{OPENID.href}">&nbsp;<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>&nbsp;</a>
		<!-- END: server -->
	</div>
</form>
<!-- END: main -->