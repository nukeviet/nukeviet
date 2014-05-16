<!-- BEGIN: main -->
<div id="module_show_list">
	{BLOCK_LIST}
</div>
<br />
<div id="add">
	<form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<col width="10"/>
				<caption>{LANG.addtoblock}</caption>
				<thead>
					<tr class="text-center">
						<th><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
						<th>{LANG.name}</th>
					</tr>
				</thead>
				<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" {ROW.checked}></td>
						<td>{ROW.title}</td>
					</tr>
					<!-- END: loop -->
				</tbody>
				<tfoot>
					<tr align="left">
						<td class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
						<td>
						<select class="form-control" name="bid">
							<!-- BEGIN: bid -->
							<option value="{BID.key}" {BID.selected}>{BID.title}</option>
							<!-- END: bid -->
						</select><input type="hidden" name ="checkss" value="{CHECKSESS}" /><input class="btn btn-primary" name="submit1" type="submit" value="{LANG.save}" /></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>
</div>
<!-- END: main -->