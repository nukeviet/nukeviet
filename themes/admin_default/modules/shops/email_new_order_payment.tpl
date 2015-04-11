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
		{CONTENT}
	</body>
</html>
<!-- END: main -->

<!-- BEGIN: data_product -->
<table class="rows" style="width:100%;border:1px solid #F5F5F5;">
	<tr class="bgtop" style="background:#CCCCCC;line-height:22px;">
		<td align="center" width="30px" style="padding:5px"> {LANG.order_no_products} </td>
		<td class="prd" style="padding:5px"> {LANG.name} </td>
		<!-- BEGIN: price1 -->
		<td class="price" align="right" style="padding:5px"> {LANG.content_product_product_price} ({unit}) </td>
		<!-- END: price1 -->
		<td class="amount" align="center" width="60px" style="padding:5px"> {LANG.seller_num} </td>
		<td class="unit" width="60" style="padding:5px"> {LANG.unit_total} </td>
	</tr>
	<tbody>
	<!-- BEGIN: loop -->
	<tr {bg}>
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
		<td valign="top" style="padding:5px"><span style="font-style:italic;"> {LANG.order_products_note} : {DATA.order_note} </span></td>
		<!-- END: order_note -->
		<!-- BEGIN: price3 -->
		<td align="right" valign="top" style="padding:5px"> {LANG.order_total}: <strong id="total">{order_total}</strong> {unit} </td>
		<!-- END: price3 -->
	</tr>
</table>
<!-- END: data_product -->