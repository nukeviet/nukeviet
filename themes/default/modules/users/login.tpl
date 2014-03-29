<!-- BEGIN: main -->
<h2>{LANG.login}</h2>
<form id="loginForm" action="{USER_LOGIN}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<p>
		<em class="fa fa-quote-left">&nbsp;</em>
		{LANG.login_info}
		<em class="fa fa-quote-right">&nbsp;</em>
	</p>
	<div class="form-group">
		<label for="login_iavim" class="col-sm-3 control-label">{LANG.account}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="text" id="login_iavim" name="nv_login" value="{DATA.nv_login}" class="required form-control" />
		</div>
	</div>
	<div class="form-group">
		<label for="password_iavim" class="col-sm-3 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<input type="password" id="password_iavim" name="nv_password" value="{DATA.nv_password}" class="required form-control password" />
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
			<input name="nv_redirect" value="{DATA.nv_redirect}" type="hidden" />
			<input type="submit" value="{LANG.login_submit}" class="btn btn-primary" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a> - <a title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}</a>
		</div>
	</div>
	<!-- BEGIN: openid -->
	<div class="text-center">
		<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
		<div class="m-bottom">
			{DATA.openid_info}
		</div>
		<!-- BEGIN: server -->
		<a href="{OPENID.href}" title="{OPENID.title}"> <img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/> </a> 
		<!-- END: server -->
	</div>
	<!-- END: openid -->
</form>
<!-- END: main -->