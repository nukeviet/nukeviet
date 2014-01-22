<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>print</title>
		<meta http-equiv="Content-Language" content="vi" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>

	<body onload="window.print();">
		<style type="text/css">
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}

			em {
				color: #ff0000;
			}

			table.rows2 {
				width: 100%;
				border: 1px solid #F5F5F5;
				padding: 5px;
			}

			table.rows2 td {
			}

			table.rows {
				width: 100%;
				border: 1px solid #F5F5F5;
			}

			table.rows td {
				padding: 5px
			}

			table.rows td img {
				width: 60px;
				padding: 2px;
				border: 1px solid #ebebeb;
				vertical-align: middle;
			}

			table.rows tr.bg {
				background: #f3f3f3;
			}

			table.rows tr.bgtop {
				background: #CCCCCC;
				line-height: 22px;
			}

			a {
				text-decoration: none;
				color: #000000
			}

			.payment {
				color: #ff0000;
				font-weight: bold;
				display: block;
				margin-top: 10px;
				border: 1px solid #ff0000;
				padding: 3px;
				text-transform: uppercase;
			}
		</style>
		<div class="block clearfix">
			<table class="rows2" style="margin-bottom:2px">
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
						<span class="payment"> {payment} </span>
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
					<td class="unit" width="40"> {LANG.cart_unit} </td>
				</tr>
				<!-- BEGIN: loop -->
				<tr {bg}>
					<td align="center"> {pro_no} </td>
					<td class="prd"><a title="{product_name}" href="{link_pro}">{product_name}</a></td>
					<!-- BEGIN: price2 -->
					<td class="money" align="right"><strong>{product_price}</strong></td>
					<!-- END: price2 -->
					<td class="amount" align="center"> {product_number} </td>
					<td class="unit" > {product_unit} </td>
				</tr>
				<!-- END: loop -->
				</tbody>
			</table>
			<table class="rows" style="margin-top:2px">
				<tr>
					<!-- BEGIN: order_note -->
					<td valign="top"><span style="font-style:italic;"> {LANG.cart_note} : {DATA.order_note} </span></td>
					<!-- END: order_note -->
					<!-- BEGIN: price3 -->
					<td align="right" valign="top"> {LANG.cart_total_print}: <strong id="total">{order_total}</strong> {unit} </td>
					<!-- END: price3 -->
				</tr>
			</table>
		</div>
	</body>
</html>
<!-- END: main -->