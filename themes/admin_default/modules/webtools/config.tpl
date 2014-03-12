<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<col style="width:50%" />
		<col style="width:50%" />
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><strong>{LANG.autocheckupdate}</strong></td>
				<td><input type="checkbox" value="1" name="autocheckupdate" {AUTOCHECKUPDATE} /></td>
			</tr>
			<tr>
				<td><strong>{LANG.updatetime}</strong></td>
				<td>
				<select name="autoupdatetime">
					<!-- BEGIN: updatetime -->
					<option value="{VALUE}" {SELECTED}>{TEXT} </option>
					<!-- END: updatetime -->
				</select> ({LANG.hour}) </td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->