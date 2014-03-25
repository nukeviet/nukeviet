<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<br />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<label> {LANG.search_cat}: </label>
	<select name="catid">
		<!-- BEGIN: cat_content -->
		<option value="{CAT_CONTENT.value}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
		<!-- END: cat_content -->
	</select>
	<label> {LANG.search_type}: </label>
	<select name="stype">
		<!-- BEGIN: search_type -->
		<option value="{SEARCH_TYPE.key}" {SEARCH_TYPE.selected} >{SEARCH_TYPE.value}</option>
		<!-- END: search_type -->
	</select>
	<label> {LANG.search_status}: </label>
	<select name="sstatus">
		<!-- BEGIN: search_status -->
		<option value="{SEARCH_STATUS.key}" {SEARCH_STATUS.selected} >{SEARCH_STATUS.value}</option>
		<!-- END: search_status -->
	</select>
	<label> {LANG.search_per_page}: </label>
	<select name="per_page">
		<!-- BEGIN: s_per_page -->
		<option value="{SEARCH_PER_PAGE.page}" {SEARCH_PER_PAGE.selected} >{SEARCH_PER_PAGE.page}</option>
		<!-- END: s_per_page -->
	</select>
	<br />
	<br />
	{LANG.search_key}:
	<input type="text" value="{Q}" maxlength="64" name="q" style="width: 265px" />
	<input type="submit" value="{LANG.search}" />
	<br />
	<input type="hidden" name="checkss" value="{CHECKSS}" />
	<label><em>{LANG.search_note}</em></label>
</form>

<form name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<table class="tab1">
		<thead>
			<tr>
				<td class="center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
				<td><a href="{base_url_name}">{LANG.name}</a></td>
				<td class="center"><a href="{base_url_publtime}">{LANG.content_publ_date}</a></td>
				<td class="center">{LANG.status}</td>
				<td>{LANG.content_admin}</td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tbody class="center">
			<!-- BEGIN: loop -->
			<tr>
				<td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
				<td class="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
				<td>{ROW.publtime}</td>
				<td>{ROW.status}</td>
				<td>{ROW.username}</td>
				<td>{ROW.feature}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<tfoot>
			<tr class="left">
				<td colspan="6">
				<select name="action" id="action">
					<!-- BEGIN: action -->
					<option value="{ACTION.value}">{ACTION.title}</option>
					<!-- END: action -->
				</select> 
				<input type="button" onclick="nv_main_action(this.form, '{SITEKEY}', '{LANG.msgnocheck}')" value="{LANG.action}" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- BEGIN: generate_page -->
<br />
<p class="center">
	{GENERATE_PAGE}
</p>
<!-- END: generate_page -->
<!-- END: main -->