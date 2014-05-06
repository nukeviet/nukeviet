<!-- BEGIN: main -->
<!-- BEGIN: nowrite -->
<div class="alert alert-danger">{TITLE}</div>
<div class="codecontent">
	{CONTENT}
</div>
<!-- END: nowrite -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
			<col class="w100" />
			</colgroup>
			<thead>
				<tr class="text-center">
					<th>{LANG.robots_number}</th>
					<th>{LANG.robots_filename}</th>
					<th>{LANG.robots_type}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="text-center" colspan="3"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">{DATA.number}</td>
					<td>{DATA.filename}</td>
					<td>
					<select name="filename[{DATA.filename}]" class="form-control">
						<!-- BEGIN: option -->
						<option value="{OPTION.value}" {OPTION.selected}>{OPTION.title}</option>
						<!-- END: option -->
					</select></td>
				</tr>
				<!-- END: loop -->
				<!-- BEGIN: other -->
				<tr>
					<td class="text-center">{DATA.number}</td>
					<td><input class="form-control" type="text" value="{DATA.filename}" name="fileother[{DATA.number}]" /></td>
					<td><select name="optionother[{DATA.number}]" class="form-control">
						<!-- BEGIN: option -->
						<option value="{OPTION.value}" {OPTION.selected}>{OPTION.title}</option>
						<!-- END: option -->
					</select></td>
				</tr>
				<!-- END: other -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->