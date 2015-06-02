<!-- BEGIN: main -->
<div id="category">
    <div class="page-header">
        <h1>{CAT_NAME} ({count} {LANG.title_products})</h1>
        <!-- BEGIN: viewdescriptionhtml -->
		<!-- BEGIN: image -->
		<div class="text-center">
			<img src="{IMAGE}" class="img-thumbnail" alt="{CAT_NAME}">
		</div>
		<!-- END: image -->
		<p>{DESCRIPTIONHTML}</p>
		<!-- END: viewdescriptionhtml -->
    </div>

    <!-- BEGIN: displays -->
    <div class="form-group form-inline pull-right">
        <label class="control-label">{LANG.displays_product}</label>
        <select name="sort" id="sort" class="form-control input-sm" onchange="nv_chang_price();">
            <!-- BEGIN: sorts -->
                <option value="{key}" {se}> {value}</option>
            <!-- END: sorts -->
        </select>
        <label class="control-label">{LANG.title_viewnum}</label>
        <select name="viewtype" id="viewtype" class="form-control input-sm" onchange="nv_chang_viewtype();">
            <!-- BEGIN: viewtype -->
                <option value="{VIEWTYPE.key}" {VIEWTYPE.selected}> {VIEWTYPE.value}</option>
            <!-- END: viewtype -->
        </select>
    </div>
    <div class="clear">&nbsp;</div>
    <!-- END: displays -->

	<!-- BEGIN: row -->
	<div class="panel panel-default">
	    <div class="panel-body">
    		<div class="pull-left">
    			<a title="{title_pro}" href="{link_pro}"> <img class="img-thumbnail image" src="{img_pro}" alt="{title_pro}" width="{width}"/> </a>
    		</div>
    		<div>
    			<strong><a title="{title_pro}" href="{link_pro}">{title_pro}</a></strong>
            	<!-- BEGIN: new -->
            	<span class="label label-success">{LANG.newday}</span>
            	<!-- END: new -->
            	<!-- BEGIN: point -->
            	<span class="label label-info" title="{point_note}">+{point}</span>
            	<!-- END: point -->
            	<!-- BEGIN: gift -->
            	<span class="label label-success">+<em class="fa fa-gift fa-lg">&nbsp;</em></span>
            	<!-- END: gift -->
    			<br />
    			<!-- BEGIN: product_code -->
    			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
    			<br />
    			<!-- END: product_code -->
    			<em class="text-muted">{publtime}</em>
    			<br />
    			<span>
    				<!-- BEGIN: price -->
	    				{LANG.title_price} :
	                    <!-- BEGIN: discounts -->
	                    <span class="money">{PRICE.sale_format} {PRICE.unit}</span>
	                    <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
	                    <!-- END: discounts -->

						<!-- BEGIN: no_discounts -->
						<span class="money">{PRICE.price_format} {PRICE.unit}</span>
						<!-- END: no_discounts -->
    				<!-- END: price -->

    				<!-- BEGIN: contact -->
    				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
    				<!-- END: contact -->

	          		<!-- BEGIN: discounts -->
	            	(-{PRICE.discount_percent}{PRICE.discount_unit})
	            	<!-- END: discounts -->
    			</span>
    			<br />
    			<p class="text-justify">{intro}</p>
    		</div>

    		<div class="pull-right">
    			<!-- BEGIN: adminlink -->
    			{ADMINLINK}
    			<!-- END: adminlink -->

                <!-- BEGIN: compare -->
                <input type="checkbox" value="{id}"{ch} onclick="nv_compare({id});" id="compare_{id}"/> <input type="button" value="{LANG.compare}" name="compare" class="btn btn-success btn-xs" onclick="nv_compare_click();"/>
                <!-- END: compare -->

    			<!-- BEGIN: order -->
    			<a href="javascript:void(0)" id="{id}" title="{title_pro}" onclick="cartorder(this, {GROUP_REQUIE}, '{link_pro}')"><button class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
    			<!-- END: order -->

				<!-- BEGIN: product_empty -->
                <button class="btn btn-danger disabled btn-xs">{LANG.product_empty}</button>
                <!-- END: product_empty -->

                <!-- BEGIN: wishlist -->
                <a href="javascript:void(0)" title="{title_pro}" ><button type="button" onclick="wishlist({id}, this)" class="btn btn-primary btn-xs <!-- BEGIN: disabled -->disabled<!-- END: disabled -->">{LANG.wishlist}</button></a>
                <!-- END: wishlist -->
    		</div>
		</div>
	</div>
	<!-- END: row -->
	<div class="text-center">
		{pages}
	</div>
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

<div class="msgshow" id="msgshow"></div>
<!-- END: main -->