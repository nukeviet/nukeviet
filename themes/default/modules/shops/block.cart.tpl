<!-- BEGIN: main -->
<div>
	<!-- BEGIN: enable -->
	<p>
		<strong>{LANG.cart_title} :</strong>
		<span>{num}</span>
		{LANG.cart_product_title}
	</p>
	<!-- BEGIN: price -->
	<p>
		<strong>{LANG.cart_product_total} : </strong>
		<span class="money">{total}</span> {money_unit}
	</p>
	<!-- END: price -->
	<p class="clearfix">
		<a title="{LANG.cart_check_out}" href="{LINK_VIEW}" id="submit_send">{LANG.cart_check_out}</a>
	</p>
	<!--  BEGIN: history -->
	<p>
		<a href="{LINK_HIS}"><span>{LANG.history_title}</span></a>
	</p>
	<!--  END: history -->
	<!-- END: enable -->
	<!-- BEGIN: disable -->
	<p>
		{LANG.active_order_dis}
	</p>
	<!-- END: disable -->
</div>
<!-- END: main -->