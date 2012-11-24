<!-- BEGIN: main -->
<!-- BEGIN: act_modules -->
<table summary="{CAPTION.0}" class="tab1">
<caption>{CAPTION.0}</caption>
	<col style="width:60px;white-space:nowrap" />
	<col style="width:110px;white-space:nowrap" />
	<col span="5" valign="top;white-space:nowrap" />
	<col style="white-space:nowrap" />
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
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->						
				</select>
			</td>
			<td><span class="search_icon"><a href="{ROW.values.title.0}">{ROW.values.title.1}</a></span></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" checked="checked"{ROW.act_disabled} /></td>
			<td>
				<span class="edit_icon"><a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a></span>
				&nbsp;-&nbsp;
				<span class="default_icon"><a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a></span>
				<!-- BEGIN: delete -->&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a></span><!-- END: delete -->
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: act_modules -->
<!-- BEGIN: deact_modules -->
<table summary="{CAPTION.1}" class="tab1">
	<caption>{CAPTION.1}</caption>
	<col style="width:60px;white-space:nowrap" />
	<col style="width:110px;white-space:nowrap" />
	<col span="5" valign="top;white-space:nowrap" />
	<col style="white-space:nowrap" />
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
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->						
				</select>
			</td>
			<td><span class="search_icon"><a href="{ROW.values.title.0}">{ROW.values.title.1}</a></span></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
			<td>
				<span class="edit_icon"><a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a></span>
				&nbsp;-&nbsp;<span class="default_icon"><a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a></span>
				<!-- BEGIN: delete -->&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a></span><!-- END: delete -->
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: deact_modules -->
<!-- BEGIN: bad_modules -->
<table summary={CAPTION.2}" class="tab1">
	<caption>{CAPTION.2}</caption>
	<col style="width:60px;white-space:nowrap" />
	<col style="width:110px;white-space:nowrap" />
	<col span="5" valign="top;white-space:nowrap" />
	<col style="white-space:nowrap" />
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
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->						
				</select>
			</td>
			<td><span class="search_icon"><a href="{ROW.values.title.0}">{ROW.values.title.1}</a></span></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
			<td>
				<span class="edit_icon"><a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a></span>
				&nbsp;-&nbsp;<span class="default_icon"><a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a></span>
				<!-- BEGIN: delete -->&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a></span><!-- END: delete -->
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: bad_modules -->
<!-- END: main -->