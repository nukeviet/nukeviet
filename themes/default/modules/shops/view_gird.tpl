<!-- BEGIN: main -->
<div id="category">
	<h2 class="page_title">{CAT_NAME}</h2>
	<div id="products" class="clearfix">
    <!-- BEGIN: grid_rows -->
        <div class="items" style="width:{pwidth}%">
            <div class="items_content">
                <div class="content_top">
                    <a title="{title_pro}" href="{link_pro}">
                        <img src="{img_pro}" alt="{title_pro}" style="max-height:{height}px;max-width:{width}px;"/>
                    </a><br />
                    <span><a href="{LINK}" title="{title_pro}">{title_pro0}</a></span> <br />
                </div>
                <!-- BEGIN: price -->
                <p class="content_price">
                    <span class="{class_money}">{product_price} {money_unit}</span><br />
                    <!-- BEGIN: discounts -->
                        <span class="money">{product_discounts} {money_unit}</span><br />
                    <!-- END: discounts -->
                </p>
                <!-- END: price -->
                <!-- BEGIN: contact -->
                <p class="content_price">
                    {LANG.detail_pro_price} : <span class="money">{LANG.price_contact}</span>
                </p>
                <!-- END: contact -->
                <div align="center">
                   <!-- BEGIN: order -->
                   <a href="javascript:void(0)" id="{id}" title="{title_pro}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
                   <!-- END: order -->
                   <a class="pro_detail" href="{link_pro}" title="{title_pro}">{LANG.detail_product}</a>
                </div> 
            </div>
        </div>
        <!-- BEGIN: end_row -->
            <div style="clear:both"></div>
        <!-- END: end_row -->
    <!-- END: grid_rows -->
	</div>
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->