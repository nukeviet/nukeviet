<!-- BEGIN: main -->
<form name="myform" method="post" action="{ACTION}" onsubmit="nv_chsubmit(this,'tables[]');return false;">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="tables">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTIONS}</caption>
			<thead>
				<tr>
					<th class="w50 text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);" /></th>
					<!-- BEGIN: columns -->
					<th>{COLNAME}</th>
					<!-- END: columns -->
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="11">
						<select id="op_name" name="{OP_NAME}" onchange="nv_checkForm();" class="form-control pull-left w200" style="margin-right: 5px">
							<!-- BEGIN: op -->
							<option value="{KEY}">{VAL}</option>
							<!-- END: op -->
						</select>
						<select id="type_name" name="{TYPE_NAME}" class="form-control pull-left w200" style="margin-right: 5px">
							<!-- BEGIN: type -->
							<option value="{KEY}">{VAL}</option>
							<!-- END: type -->
						</select>
						<select id="ext_name" name="{EXT_NAME}" class="form-control pull-left w200" style="margin-right: 5px">
							<!-- BEGIN: ext -->
							<option value="{KEY}" {SELECTED}>{VAL}</option>
							<!-- END: ext -->
						</select>
						<input name="checkss" type="hidden" value="{NV_CHECK_SESSION}" />
						<input name="Submit1" id="subm_form" type="submit" value="{SUBMIT}" class="btn btn-primary" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input name="tables[]" type="checkbox" value="{ROW.key}" onclick="nv_UncheckAll(this.form, 'tables[]', 'check_all[]', this.checked);" /></td>
					<!-- BEGIN: col -->
					<td>{VALUE}</td>
					<!-- END: col -->
				</tr>
				<!-- END: loop -->
				<tr>
					<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'tables[]', 'check_all[]',this.checked);" /></td>
					<td colspan="10"><strong>{THIRD}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script>
	$('#tables').tshift();
</script>
<!-- END: main -->