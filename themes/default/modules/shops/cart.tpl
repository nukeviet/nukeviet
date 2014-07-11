<!-- BEGIN: main -->
<div class="step_bar alert alert-success clearfix">
	<a class="step step_current" title="{LANG.cart_check_cart}" href="#"><span>1</span>{LANG.cart_check_cart}</a>
	<a class="step step_disable" title="{LANG.cart_order}" href="{link_order_all}" id="cart_next"><span>2</span>{LANG.cart_order}</a>
</div>

<!-- BEGIN: errortitle -->

<ul class="alert alert-danger text-center">
	<!-- BEGIN: errorloop -->
	<li class="clearfix">
		{ERROR_NUMBER_PRODUCT}
	</li>
	<!-- END: errorloop -->
</ul>
<!-- END: errortitle -->

<form action="{LINK_CART}" method="post" id="fpro">
	<input type="hidden" value="1" name="save"/>
	<div class="table-responsive">
    	<table class="table table-striped table-bordered table-hover">
    	    <thead>
        		<tr>
        			<th>{LANG.order_no_products}</th>
        			<th>{LANG.cart_images}</th>
        			<th>{LANG.cart_products}</th>
        			<!-- BEGIN: price1 -->
        			<th>{LANG.cart_price}</th>
        			<!-- END: price1 -->
        			<th style="width: 80px">{LANG.cart_numbers}</th>
        			<th>{LANG.cart_unit}</th>
        			<th>&nbsp;</th>
        		</tr>
    		</thead>
    		<tbody>
    		<!-- BEGIN: rows -->
    		<tr id="{id}">
    			<td align="center">{stt}</td>
    			<td align="center"><img src="{img_pro}" alt="{link_pro}" width="70" class="img-thumbnail" /></td>
    			<td>
    				<a title="{title_pro}" href="{link_pro}">{title_pro}</a>
					<!-- BEGIN: display_group -->
					<p>
						<!-- BEGIN: group -->
						<span style="margin-right: 10px"><span class="text-muted">{group}</span></span>
						<!-- END: group -->
					</p>
					<!-- END: display_group -->
    			</td>
    			<!-- BEGIN: price2 -->
    			<td class="money text-right"><strong>{PRICE.sale_format} {PRICE.unit}</strong></td>
    			<!-- END: price2 -->
    			<td align="center"><input type="number" size="1" value="{pro_num}" name="listproid[{id}]" id="{id}" class="form-control"/></td>
    			<td>{product_unit}</td> 
    			<td align="center"><a class="remove_cart" title="{LANG.cart_remove_pro}" href="{link_remove}"><em style="color: red" class="fa fa-times-circle">&nbsp;</em></a></td>
    		</tr>
    		<!-- END: rows -->
    		</tbody>
    	</table>
    </div>

	<div>
		<!-- BEGIN: price3 -->
		<div class="clearfix">
			<div class="pull-left">
				{LANG.cart_total}: <span id="total"></span> {unit_config}
			</div>
			<div class="clear"></div>
		</div>
		<!-- END: price3 -->

        <div class="row">
            <div class="col-md-6 text-left" style="margin-top: 10px;">
                <a title="{LANG.cart_back} {LANG.cart_page_product}" href="{LINK_PRODUCTS}"><em class="fa fa-arrow-circle-left">&nbsp;</em>{LANG.cart_back} <span>{LANG.cart_page_product}</span></a>
            </div>
            <div class="col-md-6 text-right" style="margin: 10px 0 10px 0">
                <input type="submit" name="cart_update" title="{LANG.cart_update}" value="{LANG.cart_update}" class="btn btn-primary btn-sm">
                <input type="submit" name="cart_order" title="{LANG.cart_order}" value="{LANG.cart_order}" class="btn btn-primary btn-sm">
            </div>
        </div>
	</div>
</form>
<script type="text/javascript">
	var urload = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
	$("#total").load(urload + '&t=2');
	$(function() {
		$("a.remove_cart").click(function() {
			var href = $(this).attr("href");
			$.ajax({
				type : "GET",
				url : href,
				data : '',
				success : function(data) {
					if (data != '') {
						$("#" + data).html('');
						$("#cart_" + nv_module_name).load(urload);
						$("#total").load(urload + '&t=2');
					}
				}
			});
			return false;
		});
	});
</script>
<!-- END: main -->