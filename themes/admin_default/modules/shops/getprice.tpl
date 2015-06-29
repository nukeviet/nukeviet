<!-- BEGIN: main -->

<!-- BEGIN: typeprice2 -->
<div class="form-group">
	<label class="col-md-4 control-label">{LANG.content_product_product_price}</label>
	<div class="col-md-15">
		<table id="id_price_config" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"> {LANG.discount_to} </th>
					<th class="text-center"> {LANG.content_product_product_price} </th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="2">
						<input type="button" value="{LANG.price_config_add}" onclick="nv_price_config_add_item();" class="btn btn-info" />
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input class="form-control" type="number" name="price_config[{PRICE_CONFIG.id}][number_to]" value="{PRICE_CONFIG.number_to}"/></td>
					<td><input class="form-control" type="text" name="price_config[{PRICE_CONFIG.id}][price]" value="{PRICE_CONFIG.price}" onkeyup="this.value=FormatNumber(this.value);" style="text-align: right"/></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
	<div class="col-md-5">
		<select class="form-control" name="money_unit">
			<!-- BEGIN: money_unit -->
			<option value="{MON.code}" {MON.select}>{MON.currency}</option>
			<!-- END: money_unit -->
		</select>
	</div>
</div>
<!-- END: typeprice2 -->

<!-- BEGIN: product_price -->
<label class="col-md-4 control-label">{LANG.content_product_product_price}</label>
<div class="col-md-8">
	<input class="form-control" type="text" maxlength="50" value="{rowcontent.product_price}" name="product_price" onkeyup="this.value=FormatNumber(this.value);" id="f_money"/>
</div>
<div class="col-md-4">
	<select class="form-control" name="money_unit">
		<!-- BEGIN: money_unit -->
		<option value="{MON.code}" {MON.select}>{MON.currency}</option>
		<!-- END: money_unit -->
	</select>
</div>
<!-- END: product_price -->

<!-- BEGIN: typeprice1 -->
<div class="col-md-8">
	<select class="form-control" name="discount_id">
		<option value="0"> ---{LANG.content_product_discounts}--- </option>
		<!-- BEGIN: discount -->
		<option value="{DISCOUNT.did}" {DISCOUNT.selected} >{DISCOUNT.title}</option>
		<!-- END: discount -->
	</select>
</div>
<!-- END: typeprice1 -->

<!-- END:main -->