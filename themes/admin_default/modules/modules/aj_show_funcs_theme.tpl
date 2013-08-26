<!-- BEGIN: main -->
<table class="tab1">
	<col style="width:60px;white-space:nowrap" />
	<thead>
		<tr>
			<td>{LANG.funcs_subweight}</td>
			<td>{LANG.funcs_in_submenu}</td>
			<td>{LANG.funcs_title}</td>
			<!-- BEGIN: change_alias_head -->
			<td>{LANG.funcs_alias}</td>
			<!-- END: change_alias_head -->
			<td>{LANG.custom_title}</td>
			<td>{LANG.funcs_layout}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<select name="change_weight_{ROW.func_id}" id="change_weight_{ROW.func_id}" onchange="nv_chang_func_weight({ROW.func_id});">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
				<!-- END: weight -->
			</select></td>
			<td><input name="chang_func_in_submenu_{ROW.func_id}" id="chang_func_in_submenu_{ROW.func_id}" type="checkbox" value="1" onclick="nv_chang_func_in_submenu({ROW.func_id})"{ROW.in_submenu_checked} {ROW.disabled} /></td>
			<td>{ROW.func_name}</td>
			<!-- BEGIN: change_alias -->
			<td><a href="#action" onclick="nv_change_alias({ROW.func_id},'action');">{ROW.alias}</a></td>
			<!-- END: change_alias -->
			<!-- BEGIN: show_alias -->
			<td>{ROW.alias}</td>
			<!-- END: show_alias -->
			<td><a href="#action" onclick="nv_change_custom_name({ROW.func_id},'action');">{ROW.func_custom_name}</a></td>
			<td>{ROW.layout}</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->