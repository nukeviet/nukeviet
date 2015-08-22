<!-- BEGIN: main -->
<!-- BEGIN: lib -->
<script data-show="after" type="text/javascript" src="{LIB_PATH}js/jquery.bxslider.min.js"></script>
<link href="{LIB_PATH}css/jquery.bxslider.css" rel="stylesheet" />
<!-- END: lib -->
<script type="text/javascript">
$(document).ready(function(){
  $('.slider_{BLOCKID}').bxSlider({
  auto:{AUTO},
  mode:'{MODE}',
  speed:{SPEED},
  slideWidth:{WIDTH},
  slideMargin:{MARGIN},
  minSlides:{NUMVIEW},
  maxSlides:{NUMVIEW},
  moveSlides:{MOVE},
  pager:{PAGER},
  adaptiveHeight: true
  });
});
</script>

<div class="slider_{BLOCKID}">
	<!-- BEGIN: items -->
	<div class="slider_{BLOCKID}_item" align="center">
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

<!-- END: main -->
