<!-- BEGIN: main -->
<div id="category">
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
	<h2 class="page_title">{CAT_NAME}</h2>
	<!-- BEGIN: image -->
	<img src="{IMAGE}" alt="{CAT_NAME}">
	<!-- END: image -->
	<div id="products" class="clearfix">
		<!-- BEGIN: grid_rows -->
		<div class="items" style="width:{pwidth}%">
			<div class="items_content">
				<div class="content_top{CSS_PRODUCT_CODE}">
					<a title="{title_pro}" href="{link_pro}" class="tip_trigger"> <img src="{img_pro}" alt="{title_pro}" style="max-height:{height}px;max-width:{width}px;"/>
					<!-- BEGIN: tooltip -->
					<span class="tip"><strong>{title_pro}</strong>
						<br />
						<img src="{img_pro}" style="max-width:{width}px;">{intro}</span>
					<!-- END: tooltip -->
					</a>
					<br />
					<span>
						<a href="{LINK}" title="{title_pro}">{title_pro0}</a>
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
					{LANG.title_price}:
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
				<div align="center">
					<div class="compare">
						<input type="checkbox" value="{id}"{ch}  onclick="nv_compare({id});" id="compare_{id}"/>
						<input type="button" value="{LANG.compare}" name="compare" class="bsss" onclick="nv_compare_click();"/>
					</div>
				</div>
				<!-- END: compare -->
				<div align="center">
					<!-- BEGIN: order -->
					<a href="javascript:void(0)" id="{id}" title="{title_pro}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
					<!-- END: order -->

					<!-- BEGIN: product_empty -->
					<a href="javascript:void(0)" class="pro_detail">{LANG.product_empty}</a>
					<!-- END: product_empty -->
					
					<!-- BEGIN: wishlist -->
					<a href="javascript:void(0)" title="{title_pro}" onclick="wishlist({id}, this)" class="pro_detail">{LANG.wishlist}</a>
					<!-- END: wishlist -->
				</div>

			</div>
		</div>
		<!-- BEGIN: end_row -->
		<div style="clear:both"></div>
		<!-- END: end_row -->
		<!-- END: grid_rows -->
	</div>
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- BEGIN: tooltip_js -->
<script type="text/javascript">tooltip_shop();</script>
<!-- END: tooltip_js -->
<!-- END: main -->