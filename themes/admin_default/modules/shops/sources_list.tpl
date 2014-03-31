<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td style="width:60px;">{LANG.weight}</td>
			<td>{LANG.source_title}</td>
			<td>{LANG.link}</td>
			<td style="width:120px;">&nbsp;</td>
		</tr>
	</thead>
	<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td> {GENERATE_PAGE} </td>
			</tr>
		</tfoot>
	<!-- END: generate_page -->
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td align="center">
			<select id="id_weight_{ROW.sourceid}" onchange="nv_chang_sources('{ROW.sourceid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td>{ROW.title}</td>
			<td>{ROW.link}</td>
			<td align="center"><em class="icon-edit icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sources&amp;sourceid={ROW.sourceid}#edit">{GLANG.edit}</a> &nbsp;-&nbsp; <em class="icon-trash icon-large">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_source({ROW.sourceid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->