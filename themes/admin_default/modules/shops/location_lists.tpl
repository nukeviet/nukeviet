<!-- BEGIN: main -->
<!-- BEGIN: locationnav -->
<div class="divbor1">
	<!-- BEGIN: loop -->
	{LOCATION_NAV}
	<!-- END: loop -->
</div>
<!-- END: locationnav -->

<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="w100">{LANG.weight}</th>
				<th>{LANG.location_name}</th>
				<th class="w150">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.id}" onchange="nv_chang_location('{ROW.id}','weight');">
					<!-- BEGIN: weight -->
					<option value="{OPTION.key}"{OPTION.selected}>{OPTION.title}</option>
					<!-- END: weight -->
				</select></td>
				<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=location&amp;parentid={ROW.id}"><strong>{ROW.title}</strong></a>{ROW.numsub} </td>
				<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=location&amp;id={ROW.id}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a> &nbsp; - <i class="fa fa-trash-o fa-lg">&nbsp;</i><a href="javascript:void(0);" onclick="nv_del_location({ROW.id})">{GLANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr class="text-center">
				<td colspan="3">{GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<!-- END: data -->
<!-- END: main -->