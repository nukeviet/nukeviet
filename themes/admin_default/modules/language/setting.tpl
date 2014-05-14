<!-- BEGIN: info -->
<br />
<br />
<p class="text-center">
	<strong>{INFO}</strong>
</p>
<meta http-equiv="Refresh" content="10;URL={URL}" />
<!-- END: info -->
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.nv_lang_show} </caption>
			<colgroup>
				<col class="w50">
				<col class="w50">
				<col span="5">
			</colgroup>
			<thead>
				<tr class="text-center">
					<th>&nbsp;</th>
					<th>{LANG.nv_lang_key}</th>
					<th>{LANG.nv_lang_name}</th>
					<th>{LANG.nv_lang_native_name}</th>
					<th>{LANG.nv_lang_slsite}</th>
					<th>{LANG.nv_lang_sladm}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">{ROW.number}</td>
					<td class="text-center">{ROW.key}</td>
					<td>{ROW.language}</td>
					<td>{ROW.name}</td>
					<td class="text-center"><input name="allow_sitelangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_sitelangs} /></td>
					<td class="text-center"><input name="allow_adminlangs[]" value="{ROW.key}" type="checkbox" {ROW.allow_adminlangs} /></td>
					<td class="text-center">{ROW.arr_lang_func}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessshow" value="{CHECKSESSSHOW}" />
	<div class="text-center">
		<input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" />
	</div>
</form>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.nv_setting_read} </caption>
			<!-- BEGIN: type -->
			<tr>
				<td><input name="read_type" value="{TYPE.key}" type="radio"{TYPE.checked} /> {TYPE.title}</td>
			</tr>
			<!-- END: type -->
		</table>
	</div>
	<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
	<input type="hidden" name ="checksessseting" value="{CHECKSESSSETING}" />
	<div class="text-center">
		<input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" />
	</div>
</form>
<!-- END: main -->