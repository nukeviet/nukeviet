<!-- BEGIN: main -->
<!-- BEGIN: act_modules -->
<table class="tab1">
	<caption>{CAPTION.0}</caption>
	<colgroup>
		<col style="width:60px;white-space:nowrap" />
		<col style="width:110px;white-space:nowrap" />
		<col span="5" style="valign:top;white-space:nowrap" />
		<col style="white-space:nowrap" />
	</colgroup>
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
				<!-- END: weight -->
			</select></td>
			<td><em class="icon-search icon-large">&nbsp;</em> <a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" checked="checked"{ROW.act_disabled} /></td>
			<td><em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> &nbsp;-&nbsp; <em class="icon-sun icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a>
			<!-- BEGIN: delete -->
			&nbsp;-&nbsp;<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
			<!-- END: delete -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: act_modules -->
<!-- BEGIN: deact_modules -->
<table class="tab1">
	<caption>{CAPTION.1}</caption>
	<colgroup>
		<col style="width:60px;white-space:nowrap" />
		<col style="width:110px;white-space:nowrap" />
		<col span="5" style="valign:top;white-space:nowrap" />
		<col style="white-space:nowrap" />
	</colgroup>
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
				<!-- END: weight -->
			</select></td>
			<td><em class="icon-search icon-large">&nbsp;</em> <a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
			<td><em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> &nbsp;-&nbsp;<em class="icon-sun icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a>
			<!-- BEGIN: delete -->
			&nbsp;-&nbsp;<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
			<!-- END: delete -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: deact_modules -->
<!-- BEGIN: bad_modules -->
<table class="tab1">
	<caption>{CAPTION.2}</caption>
	<colgroup>
		<col style="width:60px;white-space:nowrap" />
		<col style="width:110px;white-space:nowrap" />
		<col span="5" style="valign:top;white-space:nowrap" />
		<col style="white-space:nowrap" />
	</colgroup>
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
				<!-- END: weight -->
			</select></td>
			<td><em class="icon-search icon-large">&nbsp;</em> <a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
			<td>{ROW.values.custom_title}</td>
			<td>{ROW.values.version}</td>
			<td><input name="change_inmenu_{ROW.mod}" id="change_inmenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.in_menu.1}"{ROW.inmenu_checked} /></td>
			<td><input name="change_submenu_{ROW.mod}" id="change_submenu_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.submenu.1}"{ROW.submenu_checked} /></td>
			<td><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
			<td><em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> &nbsp;-&nbsp;<em class="icon-sun icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.recreate.0}">{ROW.values.recreate.1}</a>
			<!-- BEGIN: delete -->
			&nbsp;-&nbsp;<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
			<!-- END: delete -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: bad_modules -->
<!-- END: main -->