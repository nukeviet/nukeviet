<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col span="2" style="width: 50%" />
		<thead>
			<tr>
				<th colspan="2">
				<div>
					<!-- BEGIN: edit -->
					<a class="btn btn-primary btn-xs pull-right" style="margin-left: 5px" href="{DATA.edit.2}">{DATA.edit.1}</a>
					<!-- END: edit -->
					<!-- BEGIN: disable -->
					<a class="btn btn-primary btn-xs pull-right" style="margin-left: 5px" href="{DATA.disable.2}">{DATA.disable.1}</a>
					<!-- END: disable -->
					<!-- BEGIN: delete -->
					<a class="btn btn-primary btn-xs pull-right" href="javascript:void(0);" onclick="nv_is_del_cron('{DATA.id}', '{DATA.delete.2}');">{DATA.delete.1}</a>
					<!-- END: delete -->
				</div> {DATA.caption} </th>
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
</div>
<!-- END: main -->