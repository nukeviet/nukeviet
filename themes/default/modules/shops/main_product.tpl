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
				<span><a href="{LINK}" title="{TITLE}">{TITLE0}</a></span>
				<br />
				<!-- BEGIN: product_code -->
				<div class="shops-center">
					{PRODUCT_CODE}
				</div>
				<!-- END: product_code -->
			</div>
			<!-- BEGIN: adminlink -->
			<div class="shops-center">
				{ADMINLINK}
			</div>
			<!-- END: adminlink -->
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
				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
			</p>
			<!-- END: contact -->
			<div class="clearfix">
				<!-- BEGIN: order -->
				<a href="javascript:void(0)" id="{ID}" title="{TITLE}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
				<!-- END: order -->
				<a href="{LINK}" title="{TITLE}" class="pro_detail" >{LANG.detail_product}</a>
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