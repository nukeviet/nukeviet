<!-- BEGIN: main -->
<!-- BEGIN: nowrite -->
<div class="quote">
	<blockquote class="error"> {TITLE} </blockquote>
</div>
<div class="codecontent">
	{CONTENT}
</div>
<!-- END: nowrite -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<thead>
			<tr class="center">
				<td>{LANG.robots_number}</td>
				<td>{LANG.robots_filename}</td>
				<td>{LANG.robots_type}</td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td class="center" colspan="3"><input type="submit" name="submit" value="{LANG.submit}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="center">{DATA.number}</td>
				<td>{DATA.filename}</td>
				<td>
				<select name="filename[{DATA.filename}]">
					<!-- BEGIN: option -->
					<option value="{OPTION.value}" {OPTION.selected}>{OPTION.title}</option>
					<!-- END: option -->
				</select></td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: other -->
			<tr>
				<td class="center">{DATA.number}</td>
				<td><input class="w300" type="text" value="{DATA.filename}" name="fileother[{DATA.number}]" /></td>
				<td><select name="optionother[{DATA.number}]">
					<!-- BEGIN: option -->
					<option value="{OPTION.value}" {OPTION.selected}>{OPTION.title}</option>
					<!-- END: option -->
				</select></td>
			</tr>
			<!-- END: other -->
		</tbody>
	</table>
</form>
<!-- END: main -->