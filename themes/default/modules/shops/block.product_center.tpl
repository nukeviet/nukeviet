<!-- BEGIN: main -->
<div class="product_center">
	<script type="text/javascript" src="{THEME_TEM}/js/loopedslider.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			$("#loopedslider").loopedSlider({
				containerClick : false,
				autoStart : 5000,
				restart : 8000,
				slidespeed : 500,
				fadespeed : 500
			});
		});
		//]]>
	</script>
	<div id="loopedslider">
		<div class="container">
			<div class="slides">
				<!-- BEGIN: loop -->
				<div>
					<!-- BEGIN: items -->
					<span class="items"> <a href="{LINK}" title="{TITLE}"><img src="{SRC_IMG}" alt="" /></a>
						<br />
						<a href="{LINK}" title="{TITLE}">{TITLE0}</a> </span>
					<!-- END: items -->
				</div>
				<!-- END: loop -->
			</div>
		</div>
		<ul class="pagination">
			{page}
		</ul>
	</div>
	<div style="clear:both;"></div>
</div>
<!-- END: main -->