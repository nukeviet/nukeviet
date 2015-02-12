<!-- BEGIN: main -->
<div class="page-header">
	<h3>{LANG.in_group}</h3>
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
	<li>
		<!-- BEGIN: allowopenid -->
		<a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
		<!-- END: allowopenid -->
	</li>
	<li class="ui-tabs-selected">
		<a href="{URL_HREF}regroups">{LANG.in_group}</a>
	</li>
	<!-- BEGIN: logout -->
	<li>
		<a href="{URL_HREF}logout">{LANG.logout_title}</a>
	</li>
	<!-- END: logout -->
</ul>
<form id="registerForm" action="{USER_REGISTER}" method="post" class="box-border-shadow content-box clearfix reg">
	<dl class="clearfix gray">
		<dt class="fl" style="width:33%">
			<label> {LANG.in_group} </label>
		</dt>
		<dd class="fr" style="width:60%">
			<!-- BEGIN: list -->
			<input type="checkbox" value="{GROUP.id}" name="group[]" {GROUP.checked} /><span>{GROUP.title}</span>
			<br />
			<!-- END: list -->
		</dd>
	</dl>
	<input id="submit" type="submit" class="button" value="{LANG.in_group}" name="save" />
</form>
<!-- END: main -->