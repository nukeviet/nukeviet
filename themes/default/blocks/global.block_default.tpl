<!-- BEGIN: main -->
	<!-- BEGIN: name -->	

	<div class="marquee1">{DATA.name}</div>
	<!-- END: name -->

	<script type='text/javascript' src='//cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js'></script>
	<script>
		$('.marquee1').marquee({
			//duration in milliseconds of the marquee
			duration: 15000,
			//gap in pixels between the tickers
			gap: 50,
			//time in milliseconds before the marquee will start animating
			delayBeforeStart: 0,
			//'left' or 'right'
			direction: 'left',
			//true or false - should the marquee be duplicated to show an effect of continues flow
			duplicated: true
		});
	</script>
<!-- END: main -->