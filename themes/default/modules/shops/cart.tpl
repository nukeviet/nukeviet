<!-- BEGIN: main -->
<div class="step_bar clearfix">
	<a class="step step_current" title="{LANG.cart_check_cart}" href="#"><span>1</span>{LANG.cart_check_cart}</a>
	<a class="step step_disable" title="{LANG.cart_order}" href="{link_order_all}" id="cart_next"><span>2</span>{LANG.cart_order}</a>
</div>
<!-- BEGIN: errortitle -->
<ul style="margin-top: 10px;color:#ff0000;text-align:right;">
	<!-- BEGIN: errorloop -->
	<li class="clearfix">
		{ERROR_NUMBER_PRODUCT}
	</li>
	<!-- END: errorloop -->
</ul>
<!-- END: errortitle -->
<form action="{LINK_CART}" method="post" id="fpro">
	<input type="hidden" value="1" name="save"/>
	<table class="rows">
		<tr class="bgtop">
			<td class="checkbox" width="20">{LANG.order_no_products}</td>
			<td class="image" width="50" align="center">{LANG.cart_images}</td>
			<td class="prd" style="width:180px">{LANG.cart_products}</td>
			<!-- BEGIN: price1 -->
			<td class="price" style="width:80px" align="right">{LANG.cart_price} ({unit_config})</td>
			<!-- END: price1 -->
			<td class="amount" align="center" width="40">{LANG.cart_numbers}</td>
			<td class="unit" style="width:50px">{LANG.cart_unit}</td>
			<td class="remove" align="center" width="20"></td>
		</tr>
		<!-- BEGIN: rows -->
		<tr id="{id}" {bg}>
			<td align="center">{stt}</td>
			<td align="center"><img src="{img_pro}" alt="{link_pro}" class="imgpro"/></td>
			<td class="prd"><a title="{title_pro}" href="{link_pro}">{title_pro}</a></td>
			<!-- BEGIN: price2 -->
			<td class="money" align="right"><strong>{product_price}</strong></td>
			<!-- END: price2 -->
			<td class="amount" align="center"><input size="1" value="{pro_num}" name="listproid[{id}]" id="{id}" class="btnum"/></td>
			<td class="unit">{product_unit}</td>
			<td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{link_remove}"> <img src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/shops/24x24-no.png" style="width: 18px"/> </a></td>
		</tr>
		<!-- END: rows -->
		</tbody>
	</table>
	<div style="margin-top:5px;">
		<!-- BEGIN: price3 -->
		<div class="clearfix">
			<div class="fl">
				{LANG.cart_total}: <span id="total"></span> {unit_config}
			</div>
			<div class="clear"></div>
		</div>
		<!-- END: price3 -->
		<div class="clearfix" style="margin-top:5px;">
			<div class="fl" style="width:49%;">
				<a class="back" title="{LANG.cart_back} {LANG.cart_page_product}" href="{LINK_PRODUCTS}">{LANG.cart_back} <span>{LANG.cart_page_product}</span></a>
			</div>
			<div class="fr">
				<input type="submit" name="cart_update" title="{LANG.cart_update}" value="{LANG.cart_update}">
				<input type="submit" name="cart_order" title="{LANG.cart_order}" value="{LANG.cart_order}">
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	var urload = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
	$("#total").load(urload + '&t=2');
	$(function() {
		$("a.remove_cart").click(function() {
			var href = $(this).attr("href");
			$.ajax({
				type : "GET",
				url : href,
				data : '',
				success : function(data) {
					if (data != '') {
						$("#" + data).html('');
						$("#cart_" + nv_module_name).load(urload);
						$("#total").load(urload + '&t=2');
					}
				}
			});
			return false;
		});
	}); 
</script>
<!-- END: main -->