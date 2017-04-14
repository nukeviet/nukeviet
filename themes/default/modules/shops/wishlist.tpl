<!-- BEGIN: main -->
<div id="products" class="clearfix">
    <!-- BEGIN: items -->
    <div class="col-sm-12 col-md-{num}" id="item_{ID}">
        <div class="thumbnail">
            <div style="height: {height}px">
                <a href="{LINK}" title="{TITLE}"><img src="{IMG_SRC}" alt="{TITLE}" data-content='{hometext}' data-rel="tooltip" class="img-thumbnail" style="max-width:{width}px;"></a>
            </div>
            <div class="info_pro">
            	<!-- BEGIN: new -->
            	<span class="label label-success newday">{LANG.newday}</span>
            	<!-- END: new -->
            	<!-- BEGIN: discounts -->
            	<span class="label label-danger">-{PRICE.discount_percent}{PRICE.discount_unit}</span>
            	<!-- END: discounts -->
            	<!-- BEGIN: point -->
            	<span class="label label-info" title="{point_note}">+{point}</span>
            	<!-- END: point -->
            	<!-- BEGIN: gift -->
            	<span class="label label-success">+<em class="fa fa-gift fa-lg">&nbsp;</em></span>
            	<!-- END: gift -->
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
                    <a href="javascript:void(0)" id="{ID}" title="{TITLE}" onclick="cartorder(this, {GROUP_REQUIE}, '{LINK}')"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                    <!-- END: order -->
                    <a href="javascript:void(0)" title="{LANG.wishlist_del_item}" onclick="wishlist_del_item({ID})" class="btn btn-danger btn-xs">{LANG.wishlist_del_item}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: items -->
</div>

<!-- BEGIN: modal_loaded -->
<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{LANG.add_product}</h4>
			</div>
			<div class="modal-body">
				<em class="fa fa-spinner fa-spin">&nbsp;</em>
			</div>
		</div>
	</div>
</div>
<!-- END: modal_loaded -->

<!-- BEGIN: pages -->
<div class="text-center">
    {generate_page}
</div>
<!-- END: pages -->
<div class="msgshow" id="msgshow">&nbsp;</div>

<script type="text/javascript" data-show="after">
	var lang_del_confirm = '{LANG.wishlist_del_item_confirm}';
</script>

<!-- BEGIN: tooltip_js -->
<script type="text/javascript" data-show="after">
	$(document).ready(function() {$("[data-rel='tooltip']").tooltip({
		placement: "bottom",
		html: true,
		title: function(){return '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';}
	});});
</script>
<!-- END: tooltip_js -->
<!-- END: main -->