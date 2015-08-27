<!-- BEGIN: main -->
<div class="page panel panel-default">
	<div class="panel-body">
		<div class="alert alert-info">
			<h2 class="text-center margin-bottom-lg">
				{LANG.mode_login_2}
			</h2>
			<form action="{OPENID_LOGIN}" method="post" role="form" class="form-horizontal margin-bottom-lg">
				<div class="row">
					<div class="nv-info margin-bottom">
						{LANG.openid_confirm_info}
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<em class="fa fa-key fa-lg fa-fix">
							</em>
						</span>
						<input type="password" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="100"/>
					</div>
				</div>
				<!-- BEGIN: captcha -->
				<div class="form-group">
					<div class="middle text-center clearfix">
						<img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" />
						<em class="fa fa-pointer fa-refresh margin-left margin-right" title="{GLANG.captcharefresh}" onclick="change_captcha('.bsec');">
						</em>
						<input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}"/>
					</div>
				</div>
				<!-- END: captcha -->
				<div class="text-center margin-bottom-lg">
					<input name="openid_account_confirm" value="1" type="hidden" />
					<input type="reset" value="{GLANG.reset}" class="btn btn-default"/>
					<button class="bsubmit btn btn-primary" type="submit">
						{GLANG.loginsubmit}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END: main -->