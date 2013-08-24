<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:60px;">{LANG.weight}</td>
			<td>{LANG.name}</td>
			<td>{LANG.link}</td>
			<td style="width:120px;"></td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">
			<select id="id_weight_{ROW.sourceid}" onchange="nv_chang_sources('{ROW.sourceid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td>{ROW.title}</td>
			<td>{ROW.link}</td>
			<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;-&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_del_source({ROW.sourceid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- BEGIN: generate_page -->
<table class="tab1">
	<tbody>
		<tr>
			<td>{GENERATE_PAGE}</td>
		</tr>
	</tbody>
</table>
<!-- END: generate_page -->
<!-- END: main -->