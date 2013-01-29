<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td><strong>{LANG.mudim_active}</strong></td>
				<td>
				<select name="mudim_active">
					<!-- BEGIN: mudim_active -->
					<option value="{MUDIM_ACTIVE_OP}"{MUDIM_ACTIVE_SELECTED}>{MUDIM_ACTIVE_TEXT}</option>
					<!-- END: mudim_active -->
				</select></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.mudim_showpanel}</strong></td>
				<td><input type="checkbox" value="1" name="mudim_showpanel" {MUDIM_SHOWPANEL}/></td>
			</tr>
		</tbody>

		<tbody>
			<tr>
				<td><strong>{LANG.mudim_displaymode}</strong></td>
				<td>
				<select name="mudim_displaymode">
					<!-- BEGIN: mudim_displaymode -->
					<option value="{MUDIM_DISPLAYMODE_OP}"{MUDIM_DISPLAYMODE_SELECTED}>{MUDIM_DISPLAYMODE_TEXT}</option>
					<!-- END: mudim_displaymode -->
				</select></td>
			</tr>
		</tbody>

		<tbody class="second">
			<tr>
				<td><strong>{LANG.mudim_method}</strong></td>
				<td>
				<select name="mudim_method">
					<!-- BEGIN: mudim_method -->
					<option value="{MUDIM_METHOD_OP}"{MUDIM_METHOD_SELECTED}>{MUDIM_METHOD_TEXT}</option>
					<!-- END: mudim_method -->
				</select></td>
			</tr>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->