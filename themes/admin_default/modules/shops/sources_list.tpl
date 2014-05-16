<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th class="w100">{LANG.weight}</th>
				<th>{LANG.source_title}</th>
				<th>{LANG.link}</th>
				<th class="w150">&nbsp;</th>
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
				<td class="text-center">
				<select class="form-control" id="id_weight_{ROW.sourceid}" onchange="nv_chang_sources('{ROW.sourceid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td>{ROW.title}</td>
				<td>{ROW.link}</td>
				<td class="text-center"><i class="fa fa-edit">&nbsp;</i><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sources&amp;sourceid={ROW.sourceid}#edit">{GLANG.edit}</a> &nbsp;-&nbsp; <i class="fa fa-trash-o">&nbsp;</i><a href="javascript:void(0);" onclick="nv_del_source({ROW.sourceid})">{GLANG.delete}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->