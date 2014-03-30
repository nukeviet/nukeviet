<!-- BEGIN: main -->
<div id="main_module">
	<table class="tab1">
		<colgroup>
			<col class="w50"/>
			<col style="white-space:nowrap" />
			<col style="white-space:nowrap" />
			<col span="3" class="w150" />
		</colgroup>
		<thead>
			<tr class="center">
				<td>{LANG.number}</td>
				<td>{LANG.module}</td>
				<td>{LANG.custom_title}</td>
				<td>{GLANG.level1}</td>
				<td>{GLANG.level2}</td>
				<td>{GLANG.level3}</td>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<select name="id_weight_{ROW.mid}" id="id_weight_{ROW.mid}" onchange="nv_chang_weight('{ROW.mid}');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.key}</option>
					<!-- END: weight -->
				</select></td>
				<td>{ROW.module}</td>
				<td>{ROW.custom_title}</td>
				<td class="center"><input id="change_act_1_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 1);" type="checkbox" value="1" {CHANG_ACT.1} /></td>
				<td class="center"><input id="change_act_2_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 2);" type="checkbox" value="1" {CHANG_ACT.2} /></td>
				<td class="center"><input id="change_act_3_{ROW.mid}" onclick="nv_chang_act('{ROW.mid}', 3);" type="checkbox" value="1" {CHANG_ACT.3} /></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->