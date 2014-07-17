<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					<th>{LANG.title}</th>
					<th>{LANG.begin_time}</th>
					<th>{LANG.end_time}</th>
					<th>{LANG.config_discounts}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
					<select class="form-control" id="id_weight_{VIEW.did}" onchange="nv_change_weight('{VIEW.did}');">
						<!-- BEGIN: weight_loop -->
						<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: weight_loop -->
					</select></td>
					<td> {VIEW.title} </td>
					<td> {VIEW.begin_time} </td>
					<td> {VIEW.end_time} </td>
					<td  class="text-center">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center"> {LANG.discount_from} </th>
								<th class="text-center"> {LANG.discount_to} </th>
								<th class="text-center"> {LANG.discount_number} </th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: discount -->
							<tr>
								<td>{DISCOUNT.discount_from}</td>
								<td>{DISCOUNT.discount_to}</td>
								<td>{DISCOUNT.discount_number}</td>
							</tr>
							<!-- END: discount -->
						</tbody>
					</table></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="did" value="{ROW.did}" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>{CAPTION}</caption>
			<tbody>
				<tr>
					<th> {LANG.title} </th>
					<td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<th> {LANG.begin_time} </th>
					<td><input class="form-control" type="text" name="begin_time" value="{ROW.begin_time}" id="begin_time" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" /></td>
				</tr>
				<tr>
					<th> {LANG.end_time} </th>
					<td><input class="form-control" type="text" name="end_time" value="{ROW.end_time}" id="end_time" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" /></td>
				</tr>
				<tr>
					<th> {LANG.config_discounts} </th>
					<td>
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th> {LANG.discount_from} </th>
								<th> {LANG.discount_to} </th>
								<th> {LANG.discount_number} </th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<!-- BEGIN: config -->
							<tr id="discount_{CONFIG.id}">
								<td><input class="form-control" type="number" name="config[{CONFIG.id}][discount_from]" value="{CONFIG.discount_from}" /></td>
								<td><input class="form-control" type="number" name="config[{CONFIG.id}][discount_to]" value="{CONFIG.discount_to}"/></td>
								<td><input class="form-control" type="text" name="config[{CONFIG.id}][discount_number]" value="{CONFIG.discount_number}" /></td>
								<td><em class="fa fa-trash-o fa-lg">&nbsp;</em><a onclick="$('#discount_{CONFIG.id}').remove();">{LANG.delete}</a></td>
							</tr>
							<!-- END: config -->
						</tbody>
					</table></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	//<![CDATA[
	$("#begin_time,#end_time").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=discounts&nocache=' + new Date().getTime(), 'ajax_action=1&did=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			clearTimeout(nv_timer);
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=discounts';
			return;
		});
		return;
	}

	//]]>
</script>
<!-- END: main -->