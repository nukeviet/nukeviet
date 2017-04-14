<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" type="text/css" rel="stylesheet" />

{FILE "shipping_menu.tpl"}

<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w100" />
				<col />
				<col class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.title}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
					<select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
						<!-- BEGIN: weight_loop -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight_loop -->
					</select></td>
					<td><strong>{VIEW.title}</strong><span class="help-block">{VIEW.description}</span></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr class="text-center">
					<td colspan="3">{PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
		</table>
	</div>
</form>
<!-- END: view -->

<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{CAPTION}
			</caption>
			<tbody>
				<tr>
					<th class="w150"> {LANG.title} </th>
					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<th class="w150"> {LANG.carrier_config_items_cg} </th>
					<td>
						<select class="form-control" name="cid">
							<!-- BEGIN: config_list -->
							<option value="{CONFIG.key}" {CONFIG.selected}>{CONFIG.value}</option>
							<!-- END: config_list -->
						</select>
					</td>
				</tr>
				<tr>
					<td> {LANG.carrier_config_description} </td>
					<td><textarea class="form-control w400" cols="75" rows="5" name="description">{ROW.description}</textarea></td>
				</tr>
				<tr>
					<th> {LANG.location} </th>
					<td>
					<select id="location" name="config_location[]" class="form-control" style="width: 100%" multiple="multiple">
						<!-- BEGIN: parent_loop -->
						<option value="{plocal_i}" {pselect}>{ptitle_i}</option>
						<!-- END: parent_loop -->
					</select></td>
				</tr>
				<tr>
					<th> {LANG.carrier_config_weight} </th>
					<td>
					<table class="table table-striped table-bordered table-hover" id="table_weight">
						<thead>
							<tr>
								<th> {LANG.carrier_config_value} </th>
								<th> {LANG.carrier_price} </th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: config -->
							<tr id="weight_{CONFIG.id}">
								<td><input class="form-control" type="number" name="config_weight[{CONFIG.id}][weight]" value="{CONFIG.weight}" />
								<select name="config_weight[{CONFIG.id}][weight_unit]" class="form-control">
									<!-- BEGIN: weight_unit -->
									<option value="{UNIT.key}" {UNIT.selected}>{UNIT.value.title}</option>
									<!-- END: weight_unit -->
								</select></td>
								<td><input class="form-control" type="text" name="config_weight[{CONFIG.id}][carrier_price]" value="{CONFIG.carrier_price}" onkeyup="this.value=FormatNumber(this.value);" class="f_carrier_price" />
								<select class="form-control" name="config_weight[{CONFIG.id}][carrier_price_unit]">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.select}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select></td>
								<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0)" onclick="$('#weight_{CONFIG.id}').remove();">{LANG.delete}</a></td>
							</tr>
							<!-- END: config -->
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" class="text-right"><a href="javascript:void(0)" onclick="nv_add_weight_items()">{LANG.carrier_config_add}</a></td>
							</tr>
						</tfoot>
					</table></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/shops/js/content.js"></script>

<script type="text/javascript">
	//<![CDATA[
	$("#location").select2();

	$("#begin_time,#end_time").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
		buttonImageOnly : true
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=carrier_config_items&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&cid={ROW.cid}&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=carrier_config_items&cid={ROW.cid}';
			return;
		});
		return;
	}

	var num = {config_weight_count};
	function nv_add_weight_items() {
		var html = '';
		html += '<tr id="weight_' + num + '">';
		html += '	<td>';
		html += '		<input class="form-control" type="number" name="config_weight[' + num + '][weight]" value="" />';
		html += '		<select name="config_weight[' + num + '][weight_unit]" class="form-control">';
		html += '		<!-- BEGIN: weight_unit -->';
		html += '		<option value="{UNIT.key}" {UNIT.selected}>{UNIT.value.title}</option>';
		html += '		<!-- END: weight_unit -->';
		html += '		</select>';
		html += '	</td>';
		html += '	<td>';
		html += '		<input class="form-control" type="text" name="config_weight[' + num + '][carrier_price]" value="" onkeyup="this.value=FormatNumber(this.value);" class="f_carrier_price" />';
		html += '		<select class="form-control" name="config_weight[' + num + '][carrier_price_unit]">';
		html += '		<!-- BEGIN: money_unit -->';
		html += '		<option value="{MON.code}" {MON.select}>{MON.currency}</option>';
		html += '		<!-- END: money_unit -->';
		html += '		</select>';
		html += '	</td>';
		html += '	<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a onclick="nv_remove_weight_item(' + num + ')" href="javascript:void(0)">{LANG.delete}</a></td>';
		html += '</tr>';
		num += 1;
		$('#table_weight tbody>tr:last').after(html);
	}

	function nv_remove_weight_item(num) {
		$('#weight_' + num).remove();
	}

	//]]>
</script>
<!-- END: main -->