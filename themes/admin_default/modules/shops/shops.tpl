<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/select2/select2.min.js"></script>
<link href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/select2/select2.min.css" type="text/css" rel="stylesheet" />

{FILE "shipping_menu.tpl"}

<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w100" />
				<col span="2" />
				<col span="2" class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.currency}</th>
					<th>{LANG.order_address}</th>
					<th class="text-center">{LANG.carrier_active}</th>
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
					</select>
				</td>
					<td> {VIEW.name} </td>
					<td> {VIEW.location_string}<span class="help-block">{VIEW.address}</span> </td>
					<td class="text-center"> <input type="checkbox" id="change_active_{VIEW.id}" onclick="nv_change_active({VIEW.id})" {VIEW.status} /> </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td class="w150"> {LANG.currency} <span class="red">*</span></td>
					<td><input class="form-control w400" type="text" name="name" value="{ROW.name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.location} <span class="red">*</span></td>
					<td>
						<select id="location" name="location" class="form-control w400">
							<!-- BEGIN: parent_loop -->
							<option value="{plocal_i}" {pselect}>{ptitle_i}</option>
							<!-- END: parent_loop -->
						</select><br /><br />
						<input class="form-control w400" type="text" name="address" value="{ROW.address}" placeholder="{LANG.location_detail}" />
					</td>
				</tr>
				<tr>
					<td> {LANG.carrier_config} <span class="red">*</span></td>
					<td>
					<table class="table table-striped table-bordered table-hover" id="table_carrier">
						<thead>
							<tr>
								<th> {LANG.carrier} </th>
								<th> {LANG.carrier_config_name} </th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: config -->
							<tr id="carrier_{CONFIG.id}">
								<td>
								<select name="config_carrier[{CONFIG.id}][carrier]" class="form-control">
									<option value="">---{LANG.carrier_chose}---</option>
									<!-- BEGIN: carrier -->
									<option value="{CARRIER.key}" {CARRIER.selected}>{CARRIER.value}</option>
									<!-- END: carrier -->
								</select></td>
								<td>
								<select class="form-control" name="config_carrier[{CONFIG.id}][config]">
									<option value="">---{LANG.carrier_config_chose}---</option>
									<!-- BEGIN: carrier_config -->
									<option value="{CARRIER_CONFIG.id}" {CARRIER_CONFIG.select}>{CARRIER_CONFIG.title}</option>
									<!-- END: carrier_config -->
								</select></td>
								<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0)" onclick="$('#carrier_{CONFIG.id}').remove();">{LANG.delete}</a></td>
							</tr>
							<!-- END: config -->
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" class="text-right"><a href="javascript:void(0)" onclick="nv_add_carrier_items()">{LANG.carrier_config_add}</a></td>
							</tr>
						</tfoot>
					</table></td>
				</tr>
				<tr>
					<td> {LANG.carrier_description} </td>
					<td>{ROW.description}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$("#location").select2();
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shops&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shops';
			return;
		});
		return;
	}

	function nv_change_active( id )
	{
		var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shops&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

			});
		}
		else
		{
			$('#change_active_' + id).prop('checked', new_status ? false : true );
		}
	}

	function nv_add_carrier_items() {
		var num = '{config_carrier_count}';
		var html = '';
			html += '<tr id="carrier_{CONFIG.id}">';
			html += '	<td>';
			html += '		<select name="config_carrier[{CONFIG.id}][carrier]" class="form-control">';
			html += '			<option value="">---{LANG.carrier_chose}---</option>';
			html += '			<!-- BEGIN: carrier -->';
			html += '			<option value="{CARRIER.key}" {CARRIER.selected}>{CARRIER.value}</option>';
			html += '			<!-- END: carrier -->';
			html += '		</select></td>';
			html += '		<td>';
			html += '		<select class="form-control" name="config_carrier[{CONFIG.id}][config]">';
			html += '			<option value="">---{LANG.carrier_config_chose}---</option>';
			html += '			<!-- BEGIN: carrier_config -->';
			html += '			<option value="{CARRIER_CONFIG.id}" {CARRIER_CONFIG.select}>{CARRIER_CONFIG.title}</option>';
			html += '			<!-- END: carrier_config -->';
			html += '		</select></td>';
			html += '	<td class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0)" onclick="$("#carrier_{CONFIG.id}").remove();">{LANG.delete}</a></td>';
			html += '</tr>';
		$('#table_carrier tbody>tr:last').after(html);
	}

//]]>
</script>
<!-- END: main -->