<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<table id="aid{ID}" class="tab1">
	<caption>{CAPTION}</caption>
	<col span="2" style="width: 50%"/>
	<thead>
		<tr>
			<td colspan="2">
			<div style="position:absolute;right:20px">
				<!-- BEGIN: edit -->
				<a class="button button-h" href="{EDIT_HREF}">{EDIT_NAME}</a>
				<!-- END: edit -->
				<!-- BEGIN: suspend -->
				<a class="button button-h" href="{SUSPEND_HREF}">{SUSPEND_NAME}</a>
				<!-- END: suspend -->
				<!-- BEGIN: del -->
				<a class="button button-h" href="{DEL_HREF}">{DEL_NAME}</a>
				<!-- END: del -->
			</div><img class="refresh" alt="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /> {CAPTION} </td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: option_loop -->
		<tr>
			<td>{VALUE0}</td>
			<td>{VALUE1}</td>
		</tr>
		<!-- END: option_loop -->
	</tbody>
</table>
<!-- END: loop -->
<!-- END: main -->