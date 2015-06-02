<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/select2/select2.min.js"></script>
<link href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/select2/select2.min.css" type="text/css" rel="stylesheet" />

<div class="block clearfix">
	<div class="step_bar alert alert-success clearfix">
		<a class="step step_disable" title="{LANG.cart_check_cart}" href="{LINK_CART}"><span>1</span>{LANG.cart_check_cart}</a>
		<a class="step step_current" title="{LANG.cart_order}" href="#"><span>2</span>{LANG.cart_order}</a>
	</div>

	<p class="alert alert-info">
		{LANG.order_info}
	</p>

	<!-- BEGIN: edit_order -->
	<div class="alert alert-warning">
	{EDIT_ORDER}
	</div>
	<!-- END: edit_order -->

	<form action="" method="post" name="fpost" id="fpost" class="form-horizontal">
		<input type="hidden" value="1" name="postorder">

		<div class="panel panel-default">
		    <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.order_name} <span class="error">(*)</span></label>
                    <div class="col-sm-18">
                        <p class="form-control-static"><input name="order_name" class="form-control" value="{DATA.order_name}" /></p>
                        <span class="error">{ERROR.order_name}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.order_email} <span class="error">(*)</span></label>
                    <div class="col-sm-18">
                        <p class="form-control-static"><input type="email" name="order_email" value="{DATA.order_email}" class="form-control" /></p>
                        <span class="error">{ERROR.order_email}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.order_phone} <span class="error">(*)</span></label>
                    <div class="col-sm-18">
                        <p class="form-control-static"><input name="order_phone" class="form-control" value="{DATA.order_phone}" /></p>
                        <span class="error">{ERROR.order_phone}</span>
                    </div>
                </div>

				<!-- BEGIN: shipping_chose -->
                <div class="form-group">
                    <label class="col-sm-6 control-label">{LANG.shipping}</label>
                    <div class="col-sm-18">
                    	<!-- BEGIN: shipping_loop -->
                    	<p class="form-control-static"><label><input type="radio" name="order_shipping" value="{IS_SHIPPING.key}" {IS_SHIPPING.checked} />{IS_SHIPPING.value}</label></p>
                    	<!-- END: shipping_loop -->
                    </div>
                </div>
                <!-- END: shipping_chose -->
            </div>
        </div>

		<!-- BEGIN: shipping -->
		<div class="panel panel-primary" id="shipping_form">
			<div class="panel-heading">
				{LANG.shipping_services}
			</div>
			<div class="panel-body">
				<p><strong>{LANG.order_address} (<a href="javascript:void(0)" title="{LANG.shipping_copy}" onclick="nv_get_customer_info()">{LANG.shipping_copy}</a>)</strong></p>
				<div class="row">
					<div class="col-xs-10">
						<input type="text" name="ship_name" value="{DATA.shipping.ship_name}" class="form-control" placeholder="{LANG.shipping_name}" />
						<span class="error">{ERROR.order_shipping_name}</span>
					</div>
					<div class="col-xs-14">
						<input type="text" name="ship_phone" value="{DATA.shipping.ship_phone}" class="form-control" placeholder="{LANG.shipping_phone}" />
						<span class="error">{ERROR.order_shipping_phone}</span>
					</div>
				</div><br />
				<div class="row">
					<div class="col-xs-10">
						<select id="location" name="ship_location" class="form-control">
							<!-- BEGIN: location_loop -->
							<option value="{LOCATION.id}" {LOCATION.selected}>{LOCATION.title}</option>
							<!-- END: location_loop -->
						</select>
					</div>
					<div class="col-xs-14">
						<input type="text" name="ship_address_extend" value="{DATA.shipping.ship_address_extend}" class="form-control" placeholder="{LANG.shipping_address_extend}" />
						<span class="error">{ERROR.order_shipping_address_extend}</span>
					</div>
				</div>
				<em class="help-block">{LANG.shipping_address_note}</em>
				<div class="panel panel-danger">
					<div class="panel-body">
						<p><strong>{LANG.shipping_services}</strong></p>
						<div class="row">
							<div class="col-xs-12">
								<p><em>{LANG.shipping_shops_chose}</em></p>
								<!-- BEGIN: shops_loop -->
								<label class="show">
									<input type="radio" name="shops" value="{SHOPS.id}" {SHOPS.checked} title="{SHOPS.name}" />{SHOPS.name}
								</label>
								<span class="help-block">{SHOPS.location_string}</span>
								<!-- END: shops_loop -->
							</div>
							<div class="col-xs-12">
								<p>
									<em>{LANG.shipping_carrier_chose}</em>
									<span class="error show">{ERROR.order_shipping_carrier_id}</span>
								</p>
								<div id="carrier">
									<!-- BEGIN: carrier_loop -->
									<label class="show"><input type="radio" name="carrier" value="{CARRIER.id}" {CARRIER.checked} title="{CARRIER.name}" />{CARRIER.name}</label>
									<!-- END: carrier_loop -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-danger">
					<div class="panel-body">
						<p><strong>{LANG.shipping_info}</strong></p>
						<ul class="order_shipping_info">
							<li><em class="fa fa-circle-o">&nbsp;</em><strong>{LANG.shipping_info_weight}:</strong> {DATA.weight_total}{weight_unit}</li>
							<li><em class="fa fa-circle-o">&nbsp;</em><strong>{LANG.shipping_shops}:</strong> <span id="shipping_shops">&nbsp;</span></li>
							<li><em class="fa fa-circle-o">&nbsp;</em><strong>{LANG.shipping_services}:</strong> <span id="shipping_services">&nbsp;</span></li>
							<li><em class="fa fa-circle-o">&nbsp;</em><strong>{LANG.shipping_price}:</strong> <span id="shipping_price">&nbsp;</span></li>
							<li><em class="fa fa-circle-o">&nbsp;</em><strong>{LANG.order_address}:</strong>
								<ul>
									<li><strong>{LANG.shipping_name}:</strong> <span id="order_ship_name">N/A</span><span id="order_ship_phone">&nbsp;</span></li>
									<li><strong>{LANG.shipping_address}:</strong> <span id="order_address_extend">&nbsp;</span><span id="order_address">&nbsp;</span></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- END: shipping -->

		<!-- BEGIN: price6 -->
		<span class="text-right help-block"><strong>{LANG.product_unit_price}:</strong> {unit_config}</span>
		<!-- END: price6 -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
        			<tr>
        				<th align="center" width="30px">{LANG.order_no_products}</th>
        				<th>{LANG.cart_products}</th>
	        			<!-- BEGIN: main_group -->
	        			<th>{MAIN_GROUP.title}</th>
	        			<!-- END: main_group -->
        				<!-- BEGIN: price1 -->
        				<th class="price text-right">
        					{LANG.cart_price}
        					<span class="info_icon" data-toggle="tooltip" title="" data-original-title="{LANG.cart_price_note}">&nbsp;</span>
        				</th>
        				<!-- END: price1 -->
        				<th class="text-center" width="60px">{LANG.cart_numbers}</th>
        				<th>{LANG.cart_unit}</th>
        				<!-- BEGIN: price4 -->
        				<th class="text-right">{LANG.cart_price_total}</th>
        				<!-- END: price4 -->
        			</tr>
    			</thead>

    			<tbody>
    			<!-- BEGIN: rows -->
    			<tr>
    				<td align="center">{pro_no}</td>
    				<td>
    					<a title="{title_pro}" href="{link_pro}">{title_pro}</a>
						<!-- BEGIN: display_group -->
							<p>
							<!-- BEGIN: group -->
							<span class="show"><span class="text-muted">{group.parent_title}: <strong>{group.title}</strong></span></span>
							<!-- END: group -->
							</p>
						<!-- END: display_group -->
    				</td>
					<!-- BEGIN: sub_group -->
	    			<td><a href="{SUB_GROUP.link}" title="{SUB_GROUP.title}">{SUB_GROUP.title}</a></td>
	    			<!-- END: sub_group -->
    				<!-- BEGIN: price2 -->
    				<td class="money" align="right"><strong>{PRICE.sale_format}</strong></td>
    				<!-- END: price2 -->
    				<td align="center">{pro_num}</td>
    				<td>{product_unit}</td>
    				<!-- BEGIN: price5 -->
    				<td class="text-right money">{PRICE_TOTAL.sale_format}</td>
    				<!-- END: price5 -->
    			</tr>
    			<!-- END: rows -->
    			</tbody>
    		</table>
    	</div>

		<!-- BEGIN: price3 -->
		<!-- BEGIN: total_coupons -->
		<p class="pull-right">{LANG.coupon}: <strong>{price_coupons}</strong> {unit_config}</p>
		<div class="clear"></div>
		<!-- END: total_coupons -->
		<p class="pull-right">{LANG.cart_total}: <strong id="total">{price_total}</strong> {unit_config}</p>
		<!-- END: price3 -->

        <label>{LANG.order_note}</label>
        <textarea class="form-control" name="order_note">{DATA.order_note}</textarea>

        <div class="text-center">
        	<br />
			<input type="checkbox" name="check" value="1" id="check" />
			<span id="idselect">{LANG.order_true_info}</span>
			<br /><br />
			<span class="error">{ERROR.order_check}</span>
			<br /><br />
			<a class="btn btn-primary" title="{LANG.order_submit_send}" href="#" id="submit_send"><span>{LANG.order_submit_send}</span></a>
		</div>
	</form>
