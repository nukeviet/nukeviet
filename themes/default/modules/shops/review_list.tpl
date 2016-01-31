<!-- BEGIN: main -->

<!-- BEGIN: rate_empty -->
<div class="alert alert-info">
	{EMPTY}
</div>
<!-- END: rate_empty -->

<!-- BEGIN: rate_data -->
<div class="row">
	<!-- BEGIN: loop -->
	<div class="review_row">
		<div class="col-xs-10">
			<strong>{DATA.sender}</strong><span class="help-block">{DATA.add_time}</span>
		</div>
		<div class="col-xs-14">
			<div class="clearfix">
				<!-- BEGIN: star -->
				<div class="star-icon">&nbsp;</div>
				<!-- END: star -->
			</div>
			<!-- BEGIN: content -->
			<em class="help-block">"{DATA.content}"</em>
			<!-- END: content -->
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: loop -->

	<!-- BEGIN: generate_page -->
	<div class="text-right pagination-sm">
		{PAGE}
	</div>
	<!-- END: generate_page -->
</div>
<!-- END: rate_data -->

<!-- END: results -->