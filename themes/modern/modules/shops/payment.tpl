<!-- BEGIN: main -->
<div class="block clearfix">
	<table class="rows2" style="margin-bottom:2px">
		<tr>
			<td>
			<table>
				<tr>
					<td width="130px"> {LANG.order_name}: </td>
					<td><strong>{DATA.order_name}</strong></td>
				</tr>
				<tr>
					<td> {LANG.order_email}: </td>
					<td> {DATA.order_email} </td>
				</tr>
				<tr>
					<td> {LANG.order_phone}: </td>
					<td> {DATA.order_phone} </td>
				</tr>
				<tr>
					<td valign="top"> {LANG.order_address}: </td>
					<td valign="top"> {DATA.order_address} </td>
				</tr>
				<tr>
					<td> {LANG.order_date}: </td>
					<td> {dateup} {LANG.order_moment} {moment} </td>
				</tr>
			</table></td>
			<td width="100px" valign="top" align="center">
			<div>
				{LANG.order_code}
				<br>
				<span class="text_date">{DATA.order_code}</span>
				<br>
				<span class="payment">{DATA.transaction_name}</span>
				<a href="{url_print}" title="" id="click_print" class="btn_view" style="margin-top:5px"><span>{LANG.order_print}</span></a>
			</div></td>
		</tr>
	</table>
	<table class="rows">
		<tr class="bgtop">
			<td align="center" width="30px"> {LANG.order_no_products} </td>
			<td class="prd"> {LANG.cart_products} </td>
			<!-- BEGIN: price1 -->
			<td class="price" align="right"> {LANG.cart_price} ({unit}) </td>
			<!-- END: price1 -->
			<td class="amount" align="center" width="60px"> {LANG.cart_numbers} </td>
			<td class="unit"> {LANG.cart_unit} </td>
		</tr>
		<!-- BEGIN: loop -->
		<tr {bg}>
			<td align="center"> {pro_no} </td>
			<td class="prd"><a title="{product_name}" href="{link_pro}">{product_name}</a></td>
			<!-- BEGIN: price2 -->
			<td class="money" align="right"><strong>{product_price}</strong></td>
			<!-- END: price2 -->
			<td class="amount" align="center"> {product_number} </td>
			<td class="unit"> {product_unit} </td>
		</tr>
		<!-- END: loop -->
		</tbody>
	</table>
	<table class="rows" style="margin-top:2px">
		<tr>
			<!-- BEGIN: order_note -->
			<td valign="top"><span style="font-style:italic;">{LANG.cart_note} : {DATA.order_note}</span></td>
			<!-- END: order_note -->
			<!-- BEGIN: price3 -->
			<td align="right" valign="top"> {LANG.cart_total}: <strong id="total">{order_total}</strong> {unit} </td>
			<!-- END: price3 -->
		</tr>
	</table>
	<!-- BEGIN: actpay -->
	<div style="margin-top:4px">
		<!-- BEGIN: payment -->
		<div style="padding:5px; border:1px solid #F7F7F7; text-align:center; margin-bottom:2px">
			<!-- BEGIN: paymentloop -->
			<span style="padding:5px; border:1px solid #F7F7F7; text-align:center; margin-right:2px; display:inline-block"><a title="{DATA_PAYMENT.name}" href="{DATA_PAYMENT.url}"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}" /></a>
				<br/>
				{DATA_PAYMENT.name} </span>
			<!-- END: paymentloop -->
		</div>
		<!-- END: payment -->
		<div style="padding:5px; border:1px solid #F7F7F7">
			{intro_pay}
		</div>
	</div>
	<!-- END: actpay -->
</div>
<form action="" method="post">
	<input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
</form>
<script type="text/javascript">
	$(function() {
		$('#click_print').click(function(event) {
			var href = $(this).attr("href");
			event.preventDefault();
			nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
	});
</script>
<!-- END: main -->