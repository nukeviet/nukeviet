<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td><textarea name="content" style="width:600px;height:100px">{ROW.content}</textarea></td>
			</tr>
			<tr>
				<td><label><input type="checkbox" name="active" value="1" {ROW.status}/> {LANG.edit_active}</label> &nbsp; <label> <input type="checkbox" name="delete" value="1"/> {LANG.edit_delete} </label>&nbsp;&nbsp; <input type="hidden" value="{CID}" name="cid"/><input type="submit" name="submit" value="{LANG.delete_accept}"/></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->