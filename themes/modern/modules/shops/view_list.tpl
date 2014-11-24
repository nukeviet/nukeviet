<!-- BEGIN: main -->
<div id="category" style="padding:3px;">
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
	<!-- BEGIN: row -->
	<div class="list_rows clearfix">
		<div class="img clearfix">
			<a title="{title_pro}" href="{link_pro}"> <img class="reflect" src="{img_pro}" alt="{title_pro}" width="{width}"/> </a>
		</div>
		<p style="padding:5px">
			<strong>
				<a title="{title_pro}" href="{link_pro}">{title_pro}</a>
				<!-- BEGIN: new -->
				<span class="newday">({LANG.newday})</span>
				<!-- END: new -->
			</strong>
			<br />
			<!-- BEGIN: product_code -->
			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
			<br />
			<!-- END: product_code -->
			<!-- BEGIN: discounts -->
			<p>{LANG.detail_product_discounts}: {PRICE.discount_percent}%</p>
			<!-- END: discounts -->
			<span class="time_up">{publtime}</span>
			<br />
			<span>
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
				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
				<br />
				<!-- END: contact -->
				{intro} </span>
			<!-- BEGIN: compare -->
			<br />
			<span class="compare"> <input type="checkbox" value="{id}"{ch}  onclick="nv_compare({id});" id="compare_{id}"/> <input type="button" value="{LANG.compare}" name="compare" class="bsss" onclick="nv_compare_result();"/> </span>
			<!-- END: compare -->
		</p>

		<div class="fr" style="margin-bottom:5px; width:140px" align="right">
			<!-- BEGIN: adminlink -->
			{ADMINLINK}
			<!-- END: adminlink -->
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
	<!-- END: row -->
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->