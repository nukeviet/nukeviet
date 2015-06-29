<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>
			{LANG.coupons_info}
		</caption>
		<tbody>
			<tr>
				<td><strong>{LANG.title}</strong></td>
				<td>{DATA.title}</td>
				<td><strong>{LANG.coupons_product}</strong></td>
				<td><!-- BEGIN: product_list --><a href="javascript:void(0)" data-toggle="tooltip" title="{LANG.coupons_product_custom_edit}">{LANG.coupons_product_custom}</a><!-- END: product_list --></td>
			</tr>
			<tr>
				<td><strong>{LANG.coupons}</strong></td>
				<td>{DATA.code}</td>
				<td><strong>{LANG.begin_time}</strong></td>
				<td>{DATA.date_start}</td>
			</tr>
			<tr>
				<td><strong>{LANG.coupons_discount}</strong></td>
				<td>{DATA.discount}{DATA.discount_text}</td>
				<td><strong>{LANG.end_time}</strong></td>
				<td>{DATA.date_end}</td>
			</tr>
			<tr>
				<td><strong>{LANG.coupons_total_amount}</strong></td>
				<td>{DATA.total_amount} {MONEY_UNIT}</td>
				<td><strong>{LANG.coupons_uses_per_coupon}</strong></td>
				<td>{DATA.uses_per_coupon_count}/{DATA.uses_per_coupon}</td>
			</tr>
		</tbody>
	</table>
</div>

<div id="coupons_history">
	<p class="text-center">
		<em class="fa fa-spinner fa-spin fa-3x">&nbsp;</em>
	</p>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#coupons_history').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=coupons_view&coupons_history=1&id={CID}');
	});

	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
<!-- END: main -->

<!-- BEGIN: coupons_history -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.coupons_history}</caption>
		<thead>
			<tr>
				<th>{LANG.order_code}</th>
				<th>{LANG.coupons_discount} ({MONEY_UNIT})</th>
				<th>{LANG.order_time}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td> <a href="{DATA.order_view}">{DATA.order_code}</a> </td>
				<td> {DATA.amount} </td>
				<td> {DATA.date_added} </td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr class="text-center">
				<td colspan="3">{NV_GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<!-- END: coupons_history -->