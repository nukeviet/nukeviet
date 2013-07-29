<!-- BEGIN: main -->
<table class="tab1">
	<colgroup>
		<col class="w50">
		<col>
		<col class="w150">
		<col class="w100">
	</colgroup>
	<thead>
		<tr class="center">
			<td>{LANG.aabout3}</td>
			<td>{LANG.aabout2}</td>
			<td>{LANG.aabout4}</td>
			<td>{LANG.feature}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: row -->
		<tr>
			<td class="center">
			<select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
				<!-- END: weight -->
			</select></td>
			<td>{ROW.title}</td>
			<td class="center">
			<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
				<!-- BEGIN: status -->
				<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
				<!-- END: status -->
			</select></td>
			<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;&nbsp; <a class="delete_icon" href="javascript:void(0);" onclick="nv_module_del({ROW.id});">{GLANG.delete}</a></td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<!-- END: main -->