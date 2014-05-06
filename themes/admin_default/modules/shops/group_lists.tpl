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
				<th>{LANG.catalog_name}</th>
				<th class="text-center w150">{LANG.inhome}</th>
				<th>{LANG.viewcat_page}</th>
				<th class="w150">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=group&amp;parentid={ROW.groupid}"><strong>{ROW.title}</strong></a>{ROW.numsubgroup} </td>
				<td class="text-center">
				<select class="form-control" id="id_inhome_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','inhome');">
					<!-- BEGIN: inhome -->
					<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
					<!-- END: inhome -->
				</select></td>
				<td>
				<select class="form-control" id="id_viewgroup_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','viewgroup');">
					<!-- BEGIN: viewgroup -->
					<option value="{VIEWGROUP.key}"{VIEWGROUP.selected}>{VIEWGROUP.title}</option>
					<!-- END: viewgroup -->
				</select></td>
				<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=group&amp;groupid={ROW.groupid}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a> &nbsp; - <i class="fa fa-trash-o">&nbsp;</i><a href="javascript:void(0);" onclick="nv_del_group({ROW.groupid})">{GLANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- END: main -->