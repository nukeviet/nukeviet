<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="quote">
	<blockquote><span>{LANG.no_row_contact}</span></blockquote>
</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<form name="myform" id="myform" method="post" action="{FORM_ACTION}">
	<table class="tab1">
		<colgroup>
			<col style="width:30px" />
			<col style="width:30px" />
			<col span="4" />
		</colgroup>
		<thead>
			<tr>
				<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);" /></td>
				<td colspan="2">{LANG.name_user_send_title}</td>
				<td>{LANG.part_row_title}</td>
				<td>{LANG.title_send_title}</td>
				<td>&nbsp;</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);" /></td>
				<td colspan="5"><em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_submit(document.myform, 'sends[]');">{LANG.bt_del_row_title}</a> &nbsp; <em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0)" onclick="nv_delall_submit();">{LANG.delall}</a></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><input name="sends[]" type="checkbox" value="{ROW.id}" onclick="nv_UncheckAll(this.form, 'sends[]', 'check_all[]', this.checked);" /></td>
				<td {ROW.style} {ROW.onclick}><img alt="{ROW.status}" src="{ROW.image.0}" width="{ROW.image.1}" height="{ROW.image.2}" /></td>
				<td {ROW.style} {ROW.onclick}> {ROW.sender_name}</td>
				<td {ROW.style} {ROW.onclick}> {ROW.path}</td>
				<td {ROW.style} {ROW.onclick}> {ROW.title}</td>
				<td {ROW.style} {ROW.onclick}> {ROW.time}</td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</form>
<!-- BEGIN: generate_page -->
<table class="tab1">
	<tr>
		<td class="center">{GENERATE_PAGE}</td>
	</tr>
</table>
<!-- END: generate_page -->
<!-- END: data -->
<!-- END: main -->