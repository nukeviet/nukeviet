<!-- BEGIN: main -->
<!-- BEGIN: catnav -->
<div class="divbor1">
	<!-- BEGIN: loop -->
	{CAT_NAV}
	<!-- END: loop -->
</div>
<!-- END: catnav -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="text-center">
				<th class="w100">{LANG.weight}</th>
				<th>{LANG.catalog_name}</th>
				<th class="w150">{LANG.inhome}</th>
				<th>{LANG.viewcat_page}</th>
				<th class="w100">{LANG.numlinks}</th>
				<th class="w250">{LANG.function}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=cat&amp;parentid={ROW.catid}"> <strong>{ROW.title}</strong> </a> {ROW.numsubcat} </td>
				<td class="text-center">
				<select class="form-control" id="id_inhome_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','inhome');">
					<!-- BEGIN: inhome -->
					<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
					<!-- END: inhome -->
				</select></td>
				<td align="left">
				<select class="form-control" id="id_viewcat_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','viewcat');">
					<!-- BEGIN: viewcat -->
					<option value="{VIEWCAT.key}"{VIEWCAT.selected}>{VIEWCAT.title}</option>
					<!-- END: viewcat -->
				</select></td>
				<td class="text-center">
				<select class="form-control" id="id_numlinks_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','numlinks');">
					<!-- BEGIN: numlinks -->
					<option value="{NUMLINKS.key}"{NUMLINKS.selected}>{NUMLINKS.title}</option>
					<!-- END: numlinks -->
				</select></td>
				<td class="text-center">
					<i class="fa fa-plus">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=content&amp;catid={ROW.catid}&amp;parentid={ROW.parentid}">{LANG.content_add}</a>
					&nbsp;
					<i class="fa fa-edit">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=cat&amp;catid={ROW.catid}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a>
					&nbsp;
					<i class="fa fa-trash-o">&nbsp;</i><a href="javascript:void(0);" onclick="nv_del_cat({ROW.catid})">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- END: main -->