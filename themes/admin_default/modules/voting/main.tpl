<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w100" />
			<col />
			<col class="w100" />
			<col class="w100" />
			<col class="w150" />
		</colgroup>
		<thead>
			<tr>
				<th>{LANG.voting_order}</th>
				<th>{LANG.voting_title}</th>
				<th>{LANG.voting_stat}</th>
				<th>{LANG.voting_active}</th>
				<th>{LANG.voting_func}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select id="change_weight_{ROW.vid}" onchange="nv_chang_weight('{ROW.vid}');" class="form-control">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
					<!-- END: weight -->
				</select></td>
				<td>{ROW.question}</td>
				<td>{ROW.totalvote} {LANG.voting_totalcm}</td>
				<td class="text-center">{ROW.status}</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_content({ROW.vid}, '{ROW.checksess}')">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script>
	function nv_chang_weight(vid) {
	var nv_timer = nv_settimeout_disable('change_weight_' + vid, 5000);
	var new_weight = $('#change_weight_' + vid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'id=' + vid + '&new_weight=' + new_weight, function(res) {
		var r_split = res.split("_");
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
			clearTimeout(nv_timer);
		} else {
			window.location.href = window.location.href;
		}
	});
	return;
}
</script>
<!-- END: main -->