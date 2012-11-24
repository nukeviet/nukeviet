<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1 fixtab">
		<tbody class="second">
			<tr>
				<td style="width:200px">
					<strong>{LANG.dump_autobackup}</strong>
				</td>
				<td>
					<input type="checkbox" value="1" name="dump_autobackup" {DATA.dump_autobackup} />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>
					<strong>{LANG.dump_backup_ext}</strong>
				</td>
				<td>
					<select name="dump_backup_ext">
						<!-- BEGIN: dump_backup_ext -->
						<option value="{BACKUPEXTVALUE}" {BACKUPEXTSELECTED}>{BACKUPEXTVALUE}  </option>
						<!-- END: dump_backup_ext -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					<strong>{LANG.dump_backup_day}</strong>
				</td>
				<td>
					<select name="dump_backup_day">
						<!-- BEGIN: dump_backup_day -->
						<option value="{BACKUPDAYVALUE}" {BACKUPDAYSELECTED}>{BACKUPDAYVALUE}</option>
						<!-- END: dump_backup_day -->
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="tab1 fixtab">
		<tr>
			<td class="center"><input type="submit" name="submit" value="{LANG.submit}"/></td>
		</tr>
	</table>
</form>
<!-- END: main -->
