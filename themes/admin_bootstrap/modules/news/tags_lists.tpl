<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50">
			<col span="2">
			<col class="w50">
			<col class="w150">
		</colgroup>
		<thead>
			<tr class="text-center">
				<th>{LANG.number}</th>
				<th>{LANG.alias}</th>
				<th>{LANG.keywords}</th>
				<th>{LANG.numlinks}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.number}</td>
				<td><a href="{ROW.link}">{ROW.alias}</a></td>
				<td>{ROW.keywords}</td>
				<td class="text-center">{ROW.numnews}</td>
				<td class="text-center">
					<em class="fa fa-edit">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_tags({ROW.tid})">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: other -->
		<tfoot>
			<tr>
				<td colspan="5">{LANG.alias_search}</td>
			</tr>
		</tfoot>
		<!-- END: other -->
	</table>
</div>
<!-- END: main -->