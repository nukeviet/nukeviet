<!-- BEGIN: main -->
<div id="users">
	<h2 class="line padding_0" style="margin-bottom:5px">{LANG.openid_login}</h2>
	<div style="text-align:center">
		<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
		<br />
	</div>
	<div style="margin-top:10px">
		<strong>{LANG.openid_note2}:</strong>
	</div>
	<ol style="padding-top:10px">
		<!-- BEGIN: login_note -->
		<li>
			<span>- </span><a href="{USER_LOGIN.link}">{USER_LOGIN.title}</a>
		</li>
		<!-- END: login_note -->
	</ol>
	<div style="margin-top:30px;text-align:center">
		<!-- BEGIN: server -->
		<a title="{OPENID.title}" href="{OPENID.href}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
		<!-- END: server -->
	</div>
</div>
<!-- END: main -->