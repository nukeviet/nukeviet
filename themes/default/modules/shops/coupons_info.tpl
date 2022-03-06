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
<script type="text/javascript" data-show="after">
	$(document).ready(function() {
		nv_check_counpons_use();
		$('#coupons_uses').change(function() {
			var coupons_code = $('#coupons_code').val();
			if($(this).is(":checked")) {
				$("#cart_" + nv_module_name).load(urload + '&coupons_check=1&coupons_code=' + coupons_code);
			}
			else
			{
				$("#cart_" + nv_module_name).load(urload + '&coupons_check=0&coupons_code=' + coupons_code);
			}
			nv_check_counpons_use();
		});
	});

	function nv_check_counpons_use()
	{
		var coupons_code = $('#coupons_code').val();
		if($('#coupons_uses').is(":checked")) {
			$("#total").load(urload + '&coupons_check=1&coupons_load=1&coupons_code=' + coupons_code + '&t=2');
		}
		else
		{
			$("#total").load(urload + '&coupons_check=0&coupons_load=1&coupons_code=' + coupons_code + '&t=2');
		}
	}
</script>
<!-- END: content -->

<!-- END: main -->