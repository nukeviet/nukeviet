<!-- BEGIN: main -->
<div class="block clearfix">
    <div class="row panel panel-default">
        <div class="panel-body">
            <div class="col-xs-8">
                <div class="row">
                    <div class="col-md-4"><strong>{LANG.order_name}:</strong></div>
                    <div class="col-md-8">{DATA.order_name}</div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>{LANG.order_email}:</strong></div>
                    <div class="col-md-8">{DATA.order_email}</div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>{LANG.order_phone}:</strong></div>
                    <div class="col-md-8">{DATA.order_phone}</div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>{LANG.order_address}:</strong></div>
                    <div class="col-md-8">{DATA.order_address}</div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>{LANG.order_date}:</strong></div>
                    <div class="col-md-8">{dateup} {LANG.order_moment} {moment}</div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="text-center">
                    {LANG.order_code}
                    <br>
                    <span>{DATA.order_code}</span>
                    <br>
                    <span class="payment">{DATA.transaction_name}</span>
                    <a href="{url_print}" title="" id="click_print" class="btn btn-success hidden-xs" style="margin-top:5px"><em class="fa fa-print">&nbsp;&nbsp;{LANG.order_print}</em></a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
    	<table class="table table-striped table-bordered table-hover">
    	    <thead>
    		<tr>
    			<th align="center" width="30px"> {LANG.order_no_products} </th>
    			<th> {LANG.cart_products} </th>
    			<!-- BEGIN: price1 -->
    			<th class="price text-right"> {LANG.cart_price} ({unit}) </th>
    			<!-- END: price1 -->
    			<th class="text-right" width="60px"> {LANG.cart_numbers} </th>
    			<th class="text-right">{LANG.cart_unit}</td>
    		</tr>
    		</thead>

    		<tbody>
    		<!-- BEGIN: loop -->
    		<tr {bg}>
    			<td align="center"> {pro_no} </td>
    			<td>
    				<a title="{product_name}" href="{link_pro}">{product_name}</a>
    				<!-- BEGIN: display_group -->
    				<p>
						<!-- BEGIN: group -->
						<span class="text-muted" style="margin-right: 10px">{group}</span>
						<!-- END: group -->
					</p>
    				<!-- END: display_group -->
    			</td>
    			<!-- BEGIN: price2 -->
    			<td class="money text-right"><strong>{product_price}</strong></td>
    			<!-- END: price2 -->
    			<td class="text-right" align="center"> {product_number} </td>
    			<td class="text-right">{product_unit}</td>
    		</tr>
    		<!-- END: loop -->
    		</tbody>
    	</table>
	</div>

    <div class="row">
        <div class="col-xs-6">
            <!-- BEGIN: order_note -->
            <em>{LANG.cart_note} : {DATA.order_note}</em>
            <!-- END: order_note -->
        </div>
        <div class="col-xs-6 text-right">
            <!-- BEGIN: price3 -->
            {LANG.cart_total}: <strong id="total">{order_total}</strong> {unit}
            <!-- END: price3 -->
        </div>
    </div>

	<!-- BEGIN: actpay -->
	<hr />
    <div class="text-center">
	<!-- BEGIN: payment -->
	<div class="payment_info">
		<!-- BEGIN: paymentloop -->
		<span class="payment_items"><a title="{DATA_PAYMENT.name}" href="{DATA_PAYMENT.url}"><img src="{DATA_PAYMENT.images_button}" alt="{DATA_PAYMENT.name}" class="img-thumbnail" /></a>
			<br/>
			{DATA_PAYMENT.name} </span>
		<!-- END: paymentloop -->
	</div>
	<!-- END: payment -->
	</div>
	<!-- END: actpay -->

    <!-- BEGIN: intro_pay -->
    <div class="panel panel-default">
    	<div class="panel-body">
        	{intro_pay}
        </div>
    </div>
    <!-- END: intro_pay -->
</div>

<form action="" method="post">
	<input type="hidden" value="{order_id}" name="order_id"><input type="hidden" value="1" name="save">
</form>
<script type="text/javascript">
	$(function() {
		$('#click_print').click(function(event) {
			var href = $(this).attr("href");
			event.preventDefault();
			nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
			return false;
		});
	});
</script>
<!-- END: main -->