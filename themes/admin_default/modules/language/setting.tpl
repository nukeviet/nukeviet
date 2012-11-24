<!-- BEGIN: info -->
<br /><br /><p align="center"><strong>{INFO}</strong></p>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: info -->
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<table class="tab1 fixtab">
		<caption>{LANG.nv_lang_show}</caption>
		<tr class="thead_box">
			<td style="width:50px">{LANG.nv_lang_key}</td>
			<td style="width:180px">{LANG.nv_lang_name}</td>
			<td style="width:120px">{LANG.nv_lang_slsite}</td>
			<td style="width:120px">{LANG.nv_lang_sladm}</td>
			<td></td>
		</tr>
	</table>
	<table class="tab1 fixtab">
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<td style="width: 50px; text-align: center">{ROW.key}</td>
				<td style="width: 180px">{ROW.name}</td>
				<td style="width: 120px; text-align: center"><input name="allow_sitelangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_sitelangs} /></td>
				<td style="width: 120px; text-align: center"><input name="allow_adminlangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_adminlangs} /></td>
				<td class="center">{ROW.arr_lang_func}</td>
			</tr>
		</tbody>
		<!-- END: loop -->
	</table>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessshow" value="{CHECKSESSSHOW}" />
	<center><input type="submit" value="{LANG.nv_admin_edit_save}" /></center>
</form>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<table class="tab1">
		<caption>{LANG.nv_setting_read}</caption>
		<!-- BEGIN: type -->
		<tr>
			<td></td>
			<td><input name="read_type" value="{TYPE.key}" type="radio"{TYPE.checked} />{TYPE.title}</td>
		</tr>
		<!-- END: type -->
	</table>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessseting" value="{CHECKSESSSETING}" />
	<center><input type="submit" value="{LANG.nv_admin_edit_save}" /></center>
</form>
<!-- END: main -->