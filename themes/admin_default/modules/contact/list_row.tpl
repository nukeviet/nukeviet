<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><a href="{URL_ADD}" title="{LANG.add_row_title}" class="button1"><span><span>{LANG.add_row_title}</span></span></a></td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<thead>
		<tr>
			<td>{LANG.part_row_title}</td>
			<td>{GLANG.email}</td>
			<td>{GLANG.phonenumber}</td>
			<td>Fax</td>
			<td class="center" style="width:100px">{GLANG.status}</td>
			<td class="center" style="width:100px">{GLANG.actions}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.full_name}</td>
			<td>{ROW.email}</td>
			<td>{ROW.phone}</td>
			<td>{ROW.fax}</td>
			<td class="center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
				</select>
			</td>
			<td class="center">
				<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
				&nbsp;-&nbsp;
				<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_row_del('{ROW.id}')">{GLANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
</table>
<!-- END: main -->
