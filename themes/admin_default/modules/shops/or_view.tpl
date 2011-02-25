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
				<td>{LANG.order_phone}</td>
				<td>: {DATA.order_phone}</td>
			</tr>
			<tr>
				<td valign="top">{LANG.order_address}:</td>
				<td valign="top">{DATA.order_address}</td>
			</tr>
			<tr>
				<td>{LANG.order_date}:</td>
				<td>{dateup} {LANG.order_moment} {moment}</td>
			</tr>
		</table>
		</td>
		<td width="100px" valign="top" align="center">
		<div class="order_code">{LANG.order_code} <br>
		<span class="text_date"><strong>{DATA.order_code}</strong></span> <br>
		<span class="payment">{payment}</span></div>
		</td>
	</tr>
</table>
<table class="tab1">
	<thead>
		<tr>
			<td width="30px">{LANG.order_no_products}</td>
			<td>{LANG.order_products_name}</td>
			<td align="center" width="60px">{LANG.order_product_numbers}</td>
			<td>{LANG.order_product_unit}</td>
			<td align="right">{LANG.order_product_price} ({unit})</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody>
		<tr{bg}>
			<td align="center">{pro_no}</td>
			<td class="prd"><a title="{product_name}" href="{link_pro}">{product_name}</a></td>
			<td class="amount" align="center">{product_number}</td>
			<td class="unit">{product_unit}</td>
			<td class="money" align="right"><strong>{product_price}</strong></td>
		</tr>
	</tbody>
	<!-- END: loop -->
	<tfoot>
		<tr>
			<td align="right" valign="top" colspan="5">{LANG.order_total}: <strong id="total">{order_total}</strong></td>
		</tr>
	</tfoot>
</table>
<!-- BEGIN: order_note --><span style="font-style: italic;">{LANG.order_products_note} : {DATA.order_note}</span> <!-- END: order_note -->
<table style="margin-top: 2px">
	<tr>
		<td><!-- BEGIN: admin_process --> - {LANG.order_admin_process} : {admin_process} <!-- END: admin_process --></td>
	</tr>
</table>
</div>
<center>
<form action="" method="post" name="fpost" id="post"><input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save"><!-- BEGIN: onsubmit --> <input type="submit" value="{LANG.order_submit}" id="click_submit"><!-- END: onsubmit --> <!-- BEGIN: onpay --> <input type="button" value="{LANG.order_submit_pay}" id="click_pay"><!-- END: onpay --> <input type="button" value="{LANG.order_print}" id="click_print"></form>
</center>
<!-- BEGIN: transaction -->
<table class="tab1">
	<caption>{LANG.history_transaction}</caption>
	<thead>
		<tr align="center">
			<td width="30px"></td>
			<td>{LANG.payment_time}</td>
			<td>{LANG.user_payment}</td>
			<td>{LANG.payment_id}</td>
			<td>{LANG.status}</td>
			<td>{LANG.order_total}</td>
			<td>{LANG.transaction_time}</td>
		</tr>
	</thead>
	<!-- BEGIN: looptrans -->
	<tbody>
		<tr>
			<td width="30px">{DATA_TRANS.a}</td>
			<td align="right">{DATA_TRANS.payment_time}</td>
			<td><a href="{DATA_TRANS.link_user}">{DATA_TRANS.payment}</a></td>
			<td>{DATA_TRANS.payment_id}</td>
			<td>{DATA_TRANS.transaction}</td>
			<td align="right">{DATA_TRANS.payment_amount}</td>
			<td align="right">{DATA_TRANS.transaction_time}</td>
		</tr>
	</tbody>
	<!-- END: looptrans -->
	<!-- BEGIN: checkpayment -->
	<tfoot>
		<tr>
			<td colspan="7" align="right"><a href="{LINK_CHECK_PAYMENT}">{LANG.checkpayment}</a></td>
		</tr>	
	</tfoot>
	<!-- END: checkpayment -->
</table>
<!-- END: transaction -->
<script type="text/javascript">
    $(function(){
        $('#click_submit').click(function(event){
            event.preventDefault();
            if (confirm("{LANG.order_submit_comfix}")) {
                $('#post').submit();
            }
        });
        $('#click_print').click(function(event){
            event.preventDefault();
            NewWindow('{LINK_PRINT}', '', '640', '300', 'yes');
            return false;
        });
        $('#click_pay').click(function(event){
            event.preventDefault();
            if (confirm("{LANG.order_submit_pay_comfix}")) {
                $.ajax({
                    type: "POST",
                    url: '{URL_ACTIVE_PAY}',
                    data: 'save=1',
                    success: function(data){
                        alert(data);
                        window.location = "{URL_BACK}";
                    }
                });
            }
        });
    });
</script>
<!-- END: main -->
