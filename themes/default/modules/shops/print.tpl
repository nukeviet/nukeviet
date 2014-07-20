<!-- BEGIN: main -->
<script type="text/javascript">
$(document).ready(function() {
    window.print();
});
</script>

<style type="text/css">
body {
	font-size: 12px;
	background: #fff;
}
</style>
<div id="print">
	<table class="rows2">
		<tr>
			<td>
			<table>
				<tr>
					<td width="130px">{LANG.order_name}</td>
					<td>: <strong>{DATA.order_name}</strong></td>
				</tr>
				<tr>
					<td>{LANG.order_email}</td>
					<td>: {DATA.order_email}</td>
				</tr>
				<tr>
					<td>{LANG.order_phone}</td>
					<td>: {DATA.order_phone}</td>
				</tr>
				<tr>
					<td valign="top">{LANG.order_address}</td>
					<td valign="top">: {DATA.order_address}</td>
				</tr>
				<tr>
					<td>{LANG.order_date}</td>
					<td>: {dateup} {LANG.order_moment} {moment}</td>
				</tr>
			</table></td>
			<td width="100px" valign="top" align="center">
			<div class="order_code">
				{LANG.order_code}
				<br>
				<span><strong>{DATA.order_code}</strong></span>
				<br>
				<span class="payment"> {payment} </span>
			</div></td>
		</tr>
	</table>
	<table class="rows">
		<tr class="bgtop">
			<td align="center" width="30px"> {LANG.order_no_products} </td>
			<td> {LANG.cart_products} </td>
			<!-- BEGIN: price1 -->
			<td class="price" align="right"> {LANG.cart_price} ({unit}) </td>
			<!-- END: price1 -->
			<td align="center" width="60px"> {LANG.cart_numbers} </td>
			<td width="60"> {LANG.cart_unit} </td>
		</tr>
		<!-- BEGIN: loop -->
		<tr {bg}>
			<td align="center"> {pro_no} </td>
			<td>{product_name}</td>
			<!-- BEGIN: price2 -->
			<td class="money" align="right"><strong>{product_price}</strong></td>
			<!-- END: price2 -->
			<td align="center"> {product_number} </td>
			<td> {product_unit} </td>
		</tr>
		<!-- END: loop -->
		</tbody>
	</table>
	
    <div class="row" style="margin-top: 10px;">
        <div class="col-xs-6">
			<!-- BEGIN: order_note -->
			<em>{LANG.cart_note} : {DATA.order_note}</em>
			<!-- END: order_note -->
		</div>
		<div class="col-xs-6 text-right">
			<!-- BEGIN: price3 -->
			{LANG.cart_total_print}: <strong id="total">{order_total}</strong> {unit}
			<!-- END: price3 -->
		</div>
	</div>

	<div class="text-center">
	    <button class="btn btn-primary hidden-print" onclick="window.print();"><em class="fa fa-print">&nbsp;&nbsp;{LANG.order_print}</em></button>
	</div>
</div>
<!-- END: main -->