<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td><strong>{LANG.checkContent}</strong></td>
				<td><input type="checkbox" value="yes" name="check_all[]" onclick="nv_checkAll(this.form, 'deltype[]', 'check_all[]',this.checked);" /></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.clearcache}</strong></td>
				<td><input type="checkbox" value="clearcache" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.clearsession}</strong></td>
				<td><input type="checkbox" value="clearsession" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.clearfiletemp}</strong></td>
				<td><input type="checkbox" value="clearfiletemp" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.clearerrorlogs}</strong></td>
				<td><input type="checkbox" value="clearerrorlogs" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.clearip_logs}</strong></td>
				<td><input type="checkbox" value="clearip_logs" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
			</tr>
		</tbody>
	</table>
</form>

<!-- BEGIN: delfile -->
<br />
<br />
<strong>{LANG.deletedetail}</strong>:
<br />
<br />
<ul style="list-style: none;">
	<!-- BEGIN: loop -->
	<li>
		{DELFILE}
	</li>
	<!-- END: loop -->
</ul>
<!-- END: delfile -->
<!-- END: main -->