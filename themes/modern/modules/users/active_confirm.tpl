<!-- BEGIN: main -->
<div id="users">
	<h2 class="line padding_0" style="margin-bottom:5px">{LANG.openid_activate_account}</h2>
	<form id="loginForm" action="{OPENID_LOGIN}" method="post" class="register1 clearfix">
		<div>
			<img style="margin-left:10px;vertical-align:middle;" alt="{LANG.openid_activate_account}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
		</div>
		<div class="info padding_0" style="padding-bottom:10px">
			{LANG.login_info}
		</div>
		<div class="clearfix rows">
			<label> {LANG.password} </label>
			<input type="password" id="password_iavim" name="password" value="" class="required password" />
		</div>
		<!-- BEGIN: captcha -->
		<div class="clearfix rows">
			<label> {LANG.captcha} </label>
			<img id="vimglogin" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
			<img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimglogin','seccode_iavim');"/>
		</div>
		<div class="clearfix rows">
			<label> {LANG.retype_captcha} </label>
			<input name="nv_seccode" id="seccode_iavim" class="required" />
		</div>
		<!-- END: captcha -->
		<input name="openid_active_confirm" value="1" type="hidden" />
		<input type="submit" value="{LANG.login_submit}" class="submit" />
		<br />
		<br />
		<div>
			<div style="margin-bottom:10px;">
				{LANG.openid_login2}
			</div>
			<!-- BEGIN: server -->
			<a title="{OPENID.title}" href="{OPENID.href}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
			<!-- END: server -->
		</div>
		<br />
		<br />
		<div class="clearfix rows">
			<ul>
				<li>
					<a title="{LANG.st_login}" href="{USER_LOGIN}">{LANG.st_login}</a>
				</li>
				<li>
					<span>|</span>
					<a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a>
				</li>
			</ul>
		</div>
	</form>
</div>
<!-- END: main -->