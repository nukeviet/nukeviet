<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: content -->
<div class="alert alert-info">
	<h3>{DATA.title}</h3>
	<ul style="padding: 0">
		<li><strong>{LANG.coupons_discount}</strong>: {DATA.discount}{DATA.discount_text}</li>
		<!-- BEGIN: total_amount -->
		<li><strong>{LANG.coupons_total_amount}</strong>: {DATA.total_amount} {MONEY_UNIT}</li>
		<!-- END: total_amount -->
		<li><strong>{LANG.coupons_begin_time}</strong>: {DATA.date_start}</li>
		<li><strong>{LANG.coupons_end_time}</strong>: {DATA.date_end}</li>
	</ul>
	<label class="pull-right"><input type="checkbox" name="coupons_uses" id="coupons_uses" {COUPONS_CHECK} />{LANG.coupons_uses}</label>
	<div class="clear"></div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#coupons_uses').change(function() {
			var coupons_code = $('#coupons_code').val();
			if($(this).is(":checked")) {
				$("#total").load(urload + '&coupons_check=1&coupons_load=1&coupons_code=' + coupons_code + '&t=2');
				$("#cart_" + nv_module_name).load(urload + '&coupons_check=1&coupons_code=' + coupons_code);
			}
			else
			{
				$("#total").load(urload + '&coupons_check=0&coupons_load=1&coupons_code=' + coupons_code + '&t=2');
				$("#cart_" + nv_module_name).load(urload + '&coupons_check=0&coupons_code=' + coupons_code);
			}
		});
	});
</script>
<!-- END: content -->

<!-- END: main -->