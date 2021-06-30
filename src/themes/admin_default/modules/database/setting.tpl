<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2"><input type="hidden" name="checkss" value="{CHECKSS}" /><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" /></td>
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
					<select name="dump_backup_ext" class="form-control w100">
						<!-- BEGIN: dump_backup_ext -->
						<option value="{BACKUPEXTVALUE}" {BACKUPEXTSELECTED}>{BACKUPEXTVALUE} </option>
						<!-- END: dump_backup_ext -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.dump_interval}</strong></td>
					<td>
					<select name="dump_interval" class="form-control w100 pull-left">
						<!-- BEGIN: dump_interval -->
						<option value="{BACKUPDAYVALUE}" {BACKUPDAYSELECTED}>{BACKUPDAYVALUE}</option>
						<!-- END: dump_interval -->
					</select> 
					<span class="text-middle">&nbsp;({GLANG.day})</span></td>
				</tr>
				<tr>
					<td><strong>{LANG.dump_backup_day}</strong></td>
					<td>
					<select name="dump_backup_day" class="form-control pull-left w100">
						<!-- BEGIN: dump_backup_day -->
						<option value="{BACKUPDAYVALUE}" {BACKUPDAYSELECTED}>{BACKUPDAYVALUE}</option>
						<!-- END: dump_backup_day -->
					</select>
					<span class="text-middle">&nbsp;({GLANG.day})</span></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->