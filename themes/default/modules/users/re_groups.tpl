<!-- BEGIN: main -->
<ul class="nav nav-tabs m-bottom">
	<li><a href="{URL_HREF}main">{LANG.user_info}</a></li>
	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
	<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
	<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
	<li class="active"><a href="{URL_HREF}regroups">{LANG.in_group}</a></li>
	<!-- BEGIN: logout --><li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li><!-- END: logout -->
</ul>
<h2>{LANG.in_group}</h2>
<form action="{USER_REGISTER}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<div class="m-bottom">
		<!-- BEGIN: list -->
		<div class="checkbox">
			<label>
				<input type="checkbox" value="{GROUP.id}" name="group[]" {GROUP.checked}/>
				{GROUP.title}
			</label>
		</div>
		<!-- END: list -->
	</div>
	<div class="text-left">
		<input type="submit" class="btn btn-primary" value="{LANG.in_group}" name="save" />
	</div>
</form>
<!-- END: main -->