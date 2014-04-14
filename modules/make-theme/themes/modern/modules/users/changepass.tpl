<!-- BEGIN: main -->
<div id="users">
	<div class="page-header">
		<h3>{LANG.change_pass}</h3>
	</div>
	<ul class="list-tab top-option clearfix">
		<li>
			<a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
		</li>
		<li class="ui-tabs-selected">
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
	<form id="changePassForm" action="{USER_CHANGEPASS}" method="post" class="box-border-shadow content-box clearfix">
		<p>
			{DATA.change_info}
		</p>
		<!-- BEGIN: passEmpty -->
		<div class="clearfix rows">
			<label> {LANG.pass_old} </label>
			<input type="password" id="nv_password_iavim" name="nv_password" value="{DATA.nv_password}" class="required password input" maxlength="{PASS_MAXLENGTH}" />
		</div>
		<!-- END: passEmpty -->
		<div class="clearfix rows">
			<label> {LANG.pass_new} </label>
			<input type="password" id="new_password_iavim" name="new_password" value="{DATA.new_password}" class="required password input" maxlength="{PASS_MAXLENGTH}" />
		</div>
		<div class="clearfix rows">
			<label> {LANG.pass_new_re} </label>
			<input type="password" id="re_password_iavim" name="re_password" value="{DATA.re_password}" class="required password input" maxlength="{PASS_MAXLENGTH}" />
		</div>
		<div class="clearfix rows">
			<label> &nbsp; </label>
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input type="submit" value="{LANG.change_pass}" class="button" />
		</div>

	</form>
</div>
<!-- END: main -->