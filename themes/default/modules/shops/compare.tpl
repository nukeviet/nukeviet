<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-hover">
    	<colgroup>
    		<col width="150px" />
    	</colgroup>

    	<thead>
    		<th>&nbsp;</th>
    		<!-- BEGIN: homeimgthumb -->
    		<th class="text-center">
    			<a href="{link_pro}"><img src="{img_pro}" style="max-width: 100px" /></a></th>
    		<!-- END: homeimgthumb -->
    	</thead>

    	<tbody>
			<tr>
				<td align="center"><button class="btn btn-danger btn-xs text-center" onclick="nv_compare_del( 0, 1 )">{LANG.compare_del_all}</button></td>
				<!-- BEGIN: button -->
				<td align="center">
                    <!-- BEGIN: order -->
                    <a href="javascript:void(0)" id="{id}" title="{title_pro}" onclick="cartorder(this, {GROUP_REQUIE}, '{LINK}')"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                    <!-- END: order -->
					<!-- BEGIN: product_empty -->
                    <button class="btn btn-danger disabled btn-xs">{LANG.product_empty}</button>
                    <!-- END: product_empty -->
					<button class="btn btn-warning btn-xs" onclick="nv_compare_del( {id}, 0 )">{GLANG.delete}</button>
				</td>
				<!-- END: button -->
			</tr>
			<tr>
				<td><strong>{LANG.detail_product_name}</strong></td>
				<!-- BEGIN: title -->
				<td><a href="{link_pro}" title="{title_pro}">{title_pro}</a></td>
				<!-- END: title -->
			</tr>
			<tr>
				<td><strong>{LANG.product_intro}</strong></td>
				<!-- BEGIN: hometext -->
				<td>{intro}</td>
				<!-- END: hometext -->
			</tr>
			<tr>
				<td><strong>{LANG.detail_product}</strong></td>
				<!-- BEGIN: bodytext -->
				<td>{bodytext}</td>
				<!-- END: bodytext -->
			</tr>
			<tr>
				<td><strong>{LANG.title_price}</strong></td>

				<!-- BEGIN: price -->
				<td>
                    <!-- BEGIN: discounts -->
                    <span class="money">{PRICE.sale_format} {PRICE.unit}</span><br />
                    <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                    <!-- END: discounts -->

					<!-- BEGIN: no_discounts -->
					<span class="money">{PRICE.price_format} {PRICE.unit}</span>
					<!-- END: no_discounts -->
				</td>
				<!-- END: price -->

                <!-- BEGIN: contact -->
                <td>{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span></td>
                <!-- END: contact -->
			</tr>
			<tr>
				<td><strong>{LANG.detail_warranty}</strong></td>
				<!-- BEGIN: warranty -->
				<td>{warranty}</td>
				<!-- END: warranty -->
			</tr>
			<tr>
				<td><strong>{LANG.detail_promotional}</strong></td>
				<!-- BEGIN: promotional -->
				<td>{promotional}</td>
				<!-- END: promotional -->
			</tr>
		</tbody>
	</table>
</div>
<div class="msgshow" id="msgshow">&nbsp;</div>
<script type="text/javascript" data-show="after">
	var lang_del_confirm = '{LANG.compare_del_items_confirm}';
</script>

<!-- END: main -->