<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.nv_lang_nb}</td>
				<td>{LANG.countries_name}</td>
				<td>{LANG.nv_lang_data}</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" class="center"><input type="submit" name="submit" value="{LANG.nv_admin_submit}" style="width: 100px;"/></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: countries -->
			<tr>
				<td>{NB}</td>
				<td>{LANG_NAME}</td>
				<td>
				<select name="countries[{LANG_KEY}]">
					<!-- BEGIN: language -->
					<option value="{DATA_KEY}" {DATA_SELECTED}>{DATA_TITLE}</option>
					<!-- END: language -->
				</select></td>
			</tr>
			<!-- END: countries -->
		</tbody>
	</table>
</form>
<!-- END: main -->
