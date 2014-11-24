<!-- BEGIN: main -->
<br>
<div class="block clearfix">
	<table class="rows" style="margin-bottom: 2px">
		<tr>
			<td>
			<table>
				<tr>
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
				<tr>
					<td valign="top">{LANG.order_address}:</td>
					<td valign="top">{DATA.order_address}</td>
				</tr>
				<tr>
					<td>{LANG.order_date}:</td>
					<td>{dateup} {LANG.order_moment} {moment}</td>
				</tr>
			</table></td>
			<td width="100px" valign="top" class="text-center">
			<div class="order_code">
				{LANG.order_code}
				<br>
				<span class="text_date"><strong>{DATA.order_code}</strong></span>
				<br>
				<span class="payment">{payment}</span>
			</div></td>
		</tr>
	</table>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="30px">{LANG.order_no_products}</th>
				<th>{LANG.order_products_name}</th>
				<th>{LANG.content_product_code}</th>
				<th class="text-center" width="60px">{LANG.order_product_numbers}</th>
				<th>{LANG.order_product_unit}</th>
				<th align="right">{LANG.order_product_price} ({unit})</th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{pro_no}</td>
				<td class="prd">
					<span><a target="_blank" title="{product_name}" href="{link_pro}">{product_name}</a></span><br />
					<!-- BEGIN: display_group -->
					<span class="text-muted">
						<ul style="padding: 0">
							<!-- BEGIN: item -->
							<li class="pull-left" style="margin-right: 10px">{group_title}</li>
							<!-- END: item -->
						</ul>
					</span>
					<!-- END: display_group -->
				</td>
				<td><strong>{product_code}</strong></td>
				<td class="amount" class="text-center">{product_number}</td>
				<td class="unit">{product_unit}</td>
				<td class="money" align="right"><strong>{product_price}</strong></td>
			</tr>
		<!-- END: loop -->
		</tbody>
		<tfoot>
			<tr>
				<td align="right" valign="top" colspan="8">{LANG.order_total}: <strong id="total">{order_total} {unit}</strong></td>
			</tr>
		</tfoot>
	</table>
	<!-- BEGIN: order_note -->
	<span style="font-style: italic;">{LANG.order_products_note} : {DATA.order_note}</span>
	<!-- END: order_note -->
	<table style="margin-top: 2px">
		<tr>
			<td>
			<!-- BEGIN: admin_process -->
			- {LANG.order_admin_process} : {admin_process}
			<!-- END: admin_process -->
			</td>
		</tr>
	</table>
</div>
<div class="text-center">
	<form class="form-inline" action="" method="post" name="fpost" id="post"><input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
		<!-- BEGIN: onsubmit -->
		<input class="btn btn-primary" type="submit" value="{LANG.order_submit}" id="click_submit">
		<!-- END: onsubmit -->
		<!-- BEGIN: onpay -->
		<input class="btn btn-primary" type="button" value="{LANG.order_submit_pay}" id="click_pay">
		<!-- END: onpay -->
		<input class="btn btn-info" type="button" value="{LANG.order_print}" id="click_print">
	</form>
</div>
<!-- BEGIN: transaction -->
<table class="table table-striped table-bordered table-hover">
	<caption>{LANG.history_transaction}</caption>
	<thead>
		<tr class="text-center">
			<td width="30px">&nbsp;</td>
			<td>{LANG.payment_time}</td>
			<td>{LANG.user_payment}</td>
			<td>{LANG.payment_id}</td>
			<td>{LANG.status}</td>
			<td>{LANG.order_total}</td>
			<td>{LANG.transaction_time}</td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: looptrans -->
		<tr>
			<td width="30px">{DATA_TRANS.a}</td>
			<td align="right">{DATA_TRANS.payment_time}</td>
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