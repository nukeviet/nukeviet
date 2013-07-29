<!-- BEGIN: main -->
<div id="list_mods">
	<!-- BEGIN: data -->
	<form name="delbroken" method="post" action="{FORM_ACTION}">
		<table class="tab1">
			<caption>{LANG.weblink_link_recent}</caption>
			<thead>
				<tr>
					<td><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></td>
					<td>{LANG.weblink_add_title}</td>
					<td>{LANG.weblink_add_url}</td>
					<td>{LANG.weblink_link_broken_status}</td>
					<td>{LANG.weblink_inhome}</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8"><input type="submit" value="{LANG.link_broken_out}"></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input type="checkbox" name="idcheck[]" value="{ROW.id}"></td>
					<td>{ROW.title}</td>
					<td>{ROW.url}</td>
					<td>{ROW.type}</td>
					<td><a class="edit_icon" href="{ROW.url_edit}">{LANG.weblink_method_edit}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</form>
	<!-- END: data -->
	<!-- BEGIN: empty -->
	<table class="tab1">
		<tbody>
			<tr>
				<td class="center">{LANG.weblink_link_broken}</td>
			</tr>
		</tbody>
	</table>
	<!-- END: empty -->
</div>
<!-- BEGIN: generate_page -->
<p class="center">
	{GENERATE_PAGE}
</p>
<!-- END: generate_page -->
<!-- END: main -->