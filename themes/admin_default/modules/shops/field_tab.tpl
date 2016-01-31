<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="template" value="{template}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w100" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.field_title}</th>
					<!-- BEGIN: title_tab -->
					<th class="text-center">{title_tab}</th>
					<!-- END: title_tab -->
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
					<select class="form-control" id="id_weight_{VIEW.fid}" onchange="nv_change_weight('{VIEW.fid}');">
						<!-- BEGIN: weight_loop -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight_loop -->
					</select></td>
					<td> {VIEW.field} </td>
					<!-- BEGIN: tab -->
					<td class="text-center w200"><input type="checkbox" name="check[{VIEW.fid}][{tab}]" value="{tab}" {CHECK} /></td>
					<!-- END: tab -->
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<div class="text-center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
<!-- END: view -->

<script type="text/javascript">
	//<![CDATA[
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=field&nocache=' + new Date().getTime(), 'ajax_action=1&fid=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=field';
			return;
		});
		return;
	}

	//]]>
</script>
<!-- END: main -->