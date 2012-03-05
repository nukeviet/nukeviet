<!-- BEGIN: main -->
<div class="quote" style="width:98%">
	<blockquote><span>{LANG.vmodule_blockquote}</span></blockquote>
</div>
<div class="clear"></div>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
	<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
	<input name="checkss" type="hidden" value="{CHECKSS}" />
	<table class="tab1">
		<tbody>
			<tr>
				<td align="right" style="width:250px"><strong>{LANG.vmodule_name}: </strong></td>
				<td><input style="width:450px" name="title" type="text" value="{TITLE}" maxlength="255" /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
			<td align="right"><strong>{LANG.vmodule_file}: </strong></td>
				<td>
					<select name="module_file">
						<option value="">{LANG.vmodule_select}</option>
						<!-- BEGIN: modfile -->
						<option value="{MODFILE.key}"{MODFILE.selected}>{MODFILE.key}</option>
						<!-- END: modfile -->
					</select>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td valign="top" align="right"><br /><strong>{LANG.vmodule_note}:</strong></td>
				<td><textarea style="width: 450px" name="note" cols="80" rows="5">{NOTE}</textarea></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td class="center" colspan="2"><input name="submit1" type="submit" value="{GLANG.submit}" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->