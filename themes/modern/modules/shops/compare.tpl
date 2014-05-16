<!-- BEGIN: main -->
<div id="products" class="clearfix">
	<!-- BEGIN: grid_rows -->
	<div class="items" style="width:25%">
		<div class="items_content" style="height: auto !important">
			<div class="content_top{CSS_PRODUCT_CODE}">
				<a title="{title_pro}" href="{link_pro}" class="tip_trigger"> <img src="{img_pro}" alt="{title_pro}" style="max-height:100px;max-width:100px"/>
				<!-- BEGIN: tooltip -->
				<span class="tip"><strong>{title_pro}</strong>
					<br />
					<img src="{img_pro}" style="max-width:{width}px;">
					<br />
					{intro}</span>
				<!-- END: tooltip -->
				</a>
				<br />
				<span><a href="{link_pro}" title="{title_pro}">{title_pro0}</a></span>
				<br />
				<!-- BEGIN: product_code -->
				<div class="shops-center">
					{PRODUCT_CODE}
				</div>
				<!-- END: product_code -->
			</div>
			<!-- BEGIN: price -->
			<p class="content_price">
				<span class="{class_money}">{product_price} {money_unit}</span>
				<!-- BEGIN: discounts -->
				<br />
				<span class="money">{product_discounts} {money_unit}</span>
				<!-- END: discounts -->
			</p>
			<!-- END: price -->
			<!-- BEGIN: contact -->

			<p class="content_price">
				<span class="money">{LANG.detail_pro_price}: {LANG.price_contact}</span>
			</p>
			<!-- END: contact -->
			<div align="center">
				<!-- BEGIN: order -->
				<a href="javascript:void(0)" id="{id}" title="{title_pro}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
				<!-- END: order -->
			</div>

			<hr/>
			<div style="text-align: left">
				<!-- BEGIN: source -->
				<div>
					{LANG.detail_source} : <a href="{link_source}">{source}</a>
				</div>
				<!-- END: source -->
				<!-- BEGIN: promotional -->
				<div>
					{LANG.detail_promotional} : {promotional}
				</div>
				<!-- END: promotional -->
				<!-- BEGIN: warranty -->
				<div>
					{LANG.detail_warranty} : {warranty}
				</div>
				<!-- END: warranty -->
				<!-- BEGIN: address -->
				<div>
					{LANG.detail_product_address} : {address}
				</div>
				<!-- END: address -->
				<!-- BEGIN: note -->
				<div>
					{LANG.cart_note} : {note}
				</div>
				<!-- END: note -->
				<div>
					<b>{LANG.product_detail}</b>
					<br/>
					{DETAIL}

				</div>

			</div>

		</div>
	</div>
	<!-- END: grid_rows -->

	<!-- BEGIN: nodata -->
	<center>
		<strong style="color: #f00">{LANG.chuachonsp}</strong>
	</center>
	<!-- END: nodata -->
</div>
<!-- END: main -->