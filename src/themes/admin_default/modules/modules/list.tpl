<!-- BEGIN: main -->
<!-- BEGIN: act_modules -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o"></em>&nbsp;{CAPTION.0}</caption>
		<colgroup>
			<col class="w100" />
			<col class="w200" />
			<col span="4" style="white-space:nowrap" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<!-- BEGIN: thead -->
				<th>{THEAD}</th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}" class="form-control">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td><em class="fa fa-search fa-lg"></em>&nbsp;<a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
				<td>{ROW.values.custom_title}</td>
				<td>{ROW.values.version}</td>
				<td class="text-center"><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" checked="checked"{ROW.act_disabled} /></td>
				<td>
					<em class="fa fa-edit fa-lg"></em>&nbsp;<a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> 
					<em class="fa fa-sun-o fa-lg"></em>&nbsp;<a class="nv-reinstall-module" href="#" data-title="{ROW.values.title.1}">{GLANG.recreate}</a>
					<!-- BEGIN: delete -->
					<em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
					<!-- END: delete -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: act_modules -->
<!-- BEGIN: deact_modules -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o"></em>&nbsp;{CAPTION.1}</caption>
		<colgroup>
			<col class="w100" />
			<col class="w200" />
			<col span="4" style="white-space:nowrap" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<!-- BEGIN: thead -->
				<th>{THEAD}</th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}" class="form-control">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td><em class="fa fa-search fa-lg"></em>&nbsp;<a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
				<td>{ROW.values.custom_title}</td>
				<td>{ROW.values.version}</td>
				<td class="text-center"><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
				<td>
                    <em class="fa fa-edit fa-lg"></em>&nbsp;<a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> 
                    <em class="fa fa-sun-o fa-lg"></em>&nbsp;<a class="nv-reinstall-module" href="#" data-title="{ROW.values.title.1}">{GLANG.recreate}</a>
    				<!-- BEGIN: delete -->
    				<em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
    				<!-- END: delete -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: deact_modules -->
<!-- BEGIN: bad_modules -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o"></em>&nbsp;{CAPTION.2}</caption>
		<colgroup>
			<col class="w100" />
			<col class="w200" />
			<col span="4" style="white-space:nowrap" />
		</colgroup>
		<thead>
			<tr class="text-center">
				<!-- BEGIN: thead -->
				<th>{THEAD}</th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="change_weight_{ROW.mod}" id="change_weight_{ROW.mod}" onchange="{ROW.values.weight.1}" class="form-control">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td><em class="fa fa-search"></em>&nbsp;<a href="{ROW.values.title.0}">{ROW.values.title.1}</a></td>
				<td>{ROW.values.custom_title}</td>
				<td>{ROW.values.version}</td>
				<td class="text-center"><input name="change_act_{ROW.mod}" id="change_act_{ROW.mod}" type="checkbox" value="1" onclick="{ROW.values.act.1}" /></td>
				<td><em class="fa fa-edit fa-lg"></em>&nbsp;<a href="{ROW.values.edit.0}">{ROW.values.edit.1}</a> <em class="fa fa-sun-o"></em>&nbsp;<a class="nv-reinstall-module" href="#" data-title="{ROW.values.title.1}">{GLANG.recreate}</a>
				<!-- BEGIN: delete -->
				<em class="fa fa-trash-o fa-lg"></em>&nbsp;<a href="javascript:void(0);" onclick="{ROW.values.del.0}">{ROW.values.del.1}</a>
				<!-- END: delete -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: bad_modules -->
<!-- END: main -->