<!-- BEGIN: main -->
<div class="page panel panel-default">
	<div class="panel-body">
		<div class="alert alert-info">
			<h2 class="text-center margin-bottom-lg">
				{LANG.mode_login_2}
			</h2>
			<div class="row">
				<div class="nv-info margin-bottom-lg">
					{INFO}
				</div>
				<!-- BEGIN: allowuserreg -->
				<form action="{USER_LOGIN}" method="post" role="form" class="form-horizontal text-center margin-top-lg margin-bottom-lg">
					<span class="btn btn-primary btn-sm margin-right-lg pointer" onclick="$('#loginForm').show();return!1">
						{LANG.openid_note5}
					</span>
					<input type="hidden" name="nv_reg" value="1" />
                    <!-- BEGIN: redirect2 --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect2 -->
					<input type="submit" class="btn btn-danger btn-sm" value="{LANG.openid_note4}">
				</form>
				<!-- END: allowuserreg -->
			</div>
			<form id="loginForm" action="{USER_LOGIN}" method="post" role="form" class="form-horizontal margin-bottom-lg"<!-- BEGIN: allowuserreg2 --> style="display:none"<!-- END: allowuserreg2 -->>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<em class="fa fa-user fa-lg">
							</em>
						</span>
						<input type="text" class="required form-control" placeholder="{GLANG.username}" value="" name="login" maxlength="100"/>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">
							<em class="fa fa-key fa-lg fa-fix">
							</em>
						</span>
						<input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="100"/>
					</div>
				</div>
				<!-- BEGIN: captcha -->
				<div class="form-group">
					<div class="middle text-center clearfix">
						<img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{GLANG.securitycode}" title="{GLANG.securitycode}" />
						<em class="fa fa-pointer fa-refresh margin-left margin-right" title="{GLANG.captcharefresh}" onclick="change_captcha('.bsec');">
						</em>
						<input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}"/>
					</div>
				</div>
				<!-- END: captcha -->
                <!-- BEGIN: recaptcha -->
                <div class="form-group">
                    <div class="middle text-center clearfix">
                        <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                        <script type="text/javascript">
                        nv_recaptcha_elements.push({
                            id: "{RECAPTCHA_ELEMENT}",
                            btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
                        })
                        </script>
                    </div>
                </div>
                <!-- END: recaptcha -->
				<div class="text-center margin-bottom-lg">
					<input name="nv_login" value="1" type="hidden" />
                    <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
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