<!-- BEGIN: main -->
<div id="list_mods">
	<form name="listlink" method="post" action="{FORM_ACTION}">
		<table class="tab1">
			<caption>{LANG.weblink_link_recent}</caption>
			<thead>
				<tr>
					<td class="w50"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
					<td>{LANG.weblink_add_title}</td>
					<td>{LANG.weblink_add_url}</td>
					<td>{LANG.weblink_add_click}</td>
					<td>{LANG.weblink_inhome}</td>
					<td class="w100 center">{LANG.weblink_method}</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6"><input type="submit" value="{LANG.weblink_method_del}"></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
					<td>{ROW.title}</td>
					<td>{ROW.url}</td>
					<td class="center">{ROW.hits_total}</td>
					<td class="center">{ROW.status}</td>
					<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{LANG.weblink_method_edit}</a> &nbsp;-&nbsp;<a href="{ROW.url_delete}">{LANG.weblink_method_del}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</form>
</div>
<!-- BEGIN: generate_page -->
<div class="center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->