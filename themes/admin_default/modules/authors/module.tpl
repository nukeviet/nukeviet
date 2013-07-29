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
<!-- BEGIN: script -->
<script type="text/javascript">
	function nv_chang_weight(mid) {
		var nv_timer = nv_settimeout_disable('id_weight_' + mid, 5000);
		var new_vid = document.getElementById( 'id_weight_' + mid ).options[document.getElementById('id_weight_' + mid).selectedIndex].value;
		nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&changeweight=' + mid + '&new_vid=' + new_vid + '&num=' + nv_randomPassword(8), 'main_module');
		return;
	}

	function nv_chang_act(mid, act) {
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_act_' + act + '_' + mid, 5000);
			nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&changact=' + act + '&mid=' + mid + '&num=' + nv_randomPassword(8));
		} else {
			var sl = document.getElementById('change_act_' + act + '_' + mid);
			sl.checked = (sl.checked == true) ? false : true;
		}
		return;
	}
</script>
<!-- END: script -->
<!-- END: main -->