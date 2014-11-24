<!-- BEGIN: main -->
<div id="products" class="clearfix">
	<!-- BEGIN: displays -->
	<div style=" margin-top: 3px;float: right">
		{LANG.displays_product}
		<select name="sort" id="sort" onchange="nv_chang_price();">
			<!-- BEGIN: sorts -->
			<option value="{key}" {se}> {value}</option>
			<!-- END: sorts -->
		</select>
	</div>
	<!-- END: displays -->
	<div class="clear"></div>

	<!-- BEGIN: items -->
	<div class="items" style="width:{pwidth}%">
		<div class="items_content">
			<div class="content_top{CSS_PRODUCT_CODE}">
				<a href="{LINK}" class="tip_trigger"> <img src="{IMG_SRC}" alt="{TITLE}" style="max-height:{height}px;max-width:{width}px;"/>
				<!-- BEGIN: tooltip -->
				<span class="tip"><strong>{TITLE}</strong>
					<br />
					<img src="{IMG_SRC}" style="max-width:{width}px;">{hometext}</span>
				<!-- END: tooltip -->
				</a>
				<br />
				<span>
					<a href="{LINK}" title="{TITLE}">{TITLE0}</a>
					<!-- BEGIN: new -->
					<span class="newday">({LANG.newday})</span>
					<!-- END: new -->
				</span>
				<br />
				<!-- BEGIN: product_code -->
				<div class="shops-center">
					{PRODUCT_CODE}
				</div>
				<!-- END: product_code -->
				
				<!-- BEGIN: discounts -->
				<p>{LANG.detail_product_discounts}: {PRICE.discount_percent}%</p>
				<!-- END: discounts -->
			</div>
			<!-- BEGIN: adminlink -->
			<div class="shops-center">
				{ADMINLINK}
			</div>
			<!-- END: adminlink -->
			<!-- BEGIN: price -->
			<p class="price">
				<!-- BEGIN: discounts -->
				<span class="money">{PRICE.sale_format} {PRICE.unit}</span>
				<span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
				<!-- END: discounts -->
				
				<!-- BEGIN: no_discounts -->
				<span class="money">{PRICE.price_format} {PRICE.unit}</span>
				<!-- END: no_discounts -->
			</p>
			<!-- END: price -->
			<!-- BEGIN: contact -->
			<p class="content_price">
				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
			</p>
			<!-- END: contact -->
			
            <!-- BEGIN: compare -->
            <p><input type="checkbox" value="{ID}"{ch} onclick="nv_compare({ID});" id="compare_{ID}"/><a href="#" onclick="nv_compare_click();" >&nbsp;{LANG.compare}</a></p>
            <!-- END: compare -->
            
			<div class="clearfix">
				<!-- BEGIN: order -->
				<a href="javascript:void(0)" id="{ID}" title="{TITLE}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
				<!-- END: order -->
				
				<!-- BEGIN: product_empty -->
				<a href="javascript:void(0)" class="pro_detail">{LANG.product_empty}</a>
				<!-- END: product_empty -->
				
				<!-- BEGIN: wishlist -->
				<a href="javascript:void(0)" title="{TITLE}" onclick="wishlist({ID}, this)" class="pro_detail">{LANG.wishlist}</a>
				<!-- END: wishlist -->
			</div>
		</div>
	</div>
	<!-- BEGIN: break -->
	<div style="clear:both"></div>
	<!-- END: break -->
	<!-- END: items -->
</div>
<!-- BEGIN: pages -->
<div class="pages">
	{generate_page}
</div>
<!-- END: pages -->
<div class="msgshow" id="msgshow"></div>
<!-- BEGIN: tooltip_js -->
<script type="text/javascript">tooltip_shop();</script>
<!-- END: tooltip_js -->
<!-- END: main -->