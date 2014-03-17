<!-- BEGIN: empty -->
<div class="center">
	<strong>{LANG.nv_lang_error_exit}</strong>
</div>
<meta http-equiv="Refresh" content="3;URL={URL}" />
<!-- END: empty -->
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
	<table class="tab1">
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input type="hidden" name ="submit" value="1" /><input type="submit" value="{LANG.nv_admin_submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="aright">{LANG.nv_lang_data}:</td>
				<td>
				<select name="typelang">
					<option value="">{LANG.nv_admin_sl3}</option>
					<!-- BEGIN: language -->
					<option value="{LANGUAGE.key}"{LANGUAGE.selected}>{LANGUAGE.title}</option>
					<!-- END: language -->
				</select></td>
			</tr>
			<tr>
				<td class="aright">{LANG.nv_lang_data_source}:</td>
				<td>
				<select name="sourcelang">
					<option value="">{LANG.nv_admin_sl3}</option>
					<!-- BEGIN: language_source -->
					<option value="{LANGUAGE_SOURCE.key}"{LANGUAGE_SOURCE.selected}>{LANGUAGE_SOURCE.title}</option>
					<!-- END: language_source -->
				</select></td>
			</tr>
			<tr>
				<td class="aright"> {LANG.nv_lang_area}:</td>
				<td>
				<select name="idfile">
					<option value="0">{LANG.nv_lang_checkallarea}</option>
					<!-- BEGIN: language_area -->
					<option value="{LANGUAGE_AREA.key}"{LANGUAGE_AREA.selected}>{LANGUAGE_AREA.title}</option>
					<!-- END: language_area -->
				</select></td>
			</tr>
			<tr>
				<td class="aright">{LANG.nv_check_type}:</td>
				<td>
				<select name="check_type">
					<!-- BEGIN: language_check_type -->
					<option value="{LANGUAGE_CHECK_TYPE.key}"{LANGUAGE_CHECK_TYPE.selected}>{LANGUAGE_CHECK_TYPE.title}</option>
					<!-- END: language_check_type -->
				</select></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- BEGIN: nodata -->
<div class="center">
	<br />
	<strong>{LANG.nv_lang_check_no_data}</strong>
</div>
<!-- END: nodata -->
<!-- BEGIN: data -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="submit" value="1" />
	<input type="hidden" name ="typelang" value="{DATA.typelang}" />
	<input type="hidden" name ="sourcelang" value="{DATA.sourcelang}" />
	<input type="hidden" name ="check_type" value="{DATA.check_type}" />
	<input type="hidden" name ="idfile" value="{DATA.idfile}" />
	<input type="hidden" name ="savedata" value="{DATA.savedata}" />
	<!-- BEGIN: lang -->
	<table class="tab1">
		<caption>{CAPTION}</caption>
		<col class="w50"/>
		<col class="w200"/>
		<col/>
		<thead>
			<tr>
				<td>{LANG.nv_lang_nb}</td>
				<td>{LANG.nv_lang_key}</td>
				<td>{LANG.nv_lang_value}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="center">{ROW.stt}</td>
				<td class="right">{ROW.lang_key}</td>
				<td class="left"><input type="text" value="{ROW.datalang}" name="pozlang[{ROW.id}]" size="90" />
				<br />
				{ROW.sourcelang} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<!-- END: lang -->
	<div class="center">
		<input type="submit" value="{LANG.nv_admin_edit_save}" />
	</div>
</form>
<!-- END: data -->
<!-- END: main -->