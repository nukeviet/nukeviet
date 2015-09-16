<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.login}</th>
				<th>{LANG.email}</th>
				<th>{LANG.position}</th>
				<th>{LANG.lev}</th>
				<th>{LANG.is_suspend}</th>
				<th>{LANG.adminip_funcs}</th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td><img alt="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /><a href="{DATA.link}" title="{DATA.full_name}"><strong>{DATA.login}</strong></a></td>
				<td>{DATA.email}</td>
				<td>{DATA.position}</td>
				<td>{DATA.lev}</td>
				<td>{DATA.is_suspend}</td>
				<td>
				<!-- BEGIN: edit -->
				<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{EDIT_HREF}">{EDIT_NAME}</a>
				<!-- END: edit -->
				<!-- BEGIN: suspend -->
				<em class="fa fa-times-circle-o fa-lg">&nbsp;</em><a href="{SUSPEND_HREF}">{SUSPEND_NAME}</a>
				<!-- END: suspend -->
				<!-- BEGIN: del -->
				<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{DEL_HREF}">{DEL_NAME}</a>
				<!-- END: del -->
				</td>
			</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->