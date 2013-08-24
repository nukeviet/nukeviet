<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td>{LANG.login}</td>
			<td>{LANG.email}</td>
			<td>{LANG.lev}</td>
			<td>{LANG.position}</td>
			<td>{LANG.is_suspend}</td>
			<td>{LANG.adminip_funcs}</td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: loop -->
		<tr>
			<td><img class="refresh" alt="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /><a href="{DATA.link}"><b>{DATA.login}</b></a></td>
			<td>{DATA.email}</td>
			<td>{DATA.lev}</td>
			<td>{DATA.position}</td>
			<td>{DATA.is_suspend}</td>
			<td>
			<!-- BEGIN: edit -->
			<a class="edit_icon" href="{EDIT_HREF}">{EDIT_NAME}</a>
			<!-- END: edit -->
			<!-- BEGIN: suspend -->
			&nbsp;-&nbsp;<a class="edit_icon" href="{SUSPEND_HREF}">{SUSPEND_NAME}</a>
			<!-- END: suspend -->
			<!-- BEGIN: del -->
			&nbsp;-&nbsp;<a class="delete_icon" href="{DEL_HREF}">{DEL_NAME}</a>
			<!-- END: del -->
			</td>
		</tr>
	<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->