<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form class="form-inline" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.title}</th>
					<th>{LANG.begin_time}</th>
					<th>{LANG.end_time}</th>
					<th>{LANG.config_discounts}</th>
					<th class="text-center">{LANG.discounts_dis_detail}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td colspan="6" class="text-center">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
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
								<td>{DISCOUNT.discount_number}{DISCOUNT.discount_unit}</td>
							</tr>
							<!-- END: discount -->
						</tbody>
					</table></td>
					<td class="text-center"><label <!-- BEGIN: detail -->class="label label-danger"<!-- END: detail -->>{VIEW.detail}</label></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">

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
					<th> {LANG.discounts_dis_detail} </th>
					<td><input type="checkbox" name="detail" value="1" {ROW.detail_ck} /></td>
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
								<td>
									<input class="form-control" type="text" name="config[{CONFIG.id}][discount_number]" value="{CONFIG.discount_number}" />
									<select name="config[{CONFIG.id}][discount_unit]" class="form-control">
										<!-- BEGIN: discount_unit -->
										<option value="{UNIT.key}" {UNIT.selected}>{UNIT.value}</option>
										<!-- END: discount_unit -->
									</select>
								</td>
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

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	//<![CDATA[
	$("#begin_time,#end_time").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
		buttonImageOnly : true
	});
	//]]>
</script>
<!-- END: main -->