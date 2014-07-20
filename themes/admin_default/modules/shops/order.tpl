<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="GET" class="form-inline" role="form">
		<input type="hidden" name ="{NV_NAME_VARIABLE}"value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}"value="{OP}" />
		<div class="form-group">
			<label class="sr-only">{LANG.order_code}</label>
			<input type="text" name="order_code" value="{SEARCH.order_code}" class="form-control" placeholder="{LANG.order_code}">
		</div>
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
<p class="pull-right">
	<span>{LANG.siteinfo_order}: <strong style="color: red">{ORDER_INFO.num_items}</strong></span>
	<span>{LANG.order_total}: <strong style="color: red">{ORDER_INFO.sum_price} {ORDER_INFO.sum_unit}</strong></span>
</p>
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="10px" class="text-center"></th>
				<th><strong>{LANG.order_code}</strong></th>
				<th><strong>{LANG.order_time}</strong></th>
				<th><strong>{LANG.order_email}</strong></th>
				<th align="right"><strong>{LANG.order_total}</strong></th>
				<th><strong>{LANG.order_payment}</strong></th>
				<th width="130px" class="text-center"><strong>{LANG.function}</strong></th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
		<tr <!-- BEGIN: bgview -->class="warning"<!-- END: bgview -->>
			<td><input type="checkbox" class="ck" value="{order_id}" {DIS} /></td>
			<td>{DATA.order_code}</td>
			<td>{DATA.order_time}</td>
			<td><a href="{DATA.link_user}" style="text-decoration:underline" target="_blank">{DATA.order_email}</a></td>
			<td align="right">{DATA.order_total} {DATA.unit_total}</td>
			<td>{DATA.status_payment}</td>
			<td class="text-center">
				<em class="fa fa-edit fa-lg">&nbsp;</em><a href="{link_view}" title="">{LANG.view}</a>
				<!-- BEGIN: delete -->
				&nbsp;-&nbsp;<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{link_del}" class="delete" title="">{LANG.del}</a>
				<!-- END: delete -->
			</td>
		</tr>
		<!-- END: row -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2"><i class="fa fa-check-square-o">&nbsp;</i><a href="#" id="checkall">{LANG.prounit_select}</a> - <i class="fa fa-square-o">&nbsp;</i> <a href="#" id="uncheckall">{LANG.prounit_unselect}</a> - <i class="fa fa-trash-o">&nbsp;</i><a href="#" id="delall">{LANG.prounit_del_select}</a></td>
				<td colspan="5">{PAGES}</td>
			</tr>
		</tfoot>
	</table>
</div>
<script type='text/javascript'>
	$(function() {
		$('#checkall').click(function() {
			$('input:checkbox').each(function() {
				$(this).attr('checked', 'checked');
			});
		});
		$('#uncheckall').click(function() {
			$('input:checkbox').each(function() {
				$(this).removeAttr('checked');
			});
		});
		$('#delall').click(function() {
			if (confirm("{LANG.prounit_del_confirm}")) {
				var listall = [];
				$('input.ck:checked').each(function() {
					listall.push($(this).val());
				});
				if (listall.length < 1) {
					alert("{LANG.prounit_del_no_items}");
					return false;
				}
				$.ajax({
					type : 'POST',
					url : '{URL_DEL}',
					data : 'listall=' + listall,
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});

		$('a.delete').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.prounit_del_confirm}")) {
				var href = $(this).attr('href');
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						window.location = '{URL_DEL_BACK}';
					}
				});
			}
		});
		
		$("#from, #to").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
	});
</script>
<!-- END: data -->
<!-- END: main -->