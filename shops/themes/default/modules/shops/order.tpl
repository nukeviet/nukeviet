<!-- BEGIN: main -->
<div class="block clearfix">
<div class="step_bar clearfix"><a class="step step_current"
	title="{LANG.cart_check_cart}" href="{LINK_CART}"><span>1</span>{LANG.cart_check_cart}</a>
<a class="step step_current" title="{LANG.cart_order}" href="#"><span>2</span>{LANG.cart_order}</a>
</div>
<div style="line-height: 22px">{LANG.order_info}</div>
<form action="" method="post" name="fpost" id="fpost"><input
	type="hidden" value="1" name="postorder">
<table class="rows2" style="margin-bottom: 2px">
	<tr>
		<td width="130px">{LANG.order_name} (*)</td>
		<td><input name="order_name" style="width: 40%"
			value="{DATA.order_name}" /> {ERROR.order_name}</td>
	</tr>
	<tr>
		<td>{LANG.order_email} (*)</td>
		<td><input name="order_email" style="width: 40%"
			value="{DATA.order_email}" /> {ERROR.order_email}</td>
	</tr>
	<tr>
		<td>{LANG.order_phone} (*)</td>
		<td><input name="order_phone" style="width: 40%"
			value="{DATA.order_phone}" />{ERROR.order_phone}</td>
	</tr>
	<tr>
		<td valign="top">{LANG.order_address} (*)</td>
		<td valign="top"><input name="order_address"
			value="{DATA.order_address}" style="width: 90%" /> <br />
		{ERROR.order_address}</td>
	</tr>
</table>
<table class="rows">
	<tr class="bgtop">
		<td align="center" width="30px">{LANG.order_no_products}</td>
		<td class="prd">{LANG.cart_products}</td>
        <!-- BEGIN: price1 -->
		<td class="price" align="right">{LANG.cart_price} ({unit_config})</td>
        <!-- END: price1 -->
        <!-- BEGIN: num1 -->
		<td class="amount" align="center" width="60px">{LANG.cart_numbers}</td>
        <!-- END: num1 -->
		<td class="unit">{LANG.cart_unit}</td>
	</tr>
	<tbody>
		<!-- BEGIN: rows -->
		<tr {bg}>
			<td align="center">{pro_no}</td>
			<td class="prd"><a title="{title_pro}" href="{link_pro}">{title_pro}</a></td>
            <!-- BEGIN: price2 -->
			<td class="money" align="right"><strong>{product_price}</strong></td>
            <!-- END: price2 -->
            <!-- BEGIN: num2 -->
			<td class="amount" align="center">{pro_num}</td>
            <!-- END: num2 -->
			<td class="unit">{product_unit}</td>
		</tr>
		<!-- END: rows -->
	</tbody>
</table>
<!-- BEGIN: price3 -->
<table class="rows" style="margin-top: 2px">
	<tr>
		<td align="right">{LANG.cart_total}: <strong id="total">{price_total}</strong>
		{unit_config}</td>
	</tr>
</table>
<!-- END: price3 -->
<table class="rows2" style="margin-top: 2px">
	<tr>
		<td>{LANG.order_note}</td>
	</tr>
	<tr>
		<td><textarea style="width: 98%; height: 50px;" name="order_note">{DATA.order_note}</textarea></td>
	</tr>
</table>
<table class="rows2" style="margin-top: 2px">
	<tr>
		<td><input type="checkbox" name="check" value="1" id="check" /><span id="idselect">{LANG.order_true_info}</span> <br /> {ERROR.order_check}</td>
		<td align="right"><a class="btn_view" title="{LANG.order_submit_send}" href="#" id="submit_send"><span>{LANG.order_submit_send}</span></a></td>
	</tr>
</table>
</form>
</div>
<script type="text/javascript">
	 $("#submit_send").click(function(){
		  $("#fpost").submit();
		  return false;
	 });
	 $("#idselect").click(function(){
		 if ($("#check").attr("checked")) {
	         	$("#check").removeAttr("checked");//checked
         }
         else {
        	 $("#check").attr("checked", "checked");//checked
         }
		  return false;
	 });
  </script>
<!-- END: main -->
