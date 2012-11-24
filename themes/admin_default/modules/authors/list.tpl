<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td>{LANG.login}</td>
			<td>{LANG.email}</td>
			<td>{LANG.lev}</td>
			<td>{LANG.position}</td>
			<td>{LANG.adminip_funcs}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody {CLASS}>
		<tr>
			<td><img class="refresh" alt="{OPTION_LEV}" title="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /> <a href="{DATA.link}"><b>{DATA.login}</b></a></td>
			<td>{DATA.email}</td>
			<td>{DATA.lev}</td>
			<td>{DATA.position}</td>
			<td>
				<!-- BEGIN: edit -->
				<span class="edit_icon"><a href="{EDIT_HREF}">{EDIT_NAME}</a></span>
				<!-- END: edit -->
				<!-- BEGIN: suspend -->
				&nbsp;-&nbsp;<span class="edit_icon"><a href="{SUSPEND_HREF}">{SUSPEND_NAME}</a></span>
				<!-- END: suspend -->
				<!-- BEGIN: del -->
				&nbsp;-&nbsp;<span class="delete_icon"><a href="{DEL_HREF}">{DEL_NAME}</a></span>
				<!-- END: del -->
			</td>
		</tr>
	</tbody>		
	<!-- END: loop -->
</table>
<!-- END: main -->