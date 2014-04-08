<!-- BEGIN: main -->
<!-- BEGIN: catnav -->
<div class="divbor1">
	<!-- BEGIN: loop -->
	{CAT_NAV}
	<!-- END: loop -->
</div>
<!-- END: catnav -->
<!-- BEGIN: data -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td class="w50">{LANG.weight}</td>
			<td>{LANG.catalog_name}</td>
			<td class="w150">{LANG.inhome}</td>
			<td>{LANG.viewcat_page}</td>
			<td class="w100">{LANG.numlinks}</td>
			<td class="w250">{LANG.function}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">
			<select id="id_weight_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=cat&amp;parentid={ROW.catid}"> <strong>{ROW.title}</strong> </a> {ROW.numsubcat} </td>
			<td class="center">
			<select id="id_inhome_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','inhome');">
				<!-- BEGIN: inhome -->
				<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
				<!-- END: inhome -->
			</select></td>
			<td align="left">
			<select id="id_viewcat_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','viewcat');">
				<!-- BEGIN: viewcat -->
				<option value="{VIEWCAT.key}"{VIEWCAT.selected}>{VIEWCAT.title}</option>
				<!-- END: viewcat -->
			</select></td>
			<td class="center">
			<select id="id_numlinks_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','numlinks');">
				<!-- BEGIN: numlinks -->
				<option value="{NUMLINKS.key}"{NUMLINKS.selected}>{NUMLINKS.title}</option>
				<!-- END: numlinks -->
			</select></td>
			<td class="center">
				<em class="icon-plus icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=content&amp;catid={ROW.catid}&amp;parentid={ROW.parentid}">{LANG.content_add}</a>
				&nbsp;
				<em class="icon-edit icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=cat&amp;catid={ROW.catid}&amp;parentid={ROW.parentid}#edit">{GLANG.edit}</a>
				&nbsp;
				<em class="icon-trash icon-large">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_cat({ROW.catid})">{GLANG.delete}</a>
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: data -->
<!-- END: main -->