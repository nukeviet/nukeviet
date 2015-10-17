<!-- BEGIN: main -->
<div class="block-law marquee" <!-- BEGIN: marquee_data -->data-direction='{DATA.direction}' data-duration='{DATA.duration}' data-pauseOnHover="{DATA.pauseOnHover}" data-duplicated="{DATA.duplicated}" style="height: 200px; overflow: hidden;"<!-- END: marquee_data --> >
	<!-- BEGIN: loop -->
	<div class="m-bottom">
		<h3><a href="{ROW.link}" title="{ROW.title}">{ROW.code}</a></h3>
		<p>{ROW.title}</p>
		<!-- BEGIN: code -->
		<em class="text-muted">Lượt xem:{ROW.view_hits} | {LANG.download_hits}:{ROW.download_hits}</em>
		<!-- END: code -->
	</div>
	<!-- END: loop -->
</div>
<!-- BEGIN: marquee_js -->
<script type='text/javascript' src='{NV_BASE_SITEURL}themes/{TEMPLATE}/js/laws_jquery.marquee.js'></script>
<script type="text/javascript">
	$('.marquee').marquee();
</script>
<!-- END: marquee_js -->

<!-- END: main -->