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
					<span><a href="{LINK}" title="{title_pro}">{title_pro0}</a></span>
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
					<a class="pro_detail" href="{link_pro}" title="{title_pro}">{LANG.detail_product}</a>
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