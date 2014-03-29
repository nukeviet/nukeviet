<!-- BEGIN: main -->
<ul class="nav nav-tabs m-bottom">
	<li class="active"><a href="{URL_HREF}main">{LANG.user_info}</a></li>
	<li><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
	<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
	<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
	<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
	<!-- BEGIN: logout --><li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li><!-- END: logout -->
</ul>
<h2>{LANG.user_info}</h2>
<div class="row m-bottom">
	<div class="col-md-3 text-center">
		<img src="{SRC_IMG}" alt="{USER.username}" class="img-thumbnail m-bottom"/><br />
		{LANG.img_size_title}
	</div>
	<div class="col-md-9">
		<ul class="nv-list-item xsm">
			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.account2}: <strong>{USER.username}</strong> ({USER.email})</li>
			<li><em class="fa fa-chevron-right ">&nbsp;</em> {USER.current_mode}</li>
			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.current_login}: {USER.current_login}</li>
			<li><em class="fa fa-chevron-right ">&nbsp;</em> {LANG.ip}: {USER.current_ip}</li>
		</ul>
	</div>
</div>
<!-- BEGIN: change_login_note -->
<div class="alert alert-danger">
	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.change_name_info}
</div>
<!-- END: change_login_note -->
<!-- BEGIN: pass_empty_note -->
<div class="alert alert-danger">
	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.pass_empty_note}
</div>
<!-- END: pass_empty_note -->
<!-- BEGIN: question_empty_note -->
<div class="alert alert-danger">
	<em class="fa fa-exclamation-triangle ">&nbsp;</em> {USER.question_empty_note}
</div>
<!-- END: question_empty_note -->
<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<th>{LANG.name}</th>
				<td>{USER.full_name}</td>
			</tr>
			<tr>
				<th>{LANG.birthday}</th>
				<td>{USER.birthday}</td>
			</tr>
			<tr>
				<th>{LANG.gender}</th>
				<td>{USER.gender}</td>
			</tr>
			<tr>
				<th>{LANG.showmail}</th>
				<td>{USER.view_mail}</td>
			</tr>
			<tr>
				<th>{LANG.regdate}</th>
				<td>{USER.regdate}</td>
			</tr>
			<tr>
				<th>{LANG.st_login2}</th>
				<td>{USER.st_login}</td>
			</tr>
			<tr>
				<th>{LANG.last_login}</th>
				<td>{USER.last_login}</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: main -->