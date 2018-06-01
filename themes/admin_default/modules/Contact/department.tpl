<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover" data-is-default="fa-check-square-o" data-not-default="fa-square-o">
		<colgroup>
		<col class="w100" />
			<col span="4" />
			<col class="w150" />
			<col class="w250"/>
		</colgroup>
		<thead>
			<tr class="text-center">
			    <th>{LANG.number}</th>
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
				<td colspan="7"><a href="{URL_ADD}" title="{LANG.add_row_title}" class="btn btn-default">{LANG.add_row_title}</a></td>
			</tr>
		</tfoot>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
			    <td align="center">
                    <select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}');" class="form-control w60">
                        <!-- BEGIN: option -->
                        <option value="{WEIGHT.value}" {WEIGHT.selected}>{WEIGHT.value}</option>
                        <!-- END: option -->
                    </select>
                </td>
				<td><a href="{ROW.url_department}" title="{LANG.url_for_iframe}">{ROW.full_name}</a></td>
				<td>{ROW.email}</td>
				<td>{ROW.phone}</td>
				<td>{ROW.fax}</td>
				<td class="text-center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');" class="form-control">
					<!-- BEGIN: status -->
					   <option value="{STATUS.key}"{STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
				</select></td>
				<td class="text-center">
                    <a href="javascript:void(0);" onclick="nv_change_default('{ROW.id}',this)"><em class="fa <!-- BEGIN: check -->fa-check-square-o<!-- END: check --><!-- BEGIN: notcheck -->fa-square-o<!-- END: notcheck --> fa-lg">&nbsp;</em> {LANG.is_default}</a> &nbsp;
					<a href="{ROW.url_edit}"><em class="fa fa-edit fa-lg">&nbsp;</em> {GLANG.edit}</a> &nbsp;
					<a href="javascript:void(0);" onclick="nv_del_department('{ROW.id}')"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<!-- END: main -->