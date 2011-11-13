<!-- BEGIN: main -->
<div id="category" style="padding:3px;">
  <h2 class="page_title">{CAT_NAME}</h2>
    <!-- BEGIN: row -->
    <div class="list_rows clearfix">
        <div class="img clearfix">
            <a title="{title_pro}" href="{link_pro}">
            	<img class="reflect" src="{img_pro}" alt="{title_pro}" width="{width}"/>
			</a>
        </div>
        <p style="padding:5px">
            <strong><a title="{title_pro}" href="{link_pro}">{title_pro}</a></strong><br />
			<span class="time_up">{publtime}</span><br />
            <span>
            	<!-- BEGIN: price -->
            	{LANG.title_price} : <strong class="{class_money}">{product_price} {money_unit}</strong>
				<!-- BEGIN: discounts -->
					&nbsp; <span class="money">{product_discounts} {money_unit}</span>
				<!-- END: discounts --> <br />
                <!-- END: price -->
                <!-- BEGIN: contact -->
                {LANG.detail_pro_price} : <span class="money">{LANG.price_contact}</span><br />
                <!-- END: contact -->
                {intro}
            </span>
        </p>
		<div class="fr" style="margin-bottom:5px; width:140px" align="right">
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
