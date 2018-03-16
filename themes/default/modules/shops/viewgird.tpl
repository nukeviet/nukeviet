<!-- BEGIN: main -->
<div class="viewgrid">
    <!-- BEGIN: loop -->
    <div class="col-sm-12 col-md-{NUM}">
        <div class="thumbnail">
            <div style="height: {HEIGHT">
                <a href="{ROW.link_pro}" title="{ROW.title}"><img src="{ROW.homeimgthumb}" alt="{ROW.title}"
                <!-- BEGIN: tooltip_js -->data-content='{ROW.hometext}' data-rel="tooltip" data-img="{ROW.homeimgthumb}"<!-- END: tooltip_js -->class="img-thumbnail" style="max-height:{HEIGHT}px;max-width:{WIDTH}px;"></a>
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
                <h3>
                    <a href="{ROW.link_pro}" title="{ROW.title}">{ROW.title}</a>
                </h3>
                <!-- BEGIN: product_code -->
                <p class="label label-default">{PRODUCT_CODE}</p>
                <!-- END: product_code -->
                <!-- BEGIN: adminlink -->
                <p>{ADMINLINK}</p>
                <!-- END: adminlink -->
                <!-- BEGIN: price -->
                <p class="price">
                    <!-- BEGIN: discounts -->
                    <span class="money">{PRICE.sale_format} {PRICE.unit}</span> <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                    <!-- END: discounts -->
                    <!-- BEGIN: no_discounts -->
                    <span class="money">{PRICE.price_format} {PRICE.unit}</span>
                    <!-- END: no_discounts -->
                </p>
                <!-- END: price -->
                <!-- BEGIN: contact -->
                <p class="price">
                    {LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
                </p>
                <!-- END: contact -->
                <!-- BEGIN: compare -->
                <p>
                    <input type="checkbox" value="{ROW.id}" {ch} onclick="nv_compare({ROW.id});" id="compare_{ROW.id}" /><a href="#" onclick="nv_compare_click();">&nbsp;{LANG.compare}</a>
                </p>
                <!-- END: compare -->
                <div class="clearfix">
                    <!-- BEGIN: order -->
                    <a href="javascript:void(0)" id="{ROW.id}" title="{ROW.title}" onclick="cartorder(this, {GROUP_REQUIE}, '{ROW.link_pro}'); return !1;"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                    <!-- END: order -->
                    <!-- BEGIN: product_empty -->
                    <button class="btn btn-danger disabled btn-xs">{LANG.product_empty}</button>
                    <!-- END: product_empty -->
                    <!-- BEGIN: wishlist -->
                    <a href="javascript:void(0)" title="{ROW.title}"><button type="button" onclick="wishlist({ROW.id}, this)" class="btn btn-primary btn-xs <!-- BEGIN: disabled -->disabled<!-- END: disabled -->">{LANG.wishlist}</button></a>
                    <!-- END: wishlist -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- BEGIN: tooltip_js -->
<script type="text/javascript" data-show="after">
    $(document).ready(function() {
        $("[data-rel='tooltip']").tooltip({
            placement : "bottom",
            html : true,
            title : function() {
                return '<p class="text-justify">' + $(this).data('content') + '</p><div class="clearfix"></div>';
            }
        });
    });
</script>
<!-- END: tooltip_js -->
<!-- END: main -->