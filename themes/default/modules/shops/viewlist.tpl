<!-- BEGIN: main -->
<div class="viewlist">
    <!-- BEGIN: loop -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="image pull-left" style="height: {HEIGHT}px">
                <a href="{ROW.link_pro}" title="{ROW.title}"><img src="{ROW.homeimgthumb}" alt="{ROW.title}" class="img-thumbnail" style="max-height: {HEIGHT}px"></a>
            </div>
            <h3>
                <a href="{ROW.link_pro}" title="{ROW.title}">{ROW.title}</a>
            </h3>
            <!-- BEGIN: product_code -->
            <p class="label label-default">{PRODUCT_CODE}</p>
            <!-- END: product_code -->
            <!-- BEGIN: price -->
            <p class="price">
                {LANG.detail_pro_price}:
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
            <!-- BEGIN: adminlink -->
            <div class="pull-right">
                <ul class="list-inline">
                    <!-- BEGIN: new -->
                    <li><span class="label label-success newday">{LANG.newday}</span></li>
                    <!-- END: new -->
                    <!-- BEGIN: discounts -->
                    <li><span class="label label-danger">-{PRICE.discount_percent}{PRICE.discount_unit}</span></li>
                    <!-- END: discounts -->
                    <!-- BEGIN: point -->
                    <li><span class="label label-info" title="{point_note}">+{point}</span></li>
                    <!-- END: point -->
                    <!-- BEGIN: gift -->
                    <li><span class="label label-success">+<em class="fa fa-gift fa-lg">&nbsp;</em></span></li>
                    <!-- END: gift -->
                    <!-- BEGIN: compare -->
                    <li><input type="checkbox" value="{ROW.id}" {ch} onclick="nv_compare({ROW.id});" id="compare_{ROW.id}" /><a href="#" onclick="nv_compare_click();">&nbsp;{LANG.compare}</a></li>
                    <!-- END: compare -->
                    <li>{ADMINLINK}</li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <!-- END: adminlink -->
        </div>
    </div>
    <!-- END: loop -->
    <!-- BEGIN: page -->
    <div class="text-center">{PAGE}</div>
    <!-- END: page -->
</div>
<!-- END: main -->