<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script data-show="after" type="text/javascript" src="{JS_PATH}/responsiveCarousel.min.js"></script>
<!-- END: lib -->
<script data-show="after" type="text/javascript">
	jQuery(document).ready(function($){
		$('.product_center_{BLOCKID}').carousel({ autoRotate: 4000, speed: 800, overflow: false, visible: '{NUMVIEW}', itemMinWidth: 150, itemEqualHeight: true, itemMargin: 10 });
		$('.product_center_{BLOCKID}').on("initCarousel", function(event, defaults, obj){
			$('#'+defaults.navigation).find('.previous, .next').css({ opacity: 0 });
			$('#product_center_{BLOCKID}').hover( function(){
				$(this).find('.previous').css({ left: 0 }).stop(true, true).animate({ left: '20px', opacity: 1 });
				$(this).find('.next').css({ right: 0 }).stop(true, true).animate({ right: '20px', opacity: 1 });
			}, function(){
				$(this).find('.previous').animate({ left: 0, opacity: 0 });
				$(this).find('.next').animate({ right: 0, opacity: 0 });
			});
		});
	});
</script>

<div id="product_center_{BLOCKID}">
	<div id="nav-07_{BLOCKID}" class="crsl-nav">
		<a href="#" class="previous">&lt;</a>
		<a href="#" class="next">&gt;</a>
	</div>
	<div class="gallery product_center_{BLOCKID} crsl-items" data-navigation="nav-07_{BLOCKID}">
		<div class="crsl-wrap">
			<!-- BEGIN: items -->
			<div class="crsl-item" align="center">
				<a href="{LINK}" title="{TITLE}"><img src="{SRC_IMG}" width="{WIDTH}" alt="{TITLE}" class="thumbnail" /></a>
				<!-- BEGIN: price -->
				<span class="price">
                    <!-- BEGIN: discounts -->
                    <span class="money show">{PRICE.sale_format} {PRICE.unit}</span>
                    <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                    <!-- END: discounts -->

					<!-- BEGIN: no_discounts -->
					<span class="money">{PRICE.price_format} {PRICE.unit}</span>
					<!-- END: no_discounts -->
				</span>
				<!-- END: price -->
                <!-- BEGIN: contact -->
                <span class="money">{LANG.price_contact}</span>
                <!-- END: contact -->
				<p><a href="{LINK}" title="{TITLE}">{TITLE0}</a></p>
			</div>
			<!-- END: items -->
		</div>
	</div>
</div>
<!-- END: main -->