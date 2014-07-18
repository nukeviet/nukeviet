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
				<td align="center"><button class="btn btn-danger btn-xs text-center" onclick="nv_compare_del( 0, 1 )">{LANG.compare_del_all}</button></strong></td>
				<!-- BEGIN: delete -->
				<td align="center"><button class="btn btn-warning btn-xs" onclick="nv_compare_del( {id}, 0 )">{LANG.compare_del_items}</button></td>
				<!-- END: delete -->
			</tr>
		</tbody>
    	
    	<tbody>
			<tr>
				<td><strong>{LANG.detail_product_name}</strong></td>
				<!-- BEGIN: title -->
				<td><a href="{link_pro}" title="{title_pro}">{title_pro}</a></td>
				<!-- END: title -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.product_intro}</strong></td>
				<!-- BEGIN: hometext -->
				<td>{intro}</td>
				<!-- END: hometext -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.detail_product}</strong></td>
				<!-- BEGIN: bodytext -->
				<td>{bodytext}</td>
				<!-- END: bodytext -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.product_code}</strong></td>
				<!-- BEGIN: product_code -->
				<td>{product_code}</td>
				<!-- END: product_code -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.title_price}</strong></td>
				<!-- BEGIN: product_price -->
				<td>{PRICE.sale_format} {PRICE.unit}</td>
				<!-- END: product_price -->
				
				<!-- BEGIN: contact -->
				<td>{LANG.price_contact}</td>
				<!-- END: contact -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.detail_product_discounts}</strong></td>
				<!-- BEGIN: discount -->
				<td>{PRICE.discount_percent}%</td>
				<!-- END: discount -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.detail_warranty}</strong></td>
				<!-- BEGIN: warranty -->
				<td>{warranty}</td>
				<!-- END: warranty -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.detail_promotional}</strong></td>
				<!-- BEGIN: promotional -->
				<td>{promotional}</td>
				<!-- END: promotional -->
			</tr>
		</tbody>
		
    	<tbody>
			<tr>
				<td><strong>{LANG.custom}</strong></td>
				<!-- BEGIN: custom_field -->
				<td>
					<!-- BEGIN: custom -->
						<!-- BEGIN: loop -->
						<p><strong>{custom.lang}:</strong> {custom.title}</p>
						<!-- END: loop -->
					<!-- END: custom -->
					
					<!-- BEGIN: custom_lang -->
						<!-- BEGIN: loop -->
						<p><strong>{custom_lang.lang}:</strong> {custom_lang.title}</p>
						<!-- END: loop -->
					<!-- END: custom_lang -->
				</td>
				<!-- END: custom_field -->
			</tr>
		</tbody>
		
	</table>
</div>

<script type="text/javascript">
	var lang_del_confirm = '{LANG.compare_del_items_confirm}';
</script>

<!-- END: main -->