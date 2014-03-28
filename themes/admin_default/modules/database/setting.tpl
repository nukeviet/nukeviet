<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1 fixtab">
		<tfoot>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="{LANG.submit}"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="w200"><strong>{LANG.dump_autobackup}</strong></td>
				<td><input type="checkbox" value="1" name="dump_autobackup" {DATA.dump_autobackup} /></td>
			</tr>
			<tr>
				<td><strong>{LANG.dump_backup_ext}</strong></td>
				<td>
				<select name="dump_backup_ext">
					<!-- BEGIN: dump_backup_ext -->
					<option value="{BACKUPEXTVALUE}" {BACKUPEXTSELECTED}>{BACKUPEXTVALUE} </option>
					<!-- END: dump_backup_ext -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.dump_interval}</strong></td>
				<td>
				<select name="dump_interval">
					<!-- BEGIN: dump_interval -->
					<option value="{BACKUPDAYVALUE}" {BACKUPDAYSELECTED}>{BACKUPDAYVALUE}</option>
					<!-- END: dump_interval -->
				</select> ({GLANG.day})</td>
			</tr>
			<tr>
				<td><strong>{LANG.dump_backup_day}</strong></td>
				<td>
				<select name="dump_backup_day">
					<!-- BEGIN: dump_backup_day -->
					<option value="{BACKUPDAYVALUE}" {BACKUPDAYSELECTED}>{BACKUPDAYVALUE}</option>
					<!-- END: dump_backup_day -->
				</select> ({GLANG.day})</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->