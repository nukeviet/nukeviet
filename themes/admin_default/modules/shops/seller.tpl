<!-- BEGIN: main -->
<form class="form-inline" name="block_list">
	<span class="pull-left">{C_REPORT}</span>
	<a href="{C_LIST}" class="btn btn-success btn-xs pull-right" style="margin-bottom: 5px">{LANG.content_list}</a>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w50" />
				<col />
				<col />
				<col class="w150" />
				<col />
				<col span="3" class="w150" />
			</colgroup>
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>{LANG.customer_name}</th>
					<th>{LANG.customer_email}</th>
					<th>{LANG.order_phone}</th>
					<th>{LANG.order_address}</th>
					<th>{LANG.order_time}</th>
					<th class="text-center">{LANG.seller_num}</th>
					<th class="text-right">{LANG.order_total}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">{ROW.no}</td>
					<td>{ROW.order_name}</td>
					<td><a href="mailto:{ROW.order_email}" title="Mail to {ROW.order_email}">{ROW.order_email}</a></td>
					<td>{ROW.order_phone}</td>
					<td>{ROW.order_address}</td>
					<td>{ROW.order_time}</td>
					<td class="text-center">{ROW.num}</td>
					<td class="text-right">{ROW.price} {ROW.price_unit}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" class="text-center">
						<!-- BEGIN: generate_page -->
						{GENERATE_PAGE}
						<!-- END: generate_page -->
					</td>
					<td class="text-center red"><strong>{TOTAL.num} {TOTAL.product_unit}</strong></td>
					<td class="text-right red"><strong>{TOTAL.price} {TOTAL.pro_unit}</strong></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- END: main -->