<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.metaTagsGroupName}</td>
				<td>{LANG.metaTagsGroupValue} (*)</td>
				<td>{LANG.metaTagsContent} (**)</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" class="center"><input type="submit" name="submit" value="{LANG.submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="metaGroupsName[]">
					<option value="name"{DATA.n_selected}>name</option>
					<option value="property"{DATA.p_selected}>property</option>
					<option value="http-equiv"{DATA.h_selected}>http-equiv</option>
				</select></td>
				<td><input class="w200" type="text" value="{DATA.value}" name="metaGroupsValue[]" /></td>
				<td><input class="w400" type="text" value="{DATA.content}" name="metaContents[]" /></td>
			</tr>
			<!-- END: loop -->
			<tr>
				<td colspan="2" class="right">{LANG.metaTagsOgp}</td>
				<td><input type="checkbox" value="1" name="metaTagsOgp" {METATAGSOGPCHECKED}/></td>
			</tr>
		</tbody>
	</table>
	<div style="margin: 10px auto;">
		*: {NOTE}
	</div>
	<div style="margin: 10px auto;">
		**: {VARS}
	</div>
	<div style="margin: 10px auto;">
		***: {LANG.metaTagsOgpNote}
	</div>
</form>
<!-- END: main -->
