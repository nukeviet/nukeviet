<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><a href="{URL_ADD}" title="{LANG.add_row_title}" class="button button-h">{LANG.add_row_title}</a></td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<colgroup>
		<col span="3" />
		<col span="2" class="w100"/>
	</colgroup>
	<thead>
		<tr class="center">
			<td>{LANG.part_row_title}</td>
			<td>{GLANG.email}</td>
			<td>{GLANG.phonenumber}</td>
			<td>Fax</td>
			<td>{GLANG.status}</td>
			<td>{GLANG.actions}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: row -->
		<tr>
			<td><a href="{ROW.url_part}" target="_blank">{ROW.full_name}</a></td>
			<td>{ROW.email}</td>
			<td>{ROW.phone}</td>
			<td>{ROW.fax}</td>
			<td class="center">
			<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
				<!-- BEGIN: status -->
				<option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
				<!-- END: status -->
			</select></td>
			<td class="center">
				<em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
				<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_row_del('{ROW.id}')">{GLANG.delete}</a>
			</td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<!-- END: main -->