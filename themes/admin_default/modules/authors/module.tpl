<!-- BEGIN: main -->
<div id="main_module">
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="text-center">
				<th width="80">{LANG.number}</th>
				<th>{LANG.module}</th>
				<th>{LANG.custom_title}</th>
				<th>{GLANG.level1}</th>
				<th>{GLANG.level2}</th>
				<th>{GLANG.level3}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="id_weight_{ROW.mid}" id="id_weight_{ROW.mid}" onchange="nv_chang_weight('{ROW.mid}');" class="form-control">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td>{ROW.module}</td>
				<td>{ROW.custom_title}</td>
				<td class="text-center"><input id="change_act_1_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 1);" type="checkbox" value="1" {CHANG_ACT.1} /></td>
				<td class="text-center"><input id="change_act_2_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 2);" type="checkbox" value="1" {CHANG_ACT.2} /></td>
				<td class="text-center"><input id="change_act_3_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 3);" type="checkbox" value="1" {CHANG_ACT.3} /></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
</div>
<!-- END: main -->