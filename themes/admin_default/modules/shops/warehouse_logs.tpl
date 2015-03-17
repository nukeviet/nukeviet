<!-- BEGIN: main -->

<!-- BEGIN: list -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get" id="search_form">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" value="{SEARCH.keywords}" name="keywords" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="from" id="from" value="{SEARCH.from}" readonly="readonly" placeholder="{LANG.date_from}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="from-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="to" id="to" value="{SEARCH.to}" readonly="readonly" placeholder="{LANG.date_to}">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="to-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="{LANG.search}" />
					<input type="button" class="btn btn-danger" value="{LANG.reset}" onclick="reset_form( $('#search_form') );" />
				</div>
			</div>
		</div>
	</form>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col />
			<col class="w250" />
			<col class="w150" />
			<col class="w200" span="2" />
		</colgroup>
		<thead>
			<tr>
				<th>{LANG.title}</th>
				<th>{LANG.user_payment}</th>
				<th>{LANG.warehouse_num_pro}</th>
				<th>{LANG.order_total} ({MONEY_UNIT})</th>
				<th class="text-center">{LANG.warehouse_time}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><a href="{VIEW.link}" title="{VIEW.title}">{VIEW.title}</a><span class="help-block">{VIEW.note}</span></td>
				<td>{VIEW.full_name}</td>
				<td class="text-center">{VIEW.total_product}</td>
				<td>{VIEW.total_price}</td>
				<td class="text-center">{VIEW.addtime} <span class="help-block">{VIEW.ship_address_extend}</span></td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr class="text-center">
				<td colspan="5">{NV_GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<script type='text/javascript'>
	$(function() {
		$("#from, #to").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn : 'focus'
		});
		$('#to-btn').click(function() {
			$("#to").datepicker('show');
		});
		$('#from-btn').click(function() {
			$("#from").datepicker('show');
		});
	});
</script>
<!-- END: list -->

<!-- BEGIN: view -->
<div class="panel panel-default">
	<div class="panel-body">
		<table class="table table-bordered">
			<col class="w200" />
			<tbody>
				<tr>
					<td class="text-right"><strong>{LANG.title}</strong></td>
					<td>{DATA.title}</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.user_payment}</strong></td>
					<td>{DATA.full_name}</td>
				</tr>
				<tr>
					<td class="text-right"><strong>{LANG.warehouse_time}</strong></td>
					<td>{DATA.addtime}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.warehouse_detail_info}</caption>
		<thead>
			<tr>
				<th class="text-center">{LANG.setting_stt}</th>
				<th>{LANG.name}</th>
				<th class="text-center">{LANG.warehouse_quantity}</th>
				<th class="text-center">{LANG.warehouse_price}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">{DATA.no}</td>
					<td><a href="{DATA.link}" title="{DATA.title}">{DATA.title}</a></td>
					<td>
					<div class="row">
						<!-- BEGIN: product_number -->
						<div class="form-group">
							<label class="col-sm-6 control-label"><span class="label label-success">{LANG.content_product_number}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{DATA.product_number} {DATA.product_unit}</span></label>
							<div class="col-sm-6">
								<input type="number" name="quantity[{DATA.id}][0]" class="form-control" placeholder="{LANG.warehouse_quantity}" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="price[{DATA.id}][0]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />
							</div>
							<div class="col-sm-6">
								<select name="money_unit[{DATA.id}][0]" class="form-control">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select>
							</div>
						</div>
						<!-- END: product_number -->
						<!-- BEGIN: group -->
						<div class="form-group">
							<label class="col-sm-6 control-label"><span class="label label-success">{GROUP.parent_title}: {GROUP.title}</span>&nbsp;<span class="label label-danger" title="{LANG.content_product_number}">{GROUP.pro_quantity} {DATA.product_unit}</span></label>
							<div class="col-sm-6">
								<input type="number" name="quantity[{DATA.id}][{GROUP.groupid}]" class="form-control" placeholder="{LANG.warehouse_quantity}" />
							</div>
							<div class="col-sm-6">
								<input type="text" name="price[{DATA.id}][{GROUP.groupid}]" class="form-control" placeholder="{LANG.warehouse_price}" onkeyup="this.value=FormatNumber(this.value);" />
							</div>
							<div class="col-sm-6">
								<select name="money_unit[{DATA.id}][{GROUP.groupid}]" class="form-control">
									<!-- BEGIN: money_unit -->
									<option value="{MON.code}" {MON.selected}>{MON.currency}</option>
									<!-- END: money_unit -->
								</select>
							</div>
						</div>
						<!-- END: group -->
					</div></td>
				</tr>
				<!-- END: loop -->
			</tr>
		</tbody>
	</table>
</div>
<!-- END: view -->

<!-- END: main -->