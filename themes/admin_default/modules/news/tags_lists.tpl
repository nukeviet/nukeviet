<!-- BEGIN: main -->
<table class="tab1">
	<colgroup>
		<col class="w50">
		<col span="2">
		<col span="2" class="w100">
	</colgroup>
	<thead>
		<tr class="center">
			<td>{LANG.number}</td>
			<td>{LANG.alias}</td>
			<td>{LANG.keywords}</td>
			<td>{LANG.numlinks}</td>
			<td>&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">{ROW.number}</td>
			<td><a href="{ROW.link}">{ROW.alias}</a></td>
			<td>{ROW.keywords}</td>
			<td class="center">{ROW.numnews}</td>
			<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;-&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_del_tags({ROW.tid})">{GLANG.delete}</a></td>
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
<!-- END: main -->