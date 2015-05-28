<!-- BEGIN: main -->
<!-- BEGIN: typeprice2 -->
<td colspan="3">
<table id="id_price_config" class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center"> {LANG.discount_to} </th>
			<th class="text-center"> {LANG.content_product_product_price} </th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2"><input type="button" value="{LANG.price_config_add}" onclick="nv_price_config_add_item();" class="btn btn-info" /></td>
	</tfoot>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td><input class="form-control" type="number" name="price_config[{PRICE_CONFIG.id}][number_to]" value="{PRICE_CONFIG.number_to}"/></td>
			<td><input class="form-control" type="text" name="price_config[{PRICE_CONFIG.id}][price]" value="{PRICE_CONFIG.price}" onkeyup="this.value=FormatNumber(this.value);" style="text-align: right"/></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table></td>
<!-- END: typeprice2 -->
<!-- BEGIN: product_price -->
<th align="right">{LANG.content_product_product_price}</th>
<td><input class="form-control" type="text" maxlength="50" value="{rowcontent.product_price}" name="product_price" onkeyup="this.value=FormatNumber(this.value);" id="f_money" style="text-align: right"/></td>
<!-- END: product_price -->
<td>
<select class="form-control" name="money_unit">
	<!-- BEGIN: money_unit -->
	<option value="{MON.code}" {MON.select}>{MON.currency}</option>
	<!-- END: money_unit -->
</select></td>
<!-- BEGIN: typeprice1 -->
<th align="right" colspan="2">{LANG.content_product_discounts}
<select class="form-control" name="discount_id">
	<option value="0"> --- </option>
	<!-- BEGIN: discount -->
	<option value="{DISCOUNT.did}" {DISCOUNT.selected} >{DISCOUNT.title}</option>
	<!-- END: discount -->
</select> </td> <!-- END: typeprice1 --><!-- END:main -->