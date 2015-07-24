<!-- BEGIN: main -->
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-24 col-sm-19">
				<table>
					<tr style="margin-bottom: 10px">
						<td width="130px">{LANG.order_name}:</td>
						<td><strong> {DATA.order_name} </strong></td>
					</tr>
					<tr>
						<td>{LANG.order_email}:</td>
						<td>{DATA.order_email}</td>
					</tr>
					<tr>
						<td>{LANG.order_phone}:</td>
						<td>{DATA.order_phone}</td>
					</tr>
					<!-- BEGIN: order_address -->
					<tr>
						<td>{LANG.order_address}:</td>
						<td>{DATA.order_address}</td>
					</tr>
					<!-- END: order_address -->
					<tr>
						<td>{LANG.order_date}:</td>
						<td>{dateup} {LANG.order_moment} {moment}</td>
					</tr>
				</table>
			</div>
			<div class="col-xs-24 col-sm-5">
				<div class="order_code text-center">
					{LANG.order_code}
					<br>
					<span class="text_date"><strong>{DATA.order_code}</strong></span>
					<br>
					<span class="payment">{payment}</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{LANG.content_list}
		</caption>
		<thead>
			<tr>
				<th width="30px">{LANG.order_no_products}</th>
				<th>{LANG.order_products_name}</th>
				<th>{LANG.content_product_code}</th>
    			<!-- BEGIN: main_group -->
    			<th>{MAIN_GROUP.title}</th>
    			<!-- END: main_group -->
    			<th class="text-center">{LANG.order_product_price} ({unit})</th>
				<th class="text-center" width="60px">{LANG.order_product_numbers}</th>
				<th>{LANG.order_product_unit}</th>
				<th class="text-right">{LANG.order_product_price_total} ({unit})</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{pro_no}</td>
				<td class="prd"><span><a target="_blank" title="{product_name}" href="{link_pro}">{product_name}</a></span>
				<br />
				<!-- BEGIN: display_group --><span class="text-muted">
						<!-- BEGIN: item -->
						<span class="show"><span class="text-muted">{group.parent_title}: <strong>{group.title}</strong></span></span>
						<!-- END: item -->
					</span><!-- END: display_group --></td>
				<td><strong>{product_code}</strong></td>
				<!-- BEGIN: sub_group -->
    			<td>
    				<!-- BEGIN: loop -->
    				<a href="{SUB_GROUP.link}" target="_blank" title="{SUB_GROUP.title}">{SUB_GROUP.title}</a>
    				<!-- END: loop -->
    			</td>
    			<!-- END: sub_group -->
    			<td class="text-center">{product_price}</td>
				<td class="amount text-center">{product_number}</td>
				<td class="unit">{product_unit}</td>
				<td class="money" align="right"><strong>{product_price_total}</strong></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>

<!-- BEGIN: data_shipping -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.shipping_info}</caption>
		<thead>
			<tr>
				<th>{LANG.shipping_name}</th>
				<th>{LANG.order_address}</th>
				<th>{LANG.carrier}</th>
				<th>{LANG.weights}</th>
				<th class="text-right">{LANG.carrier_price}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{DATA_SHIPPING.ship_name} - {DATA_SHIPPING.ship_phone}</td>
				<td>
					{DATA_SHIPPING.ship_location_title}
					<span class="help-block">{DATA_SHIPPING.ship_address_extend}</span>
				</td>
				<td>{DATA_SHIPPING.ship_shops_title}</td>
				<td>{DATA_SHIPPING.weight}{DATA_SHIPPING.weight_unit}</td>
				<td class="text-right">{DATA_SHIPPING.ship_price} {DATA_SHIPPING.ship_price_unit}</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: data_shipping -->

<p class="text-right">{LANG.order_total}: <strong id="total">{order_total} {unit}</strong></p>

<!-- BEGIN: order_note -->
<span style="font-style: italic;">{LANG.order_products_note} : {DATA.order_note}</span>
<!-- END: order_note -->
<table style="margin-top: 2px">
	<tr>
		<td><!-- BEGIN: admin_process --> - {LANG.order_admin_process} : {admin_process} <!-- END: admin_process --></td>
	</tr>
</table>
<div class="text-center">
	<form class="form-inline" action="" method="post" name="fpost" id="post"><input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
		<!-- BEGIN: onsubmit -->
		<input class="btn btn-primary" type="submit" value="{LANG.order_submit}" id="click_submit">
		<!-- END: onsubmit -->
		<!-- BEGIN: onpay -->
		<input class="btn btn-primary" type="button" value="{LANG.order_submit_pay}" id="click_pay">
		<!-- END: onpay -->
		<!-- BEGIN: unpay -->
		<input class="btn btn-danger" type="button" value="{LANG.order_submit_unpay}" id="click_pay">
		<!-- END: unpay -->
		<input class="btn btn-info" type="button" value="{LANG.order_print}" id="click_print">
	</form>
</div>
<!-- BEGIN: transaction -->
<table class="table table-striped table-bordered table-hover">
	<caption>
		{LANG.history_transaction}
	</caption>
	<thead>
		<tr>
			<th width="30px">&nbsp;</th>
			<th>{LANG.payment_time}</th>
			<th>{LANG.user_payment}</th>
			<th>{LANG.payment_id}</th>
			<th>{LANG.status}</th>
			<th class="text-right">{LANG.order_total}</th>
			<th class="text-right">{LANG.transaction_time}</th>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: looptrans -->
		<tr>
			<td class="text-center" width="30px">{DATA_TRANS.a}</td>
			<td>{DATA_TRANS.payment_time}</td>
			<td><a href="{DATA_TRANS.link_user}">{DATA_TRANS.payment}</a></td>
			<td>{DATA_TRANS.payment_id}</td>
			<td>{DATA_TRANS.transaction}</td>
			<td align="right">{DATA_TRANS.payment_amount}</td>
			<td align="right">{DATA_TRANS.transaction_time}</td>
		</tr>
		<!-- END: looptrans -->
	</tbody>
	<!-- BEGIN: checkpayment -->
	<tfoot>
		<tr>
			<td colspan="8" align="right"><a href="{LINK_CHECK_PAYMENT}">{LANG.checkpayment}</a></td>
		</tr>
	</tfoot>
	<!-- END: checkpayment -->
</table>
<!-- END: transaction -->
<script type="text/javascript">
	$(function() {
		$('#click_submit').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.order_submit_comfix}")) {
				$('#post').submit();
			}
		});
		$('#click_print').click(function(event) {
			event.preventDefault();
			nv_open_browse('{LINK_PRINT}', '', 640, 300, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
		$('#click_pay').click(function(event) {
			event.preventDefault();
			if (confirm("{LANG.order_submit_pay_comfix}")) {
				$.ajax({
					type : "POST",
					url : '{URL_ACTIVE_PAY}',
					data : 'save=1',
					success : function(data) {
						alert(data);
						window.location = "{URL_BACK}";
					}
				});
			}
		});
	});
</script>
<!-- END: main -->