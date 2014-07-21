<!-- BEGIN: main -->
<div id="products">
	<!-- BEGIN: catalogs -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<a href="{LINK_CATALOG}" title="{TITLE_CATALOG}">{TITLE_CATALOG} ({NUM_PRO} {LANG.title_products})</a>
		</div>
		<div class="panel-body">
			<!-- BEGIN: items -->
            <div class="col-sm-6 col-md-{num}">
                <div class="thumbnail">
                    <div style="height: {height}px">
                        <a href="{LINK}" title="{TITLE}"><img src="{IMG_SRC}" alt="{TITLE}" data-content="{hometext}" data-rel="tooltip" class="img-thumbnail" style="max-height:{height}px;max-width:{width}px;"></a>
                    </div>
		            <div class="info_pro">
		            	<!-- BEGIN: new -->
		            	<span class="label label-success newday">{LANG.newday}</span>
		            	<!-- END: new -->
		            	<!-- BEGIN: discounts -->
		            	<span class="label label-danger">-{PRICE.discount_percent}%</span>
		            	<!-- END: discounts -->
		            </div>
					<div class="caption text-center">
    					<h3><a href="{LINK}" title="{TITLE}">{TITLE0}</a></h3>

                        <!-- BEGIN: product_code -->
                        <p class="label label-default">{PRODUCT_CODE}</p>
                        <!-- END: product_code -->

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
	</div>
	<!-- END: catalogs -->
</div>
<div class="msgshow" id="msgshow">&nbsp;</div>
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