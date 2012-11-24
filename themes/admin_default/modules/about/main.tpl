<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td class="center" style="width:90px">{LANG.aabout3}</td>
			<td>{LANG.aabout2}</td>
			<td class="center" style="width:150px">{LANG.aabout4}</td>
			<td class="center" style="width:100px">{LANG.feature}</td>
		</tr>
	</thead>
	<!-- BEGIN: row -->
	<tbody{ROW.class}>
		<tr>
			<td class="center">
				<select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}');">
					<!-- BEGIN: weight --><option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
					<!-- END: weight -->
				</select>
			</td>
			<td>{ROW.title}</td>
			<td class="center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
					<!-- BEGIN: status --><option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
					<!-- END: status -->
				</select>
			</td>
			<td class="center">
				<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
				&nbsp;&nbsp;
				<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del({ROW.id});">{GLANG.delete}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: row -->
</table>
<!-- END: main -->
