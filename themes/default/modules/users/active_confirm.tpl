<!-- BEGIN: main -->
<h2>{LANG.openid_activate_account}</h2>
<form id="loginForm" action="{OPENID_LOGIN}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<p class="text-center">
		<img alt="{LANG.openid_activate_account}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
	</p>
	<div class="well">{LANG.login_info}</div>
	<div class="form-group">
		<label for="password_iavim" class="col-sm-3 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" id="password_iavim" name="password" value="" class="required password form-control" />
		</div>
	</div>
	<!-- BEGIN: captcha -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<img id="vimglogin" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
			&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimglogin','seccode_iavim');">&nbsp;</em>
		</div>
	</div>
	<div class="form-group">
		<label for="seccode_iavim" class="col-sm-3 control-label">{LANG.retype_captcha}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="text" name="nv_seccode" id="seccode_iavim" class="required form-control" maxlength="{GFX_MAXLENGTH}" />
		</div>
	</div>
	<!-- END: captcha -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input name="openid_active_confirm" value="1" type="hidden" />
			<input type="submit" value="{LANG.login_submit}" class="btn btn-primary" />
		</div>
	</div>
	<hr />
	<div class="m-bottom text-center">
		<p>{LANG.openid_login2}</p>
		<!-- BEGIN: server -->
		<a href="{OPENID.href}" title="{OPENID.title}">&nbsp;<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}"  data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>&nbsp;</a>
		<!-- END: server -->
	</div>
	<hr />
	<p class="text-center">
		<a title="{LANG.st_login}" href="{USER_LOGIN}">{LANG.st_login}</a> - <a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a>
	</p>
</form>
<!-- END: main -->