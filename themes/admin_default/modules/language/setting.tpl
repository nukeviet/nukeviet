<!-- BEGIN: info -->
<br />
<br />
<p class="center">
	<strong>{INFO}</strong>
</p>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: info -->
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<table class="tab1 fixtab">
		<caption> {LANG.nv_lang_show} </caption>
		<colgroup>
			<col class="w50">
			<col class="w50">
			<col span="5">
		</colgroup>
		<thead>
			<tr class="center">
				<td>&nbsp;</td>
				<td>{LANG.nv_lang_key}</td>
				<td>{LANG.nv_lang_name}</td>
				<td>{LANG.nv_lang_native_name}</td>
				<td>{LANG.nv_lang_slsite}</td>
				<td>{LANG.nv_lang_sladm}</td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="center">{ROW.number}</td>
				<td class="center">{ROW.key}</td>
				<td>{ROW.language}</td>
				<td>{ROW.name}</td>
				<td class="center"><input name="allow_sitelangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_sitelangs} /></td>
				<td class="center"><input name="allow_adminlangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_adminlangs} /></td>
				<td class="center">{ROW.arr_lang_func}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessshow" value="{CHECKSESSSHOW}" />
	<div class="center">
		<input type="submit" value="{LANG.nv_admin_edit_save}" />
	</div>
</form>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<table class="tab1">
		<caption> {LANG.nv_setting_read} </caption>
		<!-- BEGIN: type -->
		<tr>
			<td>&nbsp;</td>
			<td><input name="read_type" value="{TYPE.key}" type="radio"{TYPE.checked} /> {TYPE.title}</td>
		</tr>
		<!-- END: type -->
	</table>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessseting" value="{CHECKSESSSETING}" />
	<div class="center">
		<input type="submit" value="{LANG.nv_admin_edit_save}" />
	</div>
</form>
<!-- END: main -->