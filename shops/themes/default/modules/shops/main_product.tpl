<!-- BEGIN: main -->
<div id="products" class="clearfix">
    <!-- BEGIN: items -->
    <div class="items" style="width:{pwidth}%">
        <div class="items_content">
            <p class="content_top">
                <a href="{LINK}" class="tip_trigger">
                    <img src="{IMG_SRC}" alt="" style="max-height:{height}px;max-width:{width}px;"/>
                    <!-- BEGIN: tooltip -->
                    <span class="tip"><strong>{TITLE}</strong><img src="{IMG_SRC}" style="max-width:{width}px;">{hometext}</span>
                    <!-- END: tooltip -->
                </a><br />
                <span><a href="{LINK}" title="{TITLE}">{TITLE0}</a></span> <br />
            </p>
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
<!-- BEGIN: pages -->
<div class="pages">{generate_page}</div>
<!-- END: pages -->
<div class="msgshow" id="msgshow"></div>
<!-- BEGIN: tooltip_js -->
<script type="text/javascript">tooltip_shop();</script>
<!-- END: tooltip_js -->
<!-- END: main -->