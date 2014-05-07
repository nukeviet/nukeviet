<!-- BEGIN: main -->
<form class="navbar-form" role="search" action="{NV_BASE_ADMINURL}index.php" method="get">
	<br />
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<label> {LANG.search_cat}: </label>
	<select class="form-control" name="catid">
		<!-- BEGIN: cat_content -->
		<option value="{CAT_CONTENT.value}" {CAT_CONTENT.selected} >{CAT_CONTENT.title}</option>
		<!-- END: cat_content -->
	</select>
	<label> {LANG.search_type}: </label>
	<select class="form-control" name="stype">
		<!-- BEGIN: search_type -->
		<option value="{SEARCH_TYPE.key}" {SEARCH_TYPE.selected} >{SEARCH_TYPE.value}</option>
		<!-- END: search_type -->
	</select>
	<label> {LANG.search_status}: </label>
	<select class="form-control" name="sstatus">
		<!-- BEGIN: search_status -->
		<option value="{SEARCH_STATUS.key}" {SEARCH_STATUS.selected} >{SEARCH_STATUS.value}</option>
		<!-- END: search_status -->
	</select>
	<label> {LANG.search_per_page}: </label>
	<select class="form-control" name="per_page">
		<!-- BEGIN: s_per_page -->
		<option value="{SEARCH_PER_PAGE.page}" {SEARCH_PER_PAGE.selected} >{SEARCH_PER_PAGE.page}</option>
		<!-- END: s_per_page -->
	</select>
	<br />
	<br />
	{LANG.search_key}:
	<input class="form-control" type="text" value="{Q}" maxlength="64" name="q" style="width: 265px" />
	<input class="btn btn-primary" type="submit" value="{LANG.search}" />
	<br />
	<input type="hidden" name="checkss" value="{CHECKSS}" />
	<label><em>{LANG.search_note}</em></label>
</form>

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th><a href="{base_url_name}">{LANG.name}</a></th>
					<th><a href="{base_url_publtime}">{LANG.content_publ_date}</a></th>
					<th>{LANG.status}</th>
					<th>{LANG.content_admin}</th>
					<th>{LANG.hitstotal}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody class="text-center">
				<!-- BEGIN: loop -->
				<tr>
					<td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td class="text-left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
					<td>{ROW.publtime}</td>
					<td>{ROW.status}</td>
					<td>{ROW.username}</td>
					<td>{ROW.hitstotal}</td>
					<td>{ROW.feature}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr class="text-left">
					<td colspan="7">
					<select class="form-control" name="action" id="action">
						<!-- BEGIN: action -->
						<option value="{ACTION.value}">{ACTION.title}</option>
						<!-- END: action -->
					</select>
					<input type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{SITEKEY}', '{LANG.msgnocheck}')" value="{LANG.action}" /></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->