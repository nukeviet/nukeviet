<!-- BEGIN: main -->
<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.no_row_contact}</div>
<!-- END: empty -->
<!-- BEGIN: data -->
<form name="myform" id="myform" method="post" action="{FORM_ACTION}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col width="30" />
				<col span="4" />
				<col class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);" /></th>
					<th colspan="2">{LANG.name_user_send_title}</th>
					<th>{LANG.part_row_title}</th>
                    <th>{LANG.cat}</th>
					<th>{LANG.title_send_title}</th>
					<th>{LANG.send_time}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'sends[]', 'check_all[]',this.checked);" /></td>
					<td colspan="6">
                        <a class="btn btn-default" href="javascript:void(0);" onclick="nv_del_submit(document.myform, 'sends[]');"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.bt_del_row_title}</a> &nbsp;
                        <a class="btn btn-default" href="javascript:void(0)" onclick="nv_delall_submit();"><em class="fa fa-trash-o">&nbsp;</em> {LANG.delall}</a> &nbsp;
                        <a class="btn btn-default" href="javascript:void(0);" onclick="multimark('#myform','unread');"><em class="fa fa-bookmark">&nbsp;</em> {LANG.mark_as_unread}</a> &nbsp;
                        <a class="btn btn-default" href="javascript:void(0);" onclick="multimark('#myform','read');"><em class="fa fa-bookmark-o">&nbsp;</em> {LANG.mark_as_read}</a>&nbsp;
                    </td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: row -->
				<tr>
					<td class="text-center"><input name="sends[]" type="checkbox" value="{ROW.id}" onclick="nv_UncheckAll(this.form, 'sends[]', 'check_all[]', this.checked);" /></td>
					<td {ROW.style} {ROW.onclick}><img alt="{ROW.status}" src="{ROW.image.0}" width="{ROW.image.1}" height="{ROW.image.2}" /></td>
					<td {ROW.style} {ROW.onclick}> {ROW.sender_name}</td>
					<td {ROW.style} {ROW.onclick}> {ROW.path}</td>
                    <td {ROW.style} {ROW.onclick}> {ROW.cat}</td>
					<td {ROW.style} {ROW.onclick}> {ROW.title}</td>
					<td {ROW.style} {ROW.onclick}> {ROW.time}</td>
				</tr>
				<!-- END: row -->
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
	<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: data -->
<script type="text/javascript">
	function open_browse_forward() {
		nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=forward&id=1', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
	}
</script>
<!-- END: main -->