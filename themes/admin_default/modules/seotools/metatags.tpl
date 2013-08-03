<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.metaTagsGroupName}</td>
				<td>{LANG.metaTagsGroupValue} (*)</td>
				<td>{LANG.metaTagsContent} (**)</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="metaGroupsName[]">
					<option value="name"{DATA.n_selected}>name</option>
					<option value="http-equiv"{DATA.h_selected}>http-equiv</option>
				</select></td>
				<td><input class="w200" type="text" value="{DATA.value}" name="metaGroupsValue[]" /></td>
				<td><input class="w400" type="text" value="{DATA.content}" name="metaContents[]" /></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
	<div style="margin: 10px auto;">
		*: {NOTE}
	</div>
	<div style="margin: 10px auto;">
		**: {VARS}
	</div>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
		<input type="submit" name="submit" value="{LANG.submit}" />
	</div>
</form>
<!-- END: main -->