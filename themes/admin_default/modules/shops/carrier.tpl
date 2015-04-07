<!-- BEGIN: main -->
{FILE "shipping_menu.tpl"}
<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w100" />
				<col />
				<col class="w150" />
				<col />
				<col span="2" class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.currency}</th>
					<th>{LANG.order_phone}</th>
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
					<td> {VIEW.phone} </td>
					<td> {VIEW.address} </td>
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
					<td class="w100"> {LANG.currency} <span class="red">*</span></td>
					<td><input class="form-control w400" type="text" name="name" value="{ROW.name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<td> {LANG.order_phone} </td>
					<td><input class="form-control w400" type="text" name="phone" value="{ROW.phone}" /></td>
				</tr>
				<tr>
					<td> {LANG.order_address} </td>
					<td><input class="form-control w400" type="text" name="address" value="{ROW.address}" /></td>
				</tr>
				<tr>
					<td> Logo </td>
					<td>
						<input class="form-control w400 pull-left" type="text" name="logo" value="{ROW.logo}" id="logo" />&nbsp;
						<input type="button" value="{LANG.browse_image}" name="selectimg" class="btn btn-info" />
					</td>
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
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=carrier&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=carrier';
			return;
		});
		return;
	}

	function nv_change_active( id )
	{
		var new_status = $('#change_active_' + id).is(':checked') ? 1 : 0;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_active_' + id, 3000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=carrier&nocache=' + new Date().getTime(), 'change_active=1&id=' + id + '&new_status=' + new_status, function(res) {

			});
		}
		else
		{
			$('#change_active_' + id).prop('checked', new_status ? false : true );
		}
	}

	$("input[name=selectimg]").click(function() {
		var area = "logo";
		var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var type = "image";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

//]]>
</script>
<!-- END: main -->