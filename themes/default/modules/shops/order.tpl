<!-- BEGIN: main -->
<div class="block clearfix">
	<div class="step_bar alert alert-success clearfix">
		<a class="step step_disable" title="{LANG.cart_check_cart}" href="{LINK_CART}"><span>1</span>{LANG.cart_check_cart}</a>
		<a class="step step_current" title="{LANG.cart_order}" href="#"><span>2</span>{LANG.cart_order}</a>
	</div>

	<p class="alert alert-info">
		{LANG.order_info}
	</p>

	<form action="" method="post" name="fpost" id="fpost" class="form-horizontal">
		<input type="hidden" value="1" name="postorder">

		<div class="panel panel-default">
		    <div class="panel-body">

                <div class="form-group">
                    <label class="col-sm-3 control-label">{LANG.order_name} <span class="error">(*)</span></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><input name="order_name" class="form-control" value="{DATA.order_name}" /></p>
                        <span class="error">{ERROR.order_name}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{LANG.order_email} <span class="error">(*)</span></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><input type="email" name="order_email" value="{DATA.order_email}" class="form-control" /></p>
                        <span class="error">{ERROR.order_email}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{LANG.order_phone} <span class="error">(*)</span></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><input name="order_phone" class="form-control" value="{DATA.order_phone}" /></p>
                        <span class="error">{ERROR.order_phone}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">{LANG.order_address} <span class="error">(*)</span></label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><input name="order_address" value="{DATA.order_address}" class="form-control" /></p>
                        <span class="error">{ERROR.order_address}</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
        			<tr>
        				<th align="center" width="30px">{LANG.order_no_products}</th>
        				<th>{LANG.cart_products}</th>
        				<!-- BEGIN: price1 -->
        				<th class="price text-right">{LANG.cart_price} ({unit_config})</th>
        				<!-- END: price1 -->
        				<th class="text-center" width="60px">{LANG.cart_numbers}</th>
        				<th>{LANG.cart_unit}</th>
        			</tr>
    			</thead>

    			<tbody>
    			<!-- BEGIN: rows -->
    			<tr {bg}>
    				<td align="center">{pro_no}</td>
    				<td><a title="{title_pro}" href="{link_pro}">{title_pro}</a></td>
    				<!-- BEGIN: price2 -->
    				<td class="money" align="right"><strong>{product_price}</strong></td>
    				<!-- END: price2 -->
    				<td align="center">{pro_num}</td>
    				<td>{product_unit}</td>
    			</tr>
    			<!-- END: rows -->
    			</tbody>
    		</table>
    	</div>

		<!-- BEGIN: price3 -->
		<p class="pull-right">{LANG.cart_total}: <strong id="total">{price_total}</strong> {unit_config}</p>
		<!-- END: price3 -->

        <div class="form-group">
            <label>{LANG.order_note}</label>
            <textarea class="form-control" name="order_note">{DATA.order_note}</textarea>
        </div>

        <div class="text-center">
				<input type="checkbox" name="check" value="1" id="check" /><span id="idselect">{LANG.order_true_info}</span>
				<br />
				<span class="error">{ERROR.order_check}</span></td>
				<br />
				<a class="btn btn-primary" title="{LANG.order_submit_send}" href="#" id="submit_send"><span>{LANG.order_submit_send}</span></a>
		</div>
	</form>
</div>
<script type="text/javascript">
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
<!-- END: main -->