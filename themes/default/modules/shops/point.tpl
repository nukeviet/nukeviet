<!-- BEGIN: main -->
<blockquote>
	<ul style="padding: 0">
		<li><strong>{LANG.point_cart_text}:</strong> {DATA.point}</li>
		<li><strong>{LANG.point_cart_convention}:</strong> {DATA.money} {DATA.money_unit}</li>
	</ul>
</blockquote>
<h3 class="text-center">{LANG.point_cart_history}</h3>
<!-- BEGIN: history -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.point_order}</th>
				<th>{LANG.profile_products_status}</th>
				<th class="text-right">{LANG.finter_title}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><a href="{HISTORY.link}" title="{HISTORY.order_code}">{HISTORY.order_code}</a></td>
				<td>{HISTORY.point} {LANG.point}</td>
				<td class="text-right">{HISTORY.time}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr class="text-center">
				<td colspan="3">{PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<!-- END: history -->

<!-- BEGIN: point_empty -->
<div class="alert alert-danger">{LANG.point_empty}</div>
<!-- END: point_empty -->

<!-- END: main -->