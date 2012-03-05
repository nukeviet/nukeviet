<!-- BEGIN: main -->
<table summary="{CONTENT.caption}" class="tab1">
<col style="width:60px;white-space:nowrap" />
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>
				<select name="change_weight_{ROW.id}" id="change_weight_{ROW.id}" onchange="{ROW.js_onchange}">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select>
			</td>
			<td>
				<input name="chang_func_in_submenu_{ROW.id}" id="chang_func_in_submenu_{ROW.id}" type="checkbox" value="1" onclick="{ROW.in_submenu_click}"{ROW.in_submenu_checked} {ROW.disabled} />
			</td>
			<td>{ROW.name.0}</td>
			<td><a href="#action" onclick="{ROW.name.2}">{ROW.name.1}</a></td>
			<td>{ROW.layout.0}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->