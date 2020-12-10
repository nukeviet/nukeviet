<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	[HEADER]
</div>
<div class="row">
	<div class="col-md-24">
		<div class="about col-md-6">
			[ABOUT]
		</div>
		<div class="col-md-12">
			[NEWS]
		</div>
		<div class="tophits col-md-6">
			[TOPHITS]
		</div>
	</div>
	<div class="col-md-24">
		[INTRO]
	</div>
	<div class="col-md-24">
		<div class="laws col-md-8">
			[LAWS]
			[QC]
		</div>
		<div class="module_content col-md-16">
			{MODULE_CONTENT}
		</div>
	</div>
	<div class="col-md-24">
		<div class="statistic col-md-6 col-xs-6">
			[STATISTICS]
		</div>
		<div class="bottom_ads col-md-12">
			[BOTTOM_ADS]
		</div>
		<div class="voting col-md-6 col-xs-6">
			[VOTING]
		</div>
	</div>
</div>
<div class="row">
	[FOOTER]

	[FOOTER_2]
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->