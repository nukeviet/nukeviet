<!-- BEGIN: main -->
<script type="text/javascript" src="{THEME_TEM}/js/responsiveCarousel.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.product_center').carousel({ autoRotate: 4000, speed: 800, overflow: false, visible: '{NUMVIEW}', itemMinWidth: 150, itemEqualHeight: true, itemMargin: 10 });
		$('.product_center').on("initCarousel", function(event, defaults, obj){
			$('#'+defaults.navigation).find('.previous, .next').css({ opacity: 0 });
			$('#product_center').hover( function(){
				$(this).find('.previous').css({ left: 0 }).stop(true, true).animate({ left: '20px', opacity: 1 });
				$(this).find('.next').css({ right: 0 }).stop(true, true).animate({ right: '20px', opacity: 1 });
			}, function(){
				$(this).find('.previous').animate({ left: 0, opacity: 0 });
				$(this).find('.next').animate({ right: 0, opacity: 0 });
			});
		});
	});
</script>

<div id="product_center">
	<div id="nav-07" class="crsl-nav">
		<a href="#" class="previous">&lt;</a>
		<a href="#" class="next">&gt;</a>
	</div>
	<div class="gallery product_center crsl-items" data-navigation="nav-07">
		<div class="crsl-wrap">
			<!-- BEGIN: items -->
			<figure class="crsl-item" align="center">
				<a href="{LINK}" title="{TITLE}"><img src="{SRC_IMG}" width="{WIDTH}" alt="{TITLE}" class="thumbnail" /></a>
				<p><a href="{LINK}" title="{TITLE}">{TITLE0}</a></p>
			</figure>
			<!-- END: items -->
		</div>
	</div>
</div>
<!-- END: main -->