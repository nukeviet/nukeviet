<!-- BEGIN: main -->
<!-- BEGIN: groupnav -->
<div class="divbor1">
	<!-- BEGIN: loop -->
	{GROUP_NAV}
	<!-- END: loop -->
</div>
<!-- END: groupnav -->
<!-- BEGIN: data -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:40px;">{LANG.weight}</td>
			<td>{LANG.catalog_name}</td>
			<td align="center" style="width:120px;">{LANG.inhome}</td>
			<td>{LANG.viewcat_page}</td>
			<td style="width:100px;"></td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td align="center">
			<select id="id_weight_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=group&amp;parentid={ROW.groupid}"><strong>{ROW.title}</strong></a>{ROW.numsubgroup} </td>
			<td align="center">
			<select id="id_inhome_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','inhome');">
				<!-- BEGIN: inhome -->
				<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
				<!-- END: inhome -->
			</select></td>
			<td align="left">
			<select id="id_viewgroup_{ROW.groupid}" onchange="nv_chang_group('{ROW.groupid}','viewgroup');">
				<!-- BEGIN: viewgroup -->
				<option value="{VIEWGROUP.key}"{VIEWGROUP.selected}>{VIEWGROUP.title}</option>
				<!-- END: viewgroup -->
			</select></td>
			<td align="right"><em class="icon-edit icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=group&amp;groupid={ROW.groupid}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a> &nbsp; - <em class="icon-trash icon-large">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_group({ROW.groupid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: data -->
<!-- END: main -->