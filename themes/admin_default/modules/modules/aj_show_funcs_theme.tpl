<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col style="width:80px;white-space:nowrap" />
		<thead>
			<tr>
				<th>{LANG.funcs_subweight}</th>
				<th>{LANG.funcs_in_submenu}</th>
				<th>{LANG.funcs_title}</th>
				<!-- BEGIN: change_alias_head -->
				<th>{LANG.funcs_alias}</th>
				<!-- END: change_alias_head -->
				<th>{LANG.custom_title}</th>
				<th>{LANG.site_title}</th>
				<th>{LANG.funcs_layout}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="change_weight_{ROW.func_id}" id="change_weight_{ROW.func_id}" onchange="nv_chang_func_weight({ROW.func_id});" class="form-control">
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
				<td><a href="#action" onclick="nv_change_custom_name({ROW.func_id}, 'action');">{ROW.func_custom_name}</a></td>
				<td><a href="#action" onclick="nv_change_site_title({ROW.func_id}, 'action');">{ROW.func_site_title}</a></td>
				<td>{ROW.layout}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->