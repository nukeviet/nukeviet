<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col span="3" />
			<col span="2" class="w100"/>
		</colgroup>
		<thead>
			<tr class="text-center">
				<th>{LANG.part_row_title}</th>
				<th>{GLANG.email}</th>
				<th>{GLANG.phonenumber}</th>
				<th>Fax</th>
				<th>{GLANG.status}</th>
				<th>{GLANG.actions}</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6"><a href="{URL_ADD}" title="{LANG.add_row_title}" class="btn btn-default">{LANG.add_row_title}</a></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td><a title="{LANG.url_for_iframe}" href="{ROW.url_part}" target="_blank">{ROW.full_name}</a></td>
				<td>{ROW.email}</td>
				<td>{ROW.phone}</td>
				<td>{ROW.fax}</td>
				<td class="text-center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');" class="form-control w100">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
				</select></td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_department('{ROW.id}')">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<!-- END: main -->