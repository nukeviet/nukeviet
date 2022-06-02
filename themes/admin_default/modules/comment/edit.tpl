<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td colspan="2">{ROW.content}</td>
				</tr>
                <tr>
                    <td class="w250">{LANG.attach}</td>
                    <td class="form-inline">
                        <input type="text" id="post-file" name="attach" value="{ROW.attach}" class="form-control w300" readonly="readonly">
                        <button data-path="{UPLOADS_DIR}" data-currentpath="{CURRENT_DIR}" id="select-file" class="btn btn-default" type="button">{LANG.attach_choose}</button>
                        <button id="post-file-download" class="btn btn-default" type="button">{LANG.attach_view}</button>
                        <button id="post-file-remove" class="btn btn-default" type="button">{GLANG.delete}</button>
                    </td>
                </tr>
				<tr>
					<td colspan="2"><label><input type="checkbox" name="active" value="1" {ROW.status}/> {LANG.edit_active}</label> &nbsp; <label> <input type="checkbox" name="delete" value="1"/> {LANG.edit_delete} </label>&nbsp;&nbsp; <input type="hidden" value="{CID}" name="cid"/><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.delete_accept}" class="btn btn-primary" /></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#select-file").click(function(){
        nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=post-file&alt=&path=" + $(this).data('path') + "&type=file&currentpath=" + $(this).data('currentpath'), "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
    $('#post-file-remove').click(function() {
        $('#post-file').val('');
    });
    $('#post-file-download').click(function() {
        var file = $('#post-file').val();
        if (file != '') {
            window.location = '{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&downloadfile=' + encodeURIComponent(file);
        }
    });
});
</script>
<!-- END: main -->