</div>

<script type="text/javascript">
	var url_load = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shippingajax';
	var urloadcart = nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart';
	var order_shipping = '{DATA.order_shipping}';

	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();

		$("#location").select2({
			language: "en"
		});
	});

	$("#submit_send").click(function() {
		$("#fpost").submit();
		return false;
	});
	$("#idselect").click(function() {
		if ($("#check").attr("checked")) {
			$("#check").removeAttr("checked");
		} else {
			$("#check").attr("checked", "checked");
		}
		return false;
	});
</script>

<!-- BEGIN: shipping_javascript -->
<script type="text/javascript">
	$(document).ready(function() {
		var shops_id = $('input[name="shops"]:checked');
		var carrier_id = $('input[name="carrier"]:checked');
		var location_id = $('#location option:selected').val();

		$('#carrier').load( url_load + '&get_carrier=1&carrier_id={DATA.shipping.ship_carrier_id}&shops_id=' + shops_id.val() );
		$('#shipping_shops').text( shops_id.attr("title") );
		$('#shipping_services').text( carrier_id.attr("title") );
		nv_get_price();

		$('input[name="shops"]').change(function(){
			var shops_id = $('input[name="shops"]:checked');
			$('#carrier').load( url_load + '&get_carrier=1&shops_id=' + shops_id.val() );
			$('#shipping_shops').text( shops_id.attr("title") );
			nv_get_price();
		});

		if( order_shipping == '0' )
		{
			$('#shipping_form').hide();
		}
		else
		{
			$('#shipping_form').show();
		}
	});

	$('input[name="order_shipping"]').change(function(){
		if( $('input[name="order_shipping"]:checked').val() == '1' )
		{
			nv_get_price();
			$('#shipping_form').slideDown();
		}
		else
		{
			$('#shipping_form').slideUp();
		}
	});

	$("#location").on("change", function (e) {
		var location_id = $('#location option:selected').val();
		$.post(url_load + '&nocache=' + new Date().getTime(), 'get_location=1&location_id=' + location_id, function(res) {
			$('#order_address').text( res );
		});
		nv_get_price();
	});

	$('input[name="address_extend"]').on("keyup", function () {
		var text = ', ';
		if( $(this).val().length > 0 ) text = $(this).val() + text; else text = '';
		$('#order_address_extend').html( text );
	});

	$('input[name="ship_name"]').on("keyup", function () {
		$('#order_ship_name').html( $(this).val() );
	});

	$('input[name="ship_phone"]').on("keyup", function () {
		var text = ' - ';
		if( $(this).val().length > 0 ) text = text + $(this).val(); else text = '';
		$('#order_ship_phone').html( text );
	});

	function nv_get_price()
	{
		if( $('input[name="order_shipping"]:checked').val() == '1' )
		{
			var carrier_id = $('input[name="carrier"]:checked');
			var shops_id = $('input[name="shops"]:checked').val();
			var location_id = $('#location option:selected').val();
			$('#shipping_services').text( carrier_id.attr("title") );
			$('#shipping_price').load( url_load + '&get_shipping_price=1&weight={DATA.weight_total}&weight_unit={weight_unit}&location_id=' + location_id + '&shops_id=' + shops_id + '&carrier_id=' + carrier_id.val() );
			$('#order_address').load( url_load + '&get_location=1&location_id=' + location_id );
			$("#cart_" + nv_module_name).load( urloadcart + '&coupons_check=1&coupons_code={COUPONS_CODE}&get_shipping_price=1&weight={DATA.weight_total}&weight_unit={weight_unit}&location_id=' + location_id + '&shops_id=' + shops_id + '&carrier_id=' + carrier_id.val() );
			$("#total").load( urloadcart + '&coupons_check=1&coupons_code={COUPONS_CODE}&get_shipping_price=1&weight={DATA.weight_total}&weight_unit={weight_unit}&location_id=' + location_id + '&shops_id=' + shops_id + '&carrier_id=' + carrier_id.val() + '&t=2' );
		}
	}

	function nv_carrier_change()
	{
		var carrier_id = $('input[name="carrier"]:checked');
		$('#shipping_services').text( carrier_id.attr("title") );
		nv_get_price();
	}

	function nv_get_customer_info()
	{
		$('input[name="ship_name"]').val( $('input[name="order_name"]').val() );
		$('input[name="ship_phone"]').val( $('input[name="order_phone"]').val() );
		$('#order_ship_name').html( $('input[name="order_name"]').val() );
		var text = ' - ';
		if( $('input[name="order_phone"]').val().length > 0 ) text = text + $('input[name="order_phone"]').val(); else text = '';
		$('#order_ship_phone').html( text );
	}
</script>
<!-- END: shipping_javascript -->

<!-- END: main -->