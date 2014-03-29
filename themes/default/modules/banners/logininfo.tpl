<!-- BEGIN: logininfo -->
<div id="client_login">
	<form role="form" action="#" method="post" class="form-horizontal" onsubmit="return false;">
		<div class="form-group">
			<label for="{LOGIN_INPUT_NAME}" class="col-sm-3 control-label">{LOGIN_LANG}:</label>
			<div class="col-sm-9">
				<input name="{LOGIN_INPUT_NAME}" id="{LOGIN_INPUT_NAME}" type="text" maxlength="{LOGIN_INPUT_MAXLENGTH}" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="{PASS_INPUT_NAME}" class="col-sm-3 control-label">{PASSWORD_LANG}:</label>
			<div class="col-sm-9">
				<input name="{PASS_INPUT_NAME}" id="{PASS_INPUT_NAME}" type="password" maxlength="{PASS_INPUT_MAXLENGTH}" class="form-control"/>
			</div>
		</div>
		<!-- BEGIN: captcha -->
		<div class="form-group">
			<label for="{CAPTCHA_NAME}" class="col-sm-3 control-label">{CAPTCHA_LANG}:</label>
			<div class="col-sm-4">
				<img id="vimg" alt="{CAPTCHA_LANG}"src="{CAPTCHA_IMG}" />
				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimg','{CAPTCHA_NAME}');">&nbsp;</em>
			</div>
			<div class="col-sm-5">
				<input name="{CAPTCHA_NAME}" id="{CAPTCHA_NAME}" type="password" maxlength="{CAPTCHA_MAXLENGTH}" class="form-control"/>
			</div>
		</div>
		<!-- END: captcha -->
	</form>
	<div class="text-center">
		<input type="button" value="{SUBMIT_LANG}" name="{SM_BUTTON_NAME}" id="{SM_BUTTON_NAME}" onclick="{SM_BUTTON_ONCLICK}" class="btn btn-primary"/>
	</div>
</div>
<p>
	{CLIENT_LOGIN_INFO}.
</p>
<!-- END: logininfo -->