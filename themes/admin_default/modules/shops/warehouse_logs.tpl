<!-- BEGIN: main -->

<!-- BEGIN: list -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

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
					<input type="button" class="btn btn-danger" value="{LANG.reset}" onclick="$('#search_form').clearForm();" />
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
				<!-- BEGIN: note -->
				<tr>
					<td class="text-right"><strong>{LANG.content_note}</strong></td>
					<td>{DATA.note}</td>
				</tr>
				<!-- END: note -->
			</tbody>
		</table>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.warehouse_detail_info}</caption>
		<colgroup>
			<col class="w50" />
			<col />
			<col width="600" />
		</colgroup>
		<thead>
			<tr>
				<th class="text-center">{LANG.setting_stt}</th>
				<th>{LANG.name}</th>
				<th class="text-center">{LANG.warehouse_quantity}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{LOGS.no}</td>
				<td><a href="{DATA.link}" title="{DATA.title}">{LOGS.title}</a></td>
				<td>
					<!-- BEGIN: product_number -->
					<div class="row">
						<div class="col-xs-8 text-center">

						</div>
						<div class="col-xs-8 text-center">
							<strong>{LANG.seller_num}</strong>
						</div>
						<div class="col-xs-8">
							<strong>{LANG.warehouse_price}</strong>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="col-xs-8 text-center">

						</div>
						<div class="col-xs-8 text-center">
							{LOGS.quantity}
						</div>
						<div class="col-xs-8">
							{LOGS.price} {LOGS.money_unit}
						</div>
					</div>
					<!-- END: product_number -->

					<!-- BEGIN: group -->
					<div class="row">
						<div class="col-xs-8 text-right">
							<strong>{LANG.warehouse_detail_group}</strong>
						</div>
						<div class="col-xs-8 text-center">
							<strong>{LANG.seller_num}</strong>
						</div>
						<div class="col-xs-8">
							<strong>{LANG.warehouse_price}</strong>
						</div>
					</div>
					<hr />
					<!-- BEGIN: loop -->
					<div class="row">
						<div class="col-xs-8 text-right">
							<!-- BEGIN: group_logs -->
							<label class="label label-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="{GROUP.parent_title}">{GROUP.title}</label>
							<!-- END: group_logs -->
						</div>
						<div class="col-xs-8 text-center">
							{G_LOGS.quantity}
						</div>
						<div class="col-xs-8">
							{G_LOGS.price} {G_LOGS.money_unit}
						</div>
					</div>
					<hr />
					<!-- END: loop -->
					<!-- END: group -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: view -->

<!-- END: main -->