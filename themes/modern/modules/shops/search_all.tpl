<!-- BEGIN: form -->
<div class="clear"></div>
<form action="{NV_BASE_SITEURL}" method="get" name="frm_search" style="background:#F5F5F5" onsubmit="return onsubmitsearch1();">
	<table width="100%">
		<tr>
			<td width="50">{LANG.price1} :</td>
			<td><input id="price11" type="text" value="{value_price1}" name="price1" style="text-align:center" class="txt-full"></td>
			<td width="50">{LANG.price2} :</td>
			<td><input id="price21" size="20" type="text" value="{value_price2}" name="price2" style="text-align:center" class="txt-full"></td>
			<td width="80" align="right">
			<select name="typemoney" id="typemoney1" class="txt-full">
				<option value="0">{LANG.moneyunit}</option>
				<!-- BEGIN: typemoney -->
				<option {ROW.selected} value="{ROW.code}">{ROW.currency}</option>
				<!-- END: typemoney -->
			</select></td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td>
			<select name="cata" style="width:100%" id="cata1">
				<option value="0">{LANG.allcatagories}</option>
				<!-- BEGIN: loopcata -->
				<option {ROW.selected} value="{ROW.catid}">{ROW.xtitle}</option>
				<!-- END: loopcata -->
			</select></td>
			<td>
			<select name="sourceid" style="width:100%" id="sourceid1">
				<option value="0">{LANG.source_title}</option>
				<!-- BEGIN: loopsource -->
				<option {ROW.selected} value="{ROW.sourceid}">{ROW.title}</option>
				<!-- END: loopsource -->
			</select></td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td width="60">{LANG.keyword} :</td>
			<td><input id="keyword1" type="text" value="{value_keyword}" name="keyword" style="width:98%"></td>
			<td width="80" align="right"><input type="button" class="button" name="submit" id="submit" value="{LANG.search}" onclick="onsubmitsearch1()"></td>
		</tr>
	</table>
</form>
<!-- END: form -->
<!-- BEGIN: main -->
<div class="clear"></div>
<div id="products" class="clearfix" style="margin-top:5px">
	<!-- BEGIN: items -->
	<div class="items" style="width:{pwidth}%">
		<div class="items_content">
			<p class="content_top">
				<a href="{LINK}" class="tip_trigger"> <img src="{IMG_SRC}" alt="" style="max-height:{height}px;max-width:{width}px;"/>
				<!-- BEGIN: tooltip -->
				<span class="tip"><strong>{TITLE}</strong>
					<br />
					<img src="{IMG_SRC}" style="max-width:{width}px;">{hometext}</span>
				<!-- END: tooltip -->
				</a>
				<br />
				<span><a href="{LINK}" title="{TITLE}">{TITLE0}</a></span>
				<br />
			</p>
			<!-- BEGIN: adminlink -->
			<div class="shops-center">
				{ADMINLINK}
			</div>
			<!-- END: adminlink -->
			<!-- BEGIN: price -->
			<p class="content_price">
				<span class="{class_money}">{product_price} {money_unit}</span>
				<!-- BEGIN: discounts -->
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