<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="GET" class="form-inline" role="form">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />

		<div class="form-group">
			<label class="sr-only">{LANG.date_from}</label>
			<input type="text" name="from" id="from" value="{SEARCH.date_from}" class="form-control" placeholder="{LANG.date_from}" readonly="readonly">
		</div>
		<div class="form-group">
			<label class="sr-only">{LANG.date_to}</label>
			<input type="text" name="to" id="to" value="{SEARCH.date_to}" class="form-control" placeholder="{LANG.date_to}" readonly="readonly">
		</div>
		<div class="form-group">
			<label class="sr-only">{LANG.order_email}</label>
			<input type="email" name="order_email" value="{SEARCH.order_email}" class="form-control" placeholder="{LANG.order_email}">
		</div>
		<div class="form-group">
			<label class="sr-only">{LANG.order_payment}</label>
			<select class="form-control" name="order_payment">
				<option value="">{LANG.order_payment}</option>
				<!-- BEGIN: transaction_status -->
				<option value="{TRAN_STATUS.key}" {TRAN_STATUS.selected}>{TRAN_STATUS.title}</option>
				<!-- END: transaction_status -->
			</select>
		</div>
		<div class="form-group">
			<input type="hidden" name ="checkss" value="{CHECKSESS}" />
			<input type="submit" class="btn btn-primary" name="search" value="{LANG.search}" />
		</div>
	</form>
</div>

<!-- BEGIN: data -->
<div class="clearfix"></div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col span="2" />
			<col class="w100" />
		</colgroup>
		<thead>
			<tr>
				<th><strong>{LANG.order_name}</strong></th>
				<th><strong>{LANG.order_email}</strong></th>
				<th><strong>{LANG.order_count}</strong></th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr >
				<td>{DATA.order_name}</td>
				<td>{DATA.order_email}</td>
				<td align="left">{DATA.num_total}<a href="{DATA.order_list_url}" class="pull-right" title="{LANG.order_list}"><em class="fa fa-search fa-lg">&nbsp;</em></a></td>
			</tr>
			<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5">{PAGES}</td>
			</tr>
		</tfoot>
	</table>
</div>
<!-- END: data -->

<script type="text/javascript">
	$(document).ready(function() {
		$("#from,#to").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
			buttonImageOnly : true
		});
	});
</script>
<!-- END: main -->