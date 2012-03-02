<!-- BEGIN: main -->
<form action="" method="post">
<table class="tab1" summary="">
	<thead>
		<tr>
			<td><strong>{LANG.checkContent}</strong></td>
			<td><input type="checkbox" value="yes" name="check_all[]" onclick="nv_checkAll(this.form, 'deltype[]', 'check_all[]',this.checked);" /></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong>{LANG.clearcache}</strong></td>
			<td><input type="checkbox" value="clearcache" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
		</tr>
	</tbody>

	<tbody class="second">
		<tr>
			<td><strong>{LANG.clearsession}</strong></td>
			<td><input type="checkbox" value="clearsession" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
		</tr>
	</tbody>

	<tbody>
		<tr>
			<td><strong>{LANG.clearfiletemp}</strong></td>
			<td><input type="checkbox" value="clearfiletemp" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td><strong>{LANG.clearerrorlogs}</strong></td>
			<td><input type="checkbox" value="clearerrorlogs" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td><strong>{LANG.clearip_logs}</strong></td>
			<td><input type="checkbox" value="clearip_logs" name="deltype[]" onclick="nv_UncheckAll(this.form, 'deltype[]', 'check_all[]', this.checked);" /></td>
		</tr>
	</tbody>	
	<tfoot>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></td>
		</tr>
	</tfoot>
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
		<li>{DELFILE}</li>
	<!-- END: loop -->
</ul>
<!-- END: delfile -->
<!-- END: main -->