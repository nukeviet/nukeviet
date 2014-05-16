<!-- BEGIN: main -->
<div id="products" class="clearfix">
	<!-- BEGIN: grid_rows -->
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3 text-center">
		            <a href="{link_pro}" title="{title_pro}" rel="shadowbox">
		                <img src="{img_pro}" alt="{title_pro}" width="140px" class="img-thumbnail">
		            </a>
				</div>
				<div class="col-md-9">
					<p><strong><a href="{link_pro}" title="{title_pro}">{title_pro0}</a></strong></p>
					<!-- BEGIN: price -->
					<p>
						{LANG.detail_pro_price}: <span class="{class_money}">{product_price} {money_unit}</span>
						<!-- BEGIN: discounts -->
						<br />
						<span class="money">{product_discounts} {money_unit}</span>
						<!-- END: discounts -->
					</p>
					<!-- END: price -->
					
					<!-- BEGIN: contact -->
					<p>
						{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
					</p>
					<!-- END: contact -->
					
					<p>
						<!-- BEGIN: order -->
						<a href="javascript:void(0)" id="{ID}" title="{TITLE}" onclick="cartorder(this)"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
						<!-- END: order -->
					</p>
					
					<!-- BEGIN: source -->
					<p>
						<strong>{LANG.detail_source}:</strong> <a href="{link_source}">{source}</a>
					</p>
					<!-- END: source -->
					
					<!-- BEGIN: promotional -->
					<p>
						<strong>{LANG.detail_promotional}:</strong> {promotional}
					</p>
					<!-- END: promotional -->
					
					<!-- BEGIN: warranty -->
					<p>
						<strong>{LANG.detail_warranty}:</strong> {warranty}
					</p>
					<!-- END: warranty -->
					
					<!-- BEGIN: address -->
					<p>
						<strong>{LANG.detail_product_address}:</strong> {address}
					</p>
					<!-- END: address -->
					
					<!-- BEGIN: note -->
					<p>
						<strong>{LANG.cart_note}:</strong> {note}
					</p>
					<!-- END: note -->
					<p>
						<strong>{LANG.product_detail}:</strong> {DETAIL}
					</p>
				</div>
			</div>
		</div>
	</div>
	<!-- END: grid_rows -->
	
	<strong class="text-info text-center">{LANG.compare_empty_items}</strong>
	
</div>
<!-- END: main -->