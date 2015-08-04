<!-- BEGIN: main -->

<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w100" />
				<col />
				<col class="w200" />
				<col class="w100" />
				<col class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.title}</th>
					<th>{LANG.tabs_content}</th>
					<th class="text-center">{LANG.active}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="6">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
					<select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
						<!-- BEGIN: weight_loop -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight_loop -->
					</select></td>
					<td> {VIEW.title} </td>
					<td> {VIEW.content} </td>
					<td class="text-center"><input type="checkbox" name="active" id="change_status_{VIEW.id}" value="{VIEW.id}" {VIEW.active} onclick="nv_change_status({VIEW.id});" /></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">
	{ERROR}
</div>
<!-- END: error -->

<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.title}</strong> <span class="red">*</span></label>
				<div class="col-sm-21">
					<input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.tabs_icon}</strong></label>
				<div class="col-sm-18">
					<input class="form-control" type="text" name="icon" value="{ROW.icon}" id="id_icon" />
				</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-info" id="img_icon">
						<em class="fa fa-folder-open-o">&nbsp;</em> {LANG.browse_image}
					</button>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.tabs_content}</strong></label>
				<div class="col-sm-21">
					<select class="form-control" name="content">
						<!-- BEGIN: select_content -->
						<option value="{OPTION.key}" {OPTION.selected}>{OPTION.title}</option>
						<!-- END: select_content -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-21">
					<input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	$("#img_icon").click(function() {
		var area = "id_icon";
		var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var currentpath = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tabs&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tabs';
			return;
		});
		return;
	}

	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tabs&nocache=' + new Date().getTime(), 'change_status=1&id=' + id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert(nv_is_change_act_confirm[2]);
				}
			});
		}
		else{
			$('#change_status_' + id).prop('checked', new_status ? false : true );
		}
		return;
	}

	//]]>
</script>
<!-- END: main -->