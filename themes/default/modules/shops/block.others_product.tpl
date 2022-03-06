<!-- BEGIN: main -->
<div class="others_product">
	<ul>
		<!-- BEGIN: loop -->
		<li class="clearfix">
			<a href="{link}" title="{title}"><img src="{src_img}" alt="{title}" class="img-thumbnail" width="{WIDTH}"/></a>
			<br />
			<a href="{link}" title="{title}">{title}</a>
			<br />
			<!-- BEGIN: price -->
			<span class="price">
                <!-- BEGIN: discounts -->
                <span class="money show">{PRICE.sale_format} {PRICE.unit}</span>
                <span class="discounts_money show">{PRICE.price_format} {PRICE.unit}</span>
                <!-- END: discounts -->

				<!-- BEGIN: no_discounts -->
				<span class="money show">{PRICE.price_format} {PRICE.unit}</span>
				<!-- END: no_discounts -->
			</span>
			<!-- END: price -->
            <!-- BEGIN: contact -->
            <span class="money show">{LANG.price_contact}</span>
            <!-- END: contact -->
			<span class="time">{time}</span>
		</li>
		<!-- END: loop -->
	</ul>
</div>
<!-- END: main -->