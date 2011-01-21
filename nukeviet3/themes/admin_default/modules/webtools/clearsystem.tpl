<!-- BEGIN: main -->
<form action="" method="post">
<table class="tab1" summary="">
	<tbody class="second">
		<tr>
			<td><strong>{LANG.clearcache}</strong></td>
			<td><input type="checkbox" value="clearcache" name="deltype[]" /></td>
		</tr>
	</tbody>

	<tbody>
		<tr>
			<td><strong>{LANG.clearsession}</strong></td>
			<td><input type="checkbox" value="clearsession" name="deltype[]" /></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td><strong>{LANG.cleardumpbackup}</strong></td>
			<td><input type="checkbox" value="cleardumpbackup" name="deltype[]" /></td>
		</tr>
	</tbody>

	<tbody>
		<tr>
			<td><strong>{LANG.clearfiletemp}</strong></td>
			<td><input type="checkbox" value="clearfiletemp" name="deltype[]" /></td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td><strong>{LANG.clearerrorlogs}</strong></td>
			<td><input type="checkbox" value="clearerrorlogs" name="deltype[]" /></td>
		</tr>
	</tbody>
	<tbody class="tfoot_box">
		<tr>
			<td colspan="2" align="center"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></td>
		</tr>
	</tbody>
</table>
</form>

<!-- BEGIN: delfile -->
<br>
<br>
<strong>{LANG.deletedetail}</strong>:
<br>
<br>
<ul>
	<ol>
		<!-- BEGIN: loop -->
			<li>{DELFILE}</li>
		<!-- END: loop -->
	</ol>
</ul>
<!-- END: delfile -->
<!-- END: main -->