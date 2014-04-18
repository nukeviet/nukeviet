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
    	<table class="table table-hover">
    	    <thead>
        		<tr>
        			<th width="20">{LANG.order_no_products}</th>
        			<th width="50" align="center">{LANG.cart_images}</th>
        			<th style="width:180px">{LANG.cart_products}</th>
        			<!-- BEGIN: price1 -->
        			<th style="width:80px" align="right">{LANG.cart_price} ({unit_config})</th>
        			<!-- END: price1 -->
        			<th align="center" width="40">{LANG.cart_numbers}</th>
        			<th style="width:50px">{LANG.cart_unit}</th>
        			<th align="center" width="20"></th>
        		</tr>
    		</thead>
    		<tbody>
    		<!-- BEGIN: rows -->
    		<tr id="{id}">
    			<td align="center">{stt}</td>
    			<td align="center"><img src="{img_pro}" alt="{link_pro}" width="70" class="img-thumbnail" /></td>
    			<td><a title="{title_pro}" href="{link_pro}">{title_pro}</a></td>
    			<!-- BEGIN: price2 -->
    			<td class="money text-right"><strong>{product_price}</strong></td>
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
			<div class="fl">
				{LANG.cart_total}: <span id="total"></span> {unit_config}
			</div>
			<div class="clear"></div>
		</div>
		<!-- END: price3 -->
		
        <div class="row">
            <div class="col-md-6 text-left">
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