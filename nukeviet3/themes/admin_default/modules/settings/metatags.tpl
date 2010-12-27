<!-- BEGIN: main -->
<form action="" method="post">
<table class="tab1" summary="">
	<thead>
		<tr>
			<td>{LANG.metaTagsGroupName}</td>
            <td>{LANG.metaTagsGroupValue} (*)</td>
			<td>{LANG.metaTagsContent} (**)</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody class="{DATA.class}">
		<tr>
			<td><select name="metaGroupsName[]">
                <option value="name"{DATA.n_selected}>name</option>
                <option value="http-equiv"{DATA.h_selected}>http-equiv</option>
            </select></td>
            <td><input type="text" value="{DATA.value}" name="metaGroupsValue[]" style="width: 200px;" /></td>
			<td><input type="text" value="{DATA.content}" name="metaContents[]" style="width: 400px;" /></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<div style="margin: 10px auto;">*: {NOTE}</div>
<div style="margin: 10px auto;">**: {VARS}</div>
<div style="width: 200px; margin: 10px auto; text-align: center;"><input type="submit" name="submit" value="{LANG.submit}" /></div>
</form>
<!-- END: main -->
