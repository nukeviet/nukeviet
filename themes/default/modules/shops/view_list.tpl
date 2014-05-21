<!-- BEGIN: main -->
<div id="category">
    <div class="page-header">
        <h1>{CAT_NAME}</h1>
    </div>
    
    <!-- BEGIN: displays -->
    <div class="form-group form-inline pull-right">
        <label class="control-label">{LANG.displays_product}</label>
        <select name="sort" id="sort" class="form-control input-sm" onchange="nv_chang_price();">
            <!-- BEGIN: sorts -->
                <option value="{key}" {se}> {value}</option>
            <!-- END: sorts -->
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
    			<br />
    			<!-- BEGIN: product_code -->
    			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
    			<br />
    			<!-- END: product_code -->
    			<em class="text-muted">{publtime}</em>
    			<br />
    			<span>
    				<!-- BEGIN: price -->
    				{LANG.title_price} : <strong class="{class_money}">{product_price} {money_unit}</strong>
    				<!-- BEGIN: discounts -->
    				&nbsp; <span class="money">{product_discounts} {money_unit}</span>
    				<!-- END: discounts -->
    				<br />
    				<!-- END: price -->
    				<!-- BEGIN: contact -->
    				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
    				<br />
    				<!-- END: contact -->
    			</span>
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
    			<a href="javascript:void(0)" id="{id}" title="{title_pro}" onclick="cartorder(this)"><button class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
    			<!-- END: order -->
    			<a href="{link_pro}" title="{title_pro}"><button class="btn btn-primary btn-xs">{LANG.detail_product}</button></a>
    		</div>
		</div>
	</div>
	<!-- END: row -->
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->