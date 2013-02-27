<!-- BEGIN: main -->
<div id="users">
	<div class="page-header">
		<h3>{LANG.user_info}</h3>
	</div>
	<ul class="list-tab top-option clearfix">
		<li>
			<a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
		</li>
		<li>
			<a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
		</li>
		<li>
			<a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
		</li>
		<!-- BEGIN: allowopenid -->
		<li>
			<a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
		</li>
		<!-- END: allowopenid -->
		<!-- BEGIN: regroups -->
		<li>
			<a href="{URL_HREF}regroups">{LANG.in_group}</a>
		</li>
		<!-- END: regroups -->
		<!-- BEGIN: logout -->
		<li>
			<a href="{URL_HREF}logout">{LANG.logout_title}</a>
		</li>
		<!-- END: logout -->
	</ul>
	<div class="table box-border-shadow">
		<div class="content-box h-info">
			<div class="left fl">
				<img src="{SRC_IMG}" alt="" class="s-border" />
			</div>
			<div class="fl">
				{LANG.account2}: <strong>{USER.username}</strong> ({USER.email})
				<br />
				{USER.current_mode}
				<br />
				{LANG.current_login}: {USER.current_login}
				<br />
				{LANG.ip}: {USER.current_ip}
			</div>
			<div class="clear"></div>
		</div>
		<!-- BEGIN: change_login_note -->
		<p>
			<strong>&raquo; {USER.change_name_info}</strong>
		</p>
		<!-- END: change_login_note -->
		<!-- BEGIN: pass_empty_note -->
		<p>
			<strong>&raquo; {USER.pass_empty_note}</strong>
		</p>
		<!-- END: pass_empty_note -->
		<!-- BEGIN: question_empty_note -->
		<p>
			<strong>&raquo; {USER.question_empty_note}</strong>
		</p>
		<!-- END: question_empty_note -->
		<dl class="clearfix">
			<dt class="fl">
				{LANG.name}:
			</dt>
			<dd class="fl">
				{USER.full_name}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.birthday}:
			</dt>
			<dd class="fl">
				{USER.birthday}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.gender}:
			</dt>
			<dd class="fl">
				{USER.gender}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.showmail}:
			</dt>
			<dd class="fl">
				{USER.view_mail}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.regdate}:
			</dt>
			<dd class="fl">
				{USER.regdate}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.st_login2}:
			</dt>
			<dd class="fl">
				{USER.st_login}
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				{LANG.last_login}:
			</dt>
			<dd class="fl">
				{USER.last_login}
			</dd>
		</dl>
	</div>
</div>
<!-- END: main -->