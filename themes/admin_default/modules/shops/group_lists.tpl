<!-- BEGIN: main -->
<!-- BEGIN: groupnav -->
<div class="divbor1">
	<!-- BEGIN: loop -->
	{GROUP_NAV}
	<!-- END: loop -->
</div>
<!-- END: groupnav -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="w100">{LANG.weight}</th>
				<th>{LANG.group_name}</th>
				<th>{LANG.groupview_page}</th>
				<th class="text-center">{LANG.inhome}</th>
				<th class="text-center">{LANG.indetail} <span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.indetail_note}">&nbsp;</span></th>
				<th class="text-center">{LANG.in_order}</th>
				<th class="w150">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: weight -->
				</select></td>
				<td>
					<a data-toggle="tooltip" title="" data-original-title="{ROW.description}" href="{ROW.group_link}"><strong>{ROW.title}</strong></a>
					{ROW.numsubgroup}
				</td>
				<td>
					<select class="form-control" id="id_viewgroup_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','viewgroup');">
						<!-- BEGIN: viewgroup -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: viewgroup -->
					</select>
				</td>
				<td class="text-center">
					<select class="form-control" id="id_inhome_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','inhome');">
						<!-- BEGIN: inhome -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: inhome -->
					</select>
				</td>
				<td class="text-center">
					<select class="form-control" id="id_indetail_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','indetail');">
						<!-- BEGIN: indetail -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: indetail -->
					</select>
				</td>
				<td class="text-center">
					<select class="form-control" id="id_in_order_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','in_order');">
						<!-- BEGIN: in_order -->
						<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
						<!-- END: in_order -->
					</select>
				</td>
				<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=group&amp;groupid={ROW.groupid}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a> &nbsp; - <i class="fa fa-trash-o fa-lg">&nbsp;</i><a href="javascript:void(0);" onclick="nv_del_group({ROW.groupid})">{GLANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- END: main -->