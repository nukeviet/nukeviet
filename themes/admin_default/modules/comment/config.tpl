<!-- BEGIN: main -->
<!-- BEGIN: list -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td>{LANG.weight}</td>
			<td>{LANG.mod_name}</td>
			<td>{LANG.activecomm}</td>
			<td>{LANG.allowed_comm}</td>
			<td>{LANG.auto_postcomm}</td>
			<td>{LANG.emailcomm}</td>
			<td>{LANG.funcs}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.weight}</td>
			<td>{ROW.admin_title}</td>
			<td class="center"><em class="icon-{ROW.activecomm} icon-large">&nbsp;</em></td>
			<td>{ROW.allowed_comm}</td>
			<td>{ROW.auto_postcomm}</td>
			<td class="center"><em class="icon-{ROW.emailcomm} icon-large">&nbsp;</em></td>
			<td class="center"><em class="icon-edit icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={ROW.mod_name}">{LANG.edit}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: list -->

<!-- BEGIN: config -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&mod_name={MOD_NAME}" method="post">
	<table class="tab1">
		<colgroup>
			<col style="width: 300px;" />
			<col style="width: auto;" />
		</colgroup>
		<tfoot>
			<tr>
				<td style="text-align: center;" colspan="2"><input name="submit" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.activecomm}</strong></td>
				<td><input type="checkbox" value="1" name="activecomm"{ACTIVECOMM}/></td>
			</tr>
			<tr>
				<td><strong>{LANG.allowed_comm}</strong></td>
				<td>
				<select name="allowed_comm">
					<!-- BEGIN: allowed_comm -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: allowed_comm -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.setcomm}</strong></td>
				<td>
				<select name="setcomm">
					<!-- BEGIN: setcomm -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: setcomm -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.auto_postcomm}</strong></td>
				<td>
				<select name="auto_postcomm">
					<!-- BEGIN: auto_postcomm -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: auto_postcomm -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.emailcomm}</strong></td>
				<td><input type="checkbox" value="1" name="emailcomm"{EMAILCOMM}/></td>
			</tr>
			<tr>
				<td><strong>{LANG.sortcomm}</strong></td>
				<td>
				<select name="sortcomm">
					<!-- BEGIN: sortcomm -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: sortcomm -->
				</select></td>
			</tr>

			<tr>
				<td><strong>{LANG.adminscomm}</strong></td>
				<td>
				<!-- BEGIN: adminscomm -->
				<label style="display:inline-block;width:200px"> <input name="adminscomm[]" type="checkbox" value="{OPTION.key}" {OPTION.checked}>{OPTION.title} </label>
				<!-- END: adminscomm -->
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: config -->
<!-- END: main -->