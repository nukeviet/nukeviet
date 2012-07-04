<!-- BEGIN: main -->
<div id="list_mods">
	<form name="listlink" method="post" action="{FORM_ACTION}">
		<table class="tab1">
			<caption>{LANG.weblink_link_recent}</caption>
			<thead>
				<tr>
					<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
					<td>{LANG.weblink_add_title}</td>
					<td>{LANG.weblink_add_url}</td>
					<td>{LANG.weblink_add_click}</td>
					<td>{LANG.weblink_inhome}</td>
					<td width="100" align="center">{LANG.weblink_method}</td>
				</tr>
			</thead>
			<!-- BEGIN: loop -->
			<tbody{ROW.class}>
				<tr>
					<td><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
					<td>{ROW.title}</td>
					<td>{ROW.url}</td>
					<td align="center">{ROW.hits_total}</td>
					<td align="center">{ROW.status}</td>
					<td align="center"><span class="edit_icon"><a href="{ROW.url_edit}">{LANG.weblink_method_edit}</a></span>
					&nbsp;-&nbsp;<a href="{ROW.url_delete}">{LANG.weblink_method_del}</a></span></td>
				</tr>
			</tbody>
			<!-- END: loop -->
			<tfoot>
				<tr>
					<td colspan="8">
						<input type="submit" value="{LANG.weblink_method_del}">
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
<!-- BEGIN: generate_page -->
<div align="center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->
<!-- END: main -->
