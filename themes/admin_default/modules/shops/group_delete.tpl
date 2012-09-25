<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form action="{NV_BASE_ADMINURL}index.php" method="post">
					<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
					<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
					<input type="hidden" name="groupid" value="{GROUPID}" />
					<input type="hidden" name="delallcheckss" value="{DELALLCHECKSS}" />
					<center>
						<b>{INFO}</b>
						<br><br><input name="delgroupandrows" type="submit" value="{LANG.delgroupandrows}" />
						<br><br><b>{LANG.delgroup_msg_rows_move}</b>: 
						<select name="groupidnews">
							<!-- BEGIN: grouploop -->
							<option value="{GROUP_ID}">{GROUP_TITLE}</option>
							<!-- END: grouploop -->
						</select>
						<input name="movegroup" type="submit" value="{LANG.action}" onclick="return nv_check_movegroup(this.form, '{LANG.delgroup_msg_rows_noselect}')">
					</center>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<!-- END: main -->