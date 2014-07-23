<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>print</title>
		<meta http-equiv="Content-Language" content="vi" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<p>
			{LANG.order_email_thanks}
		</p>
		<div class="block clearfix">
			<table class="rows2" style="margin-bottom:2px;width:100%;border:1px solid #F5F5F5;padding:5px;">
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
						<span class="text_date"><strong>{DATA.order_code}</strong></span>
						<br>
					</div></td>
				</tr>
			</table>
			<table class="rows" style="width:100%;border:1px solid #F5F5F5;">
				<tr class="bgtop" style="background:#CCCCCC;line-height:22px;">
					<td align="center" width="30px" style="padding:5px"> {LANG.order_no_products} </td>
					<td class="prd" style="padding:5px"> {LANG.cart_products} </td>
					<!-- BEGIN: price1 -->
					<td class="price" align="right" style="padding:5px"> {LANG.cart_price} ({unit}) </td>
					<!-- END: price1 -->
					<td class="amount" align="center" width="60px" style="padding:5px"> {LANG.cart_numbers} </td>
					<td class="unit" width="40" style="padding:5px"> {LANG.cart_unit} </td>
				</tr>
				<!-- BEGIN: loop -->
				<tr{bg}>
					<td align="center" style="padding:5px"> {pro_no} </td>
					<td class="prd" style="padding:5px"> {product_name} </td>
					<!-- BEGIN: price2 -->
					<td class="money" align="right" style="padding:5px"><strong>{product_price}</strong></td>
					<!-- END: price2 -->
					<td class="amount" align="center" style="padding:5px"> {product_number} </td>
					<td class="unit" style="padding:5px"> {product_unit} </td>
					</tr>
					<!-- END: loop -->
					</tbody>
			</table>
			<table class="rows" style="margin-top:2px;width:100%;border:1px solid #F5F5F5;">
				<tr>
					<!-- BEGIN: order_note -->
					<td valign="top" style="padding:5px"><span style="font-style:italic;"> {LANG.cart_note} : {DATA.order_note} </span></td>
					<!-- END: order_note -->
					<!-- BEGIN: price3 -->
					<td align="right" valign="top" style="padding:5px"> {LANG.cart_total_print}: <strong id="total">{order_total}</strong> {unit} </td>
					<!-- END: price3 -->
				</tr>
			</table>
			<p>{LANG.order_email_review}</p>
			<p>
				{LANG.order_email_noreply}
			</p>
			<p>
				---------------------------------------------------------------------------------------------------------------------
				<br />
				{SITE_NAME}.
				<br />
				Website: {SITE_DOMAIN}.
				<br />
			</p>
		</div>
	</body>
</html>
<!-- END: main -->