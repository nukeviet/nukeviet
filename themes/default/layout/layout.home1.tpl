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
		</div>
		<div class="module_content col-md-16">
			{MODULE_CONTENT}
		</div>
	</div>
	<div class="col-md-24">
		<div class="statistic col-md-6 col-xs-12">
			[STATISTICS]
		</div>
		<div class="bottom_ads col-md-12">
			[BOTTOM_ADS]
		</div>
		<div class="voting col-md-6 col-xs-12">
			[VOTING]
		</div>
	</div>
</div>
<div class="row">
	[FOOTER]
</div>


<!-- <div class="row">
	[HEADER]
</div>
<div class="row">
    <div class="col-sm-12 col-md-13 col-sm-push-6 col-md-push-5">
        [TOP]
        {MODULE_CONTENT}
        [BOTTOM]
    </div>
    <div class="col-sm-6 col-md-6 col-sm-push-6 col-md-push-5">
        [RIGHT]
    </div>
	<div class="col-sm-6 col-md-5 col-sm-pull-18 col-md-pull-19">
		[LEFT]
	</div>
</div>
<div class="row">
	[FOOTER]
</div>
 -->
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->