<!-- BEGIN: main -->
<div id="users">
	<div class="page-header m-bottom">
		<h3>{LANG.login}</h3>
	</div>
	<form id="loginForm" action="{USER_LOGIN}" method="post" class="box-border-shadow content-box clearfix">
		<p>
			{LANG.login_info}
		</p>
		<div class="fl login">
			<div class="clearfix r2">
				<label> {LANG.account} </label>
				<input id="login_iavim" name="nv_login" value="{DATA.nv_login}" class="required input" />
			</div>
			<div class="clearfix r2">
				<label> {LANG.password} </label>
				<input type="password" id="password_iavim" name="nv_password" value="{DATA.nv_password}" class="required password input" />
			</div>
			<!-- BEGIN: captcha -->
			<div class="clearfix r2">
				<label> {LANG.captcha} </label>
				<img id="vimglogin" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimglogin','seccode_iavim');"/>
			</div>
			<div class="clearfix r2">
				<label> {LANG.retype_captcha} </label>
				<input name="nv_seccode" id="seccode_iavim" class="required" maxlength="{GFX_MAXLENGTH}" />
			</div>
			<!-- END: captcha -->
			<div class="clearfix r2">
				<input name="nv_redirect" value="{DATA.nv_redirect}" type="hidden" /><input type="submit" value="{LANG.login_submit}" class="button" />
				<br />
				<span class="small"><a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a> &nbsp; <a title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}</a></span>
			</div>
			<div class="clear"></div>
		</div>
		<!-- BEGIN: openid -->
		<div class="openid fr">
			<div class="box-border content-box acenter">
				<img style="margin-left:10px;vertical-align:middle;" alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
				<div style="margin-bottom:10px;">
					{DATA.openid_info}
				</div>
				<!-- BEGIN: server -->
				<a title="{OPENID.title}" href="{OPENID.href}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" />{OPENID.title}</a>
				<!-- END: server -->
			</div>
			<div class="clear"></div>
		</div>
		<!-- END: openid -->
	</form>
</div>
<!-- END: main -->