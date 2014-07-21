<!-- BEGIN: form -->
<div id="formsearch">
    <form action="{NV_BASE_SITEURL}" method="get" name="frm_search" onsubmit="return onsubmitsearch1();">
        <div class="row">
            <div class="col-xs-6 col-md-4">
                <strong>{LANG.price1}</strong><input id="price11" type="text" value="{value_price1}" name="price1" class="form-control">
            </div>
            <div class="col-xs-6 col-md-4">
                <strong>{LANG.price1}</strong><input id="price21" size="20" type="text" value="{value_price2}" name="price2" class="form-control">
            </div>
            <div class="col-xs-6 col-md-4">
                &nbsp;
                <select name="typemoney" id="typemoney1" class="form-control">
                    <option value="0">{LANG.moneyunit}</option>
                    <!-- BEGIN: typemoney -->
                    <option {ROW.selected} value="{ROW.code}">{ROW.currency}</option>
                    <!-- END: typemoney -->
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <select name="cata" id="cata1" class="form-control">
                    <option value="0">{LANG.allcatagories}</option>
                    <!-- BEGIN: loopcata -->
                    <option {ROW.selected} value="{ROW.catid}">{ROW.xtitle}</option>
                    <!-- END: loopcata -->
                </select>
            </div>
            <div class="col-xs-6">

            </div>
        </div>

        <div class="form-group">
            <label>{LANG.keyword}</label>
            <input id="keyword1" type="text" value="{value_keyword}" name="keyword" class="form-control">
        </div>

        <div class="row text-center">
            <input type="button" class="btn btn-primary" name="submit" id="submit" value="{LANG.search}" onclick="onsubmitsearch1()">
        </div>
    </form>
</div>
<!-- END: form -->
<!-- BEGIN: main -->
<div id="products">
	<!-- BEGIN: items -->
    <div class="col-sm-6 col-md-{num}">
        <div class="thumbnail">
            <div style="height: {height}px">
                <a href="{LINK}" title="{TITLE}"><img src="{IMG_SRC}" alt="{TITLE}" data-content="{hometext}" data-rel="tooltip" class="img-thumbnail" style="max-height:{height}px;max-width:{width}px;"></a>
            </div>
            <div class="info_pro">
            	<!-- BEGIN: newday -->
            	<span class="label label-success newday">{LANG.newday}</span>
            	<!-- END: newday -->
            	<!-- BEGIN: discounts -->
            	<span class="label label-danger">-{PRICE.discount_percent}%</span>
            	<!-- END: discounts -->
            </div>
            <div class="caption text-center">
                <h3><a href="{LINK}" title="{TITLE}">{TITLE0}</a></h3>

    			<!-- BEGIN: adminlink -->
    			<p>{ADMINLINK}</p>
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
                <p class="price">{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span></p>
                <!-- END: contact -->

                <!-- BEGIN: compare -->
                <p><input type="checkbox" value="{ID}"{ch} onclick="nv_compare({ID});" id="compare_{ID}"/><a href="#" onclick="nv_compare_click();" >&nbsp;{LANG.compare}</a></p>
                <!-- END: compare -->

                <div class="clearfix">
                    <!-- BEGIN: order -->
                    <a href="javascript:void(0)" id="{ID}" title="{TITLE}" onclick="cartorder(this)"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                    <!-- END: order -->
                    
					<!-- BEGIN: product_empty -->
                    <button class="btn btn-danger disabled btn-xs">{LANG.product_empty}</button>
                    <!-- END: product_empty -->

	                <!-- BEGIN: wishlist -->
	                <a href="javascript:void(0)" title="{TITLE}" ><button type="button" onclick="wishlist({ID}, this)" class="btn btn-primary btn-xs <!-- BEGIN: disabled -->disabled<!-- END: disabled -->">{LANG.wishlist}</button></a>
	                <!-- END: wishlist -->
                </div>
			</div>
		</div>
	</div>
	<!-- END: items -->
</div>
<div class="clear">&nbsp;</div>
<!-- BEGIN: pages -->
<div class="pages">
	{generate_page}
</div>
<!-- END: pages -->
<div class="msgshow" id="msgshow"></div>
<!-- BEGIN: tooltip_js -->
<script type="text/javascript">
	$(document).ready(function() {$("[data-rel='tooltip']").tooltip({
		placement: "bottom",
		html: true,
		title: function(){return '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
	});});
</script>
<!-- END: tooltip_js -->
<!-- END: main -->