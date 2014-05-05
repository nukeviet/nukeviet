<!-- BEGIN: main -->
<table class="tab1">
	<colgroup>
		<col class="w50">
		<col span="1">
		<col span="2" class="w150">
	</colgroup>
	<thead>
		<tr class="center">
			<td>{LANG.order}</td>
			<td>{LANG.title}</td>
			<td>{LANG.status}</td>
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
			<td><a href="{ROW.url_view}" title="{ROW.title}" target="_blank">{ROW.title}</a></td>
			<td class="center">
			<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
				<!-- BEGIN: status -->
				<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
				<!-- END: status -->
			</select></td>
			<td class="center">
				<em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
				<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_module_del({ROW.id});">{GLANG.delete}</a>
			</td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<!-- END: main -->