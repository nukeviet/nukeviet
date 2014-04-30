<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<colgroup>
			<col class="w200" />
		</colgroup>
		<tfoot>
			<tr>
				<td colspan="2" class="center"><input name="submit" type="submit" value="{LANG.save}" /></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td> {LANG.show_logo} </td>
				<td><input type="checkbox" name="show_logo" value="1" {CONFIG_THEME.show_logo}/></td>
			</tr>
			<tr>
				<td> {LANG.show_site_name} </td>
				<td><input type="checkbox" name="show_site_name" value="1" {CONFIG_THEME.show_site_name}/></td>
			</tr>
			<tr>
				<td> {LANG.module_in_menu} </td>
				<td>
					<!-- BEGIN: module -->
						<div  class="w150" style="float: left"><label><input type="checkbox" name="module_in_menu[]" value="{MODULE.modname}" {MODULE.checked}/> {MODULE.custom_title}</label></div>
					<!-- END: module -->
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->