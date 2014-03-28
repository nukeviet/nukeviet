<!-- BEGIN: main -->
<table class="tab1">
	<col span="2" style="width: 50%" />
	<thead>
		<tr>
			<td colspan="2">
			<div style="position:absolute;right:10px">
				<!-- BEGIN: edit -->
				<a class="button button-h" href="{DATA.edit.2}">{DATA.edit.1}</a>
				<!-- END: edit -->
				<!-- BEGIN: disable -->
				<a class="button button-h" href="{DATA.disable.2}">{DATA.disable.1}</a>
				<!-- END: disable -->
				<!-- BEGIN: delete -->
				<a class="button button-h" href="javascript:void(0);" onclick="nv_is_del_cron('{DATA.id}');">{DATA.delete.1}</a>
				<!-- END: delete -->
			</div> {DATA.caption} </td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>{ROW.key}</td>
			<td>{ROW.value}</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->