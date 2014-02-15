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
	<!-- BEGIN: row -->
	<div class="list_rows clearfix">
		<div class="img clearfix">
			<a title="{title_pro}" href="{link_pro}"> <img class="reflect" src="{img_pro}" alt="{title_pro}" width="{width}"/> </a>
		</div>
		<p style="padding:5px">
			<strong><a title="{title_pro}" href="{link_pro}">{title_pro}</a></strong>
			<br />
			<!-- BEGIN: product_code -->
			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
			<br />
			<!-- END: product_code -->
			<span class="time_up">{publtime}</span>
			<br />
			<span>
				<!-- BEGIN: price -->
				{LANG.title_price} : <strong class="{class_money}">{product_price} {money_unit}</strong>
				<!-- BEGIN: discounts -->
				&nbsp; <span class="money">{product_discounts} {money_unit}</span>
				<!-- END: discounts -->
				<br />
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
			<a href="{link_pro}" title="{title_pro}" class="pro_detail">{LANG.detail_product}</a>
		</div>
	</div>
	<!-- END: row -->
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->