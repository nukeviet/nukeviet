<!-- BEGIN: main -->
{FILE "shipping_menu.tpl"}
<div class="well">
	<form action="{NV_BASE_ADMINURL}index.php" method="get">
		<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
		<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<input type="text" class="form-control" value="{SEARCH.keywords}" name="keywords" placeholder="{LANG.search_key}" />
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<select class="form-control" name="shops_id">
						<option value="">---{LANG.shops_chose}---</option>
						<!-- BEGIN: shops_loop -->
						<option value="{SHOPS.id}" {SHOPS.selected}>{SHOPS.name}</option>
						<!-- END: shops_loop -->
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<select class="form-control" name="carier_id">
						<option value="">---{LANG.carrier_chose}---</option>
						<!-- BEGIN: carrier_loop -->
						<option value="{CARRIER.id}" {CARRIER.selected}>{CARRIER.name}</option>
						<!-- END: carrier_loop -->
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="{LANG.search}" />
				</div>
			</div>
		</div>
	</form>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.order_code}</th>
				<th>{LANG.shipping_name}</th>
				<th>{LANG.order_address}</th>
				<th>{LANG.shops}</th>
				<th>{LANG.carrier}</th>
				<th>{LANG.weights}</th>
				<th class="text-right">{LANG.carrier_price}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><a href="{VIEW.order_view}" title="{VIEW.order_code}">{VIEW.order_code}</a></td>
				<td>{VIEW.ship_name} - {VIEW.ship_phone}</td>
				<td>{VIEW.ship_location_title} <span class="help-block">{VIEW.ship_address_extend}</span></td>
				<td>{VIEW.ship_shops_title}</td>
				<td>{VIEW.ship_carrier_title}</td>
				<td>{VIEW.weight}{VIEW.weight_unit}</td>
				<td class="text-right">{VIEW.ship_price} {VIEW.ship_price_unit}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr class="text-center">
				<td colspan="5">{NV_GENERATE_PAGE}</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<!-- END: main -->