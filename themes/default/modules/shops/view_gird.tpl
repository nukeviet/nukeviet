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

	<!-- BEGIN: grid_rows -->
    <div class="col-sm-6 col-md-{num}">
        <div class="thumbnail">
            <div style="height: {height}px">
                <a href="{link_pro}" title="{title_pro}"><img src="{img_pro}" alt="{title_pro}" data-content="{intro}" data-rel="tooltip" class="img-thumbnail" style="max-height:{height}px;max-width:{width}px;"></a>
            </div>
            <div class="caption text-center">
                <h3><a href="{LINK}" title="{title_pro}">{title_pro0}</a></h3>

                <!-- BEGIN: product_code -->
                <p class="label label-default">{PRODUCT_CODE}</p>
                <!-- END: product_code -->

                <!-- BEGIN: adminlink -->
                <p>{ADMINLINK}</p>
                <!-- END: adminlink -->

                <!-- BEGIN: price -->
                <p class="price">
                    <span class="{class_money}">{product_price} {money_unit}</span>
                    <!-- BEGIN: discounts -->
                    <br />
                    <span class="money">{product_discounts} {money_unit}</span>
                    <!-- END: discounts -->
                </p>
                <!-- END: price -->

                <!-- BEGIN: contact -->
                <p class="price">
                    {LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
                </p>
                <!-- END: contact -->

                <!-- BEGIN: compare -->
                <p><input type="checkbox" value="{ID}"{ch} onclick="nv_compare({ID});" id="compare_{ID}"/><a href="#" onclick="nv_compare_click();" >&nbsp;{LANG.compare}</a></p>
                <!-- END: compare -->

                <div class="clearfix">
                    <!-- BEGIN: order -->
                    <a href="javascript:void(0)" id="{id}" title="{title_pro}" onclick="cartorder(this)"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                    <!-- END: order -->
                    <a href="{link_pro}" title="{title_pro}" ><button type="button" class="btn btn-primary btn-xs">{LANG.detail_product}</button></a>
                </div>
            </div>
        </div>
    </div>
	<!-- END: grid_rows -->
	<div class="clearfix">
	</div>
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow">
</div>
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