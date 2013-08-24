<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<!-- BEGIN: confirm -->
<div id="list_mods">
	<form name="del_link" action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
		<table class="tab1">
			<input type="hidden" name="id" value="{ID}">
			<tbody>
				<tr>
					<td class="w50" style="padding-left:60px">{LANG.weblink_del_link_confirm}</td>
				</tr>
				<tr>
					<td class="w50" style="padding-left:80px"><label><input name="confirm" type="radio" value="1" id="confirm_0">{LANG.weblink_yes}</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><input name="confirm" type="radio" value="0" id="confirm_1"/>{LANG.weblink_no}</label></td>
				</tr>
				<tr>
					<td class="left"><input name="submit" style="width:80px;margin-left:80px" type="submit" value="{LANG.weblink_submit}" /></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<!-- END: confirm -->
<!-- END: main -->