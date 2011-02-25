<!-- BEGIN: main -->
<div id="products" class="clearfix">
    <!-- BEGIN: items -->
    <div class="items" style="width:{pwidth}%">
        <div class="items_content">
            <p class="content_top">
                <a href="{LINK}" title="{TITLE}">
                    <img src="{IMG_SRC}" alt="" style="max-height:{height}px;max-width:{width}px;"/></a><br />
                <span><a href="{LINK}" title="{TITLE}">{TITLE0}</a></span> <br />
            </p>
            <!-- BEGIN: price -->
            <p class="content_price">
                <span class="{class_money}">{product_price} {money_unit}</span>
                <!-- BEGIN: discounts -->
                    <br /><span class="money">{product_discounts} {money_unit}</span>
                <!-- END: discounts -->
            </p>
            <!-- END: price -->
            <!-- BEGIN: contact -->
            <p class="content_price">
                {LANG.detail_pro_price} : <span class="money">{LANG.price_contact}</span>
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
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->