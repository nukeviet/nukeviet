<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	<div class="col-md-6 hidden-xs hidden-sm" id="about">
        [ABOUT]
    </div>
    <div class="col-md-12">
        [NEWS]
    </div>
	<div class="col-md-6 hidden-xs hidden-sm" id="top_right">
		[TOPHITS]
	</div>
</div>
<div class="row">
    <div class="col-md-24">
	
		[INTRO]
	</div>
</div>
<div class="row">
    <div class="col-md-8 hidden-xs hidden-sm" id="laws">
		[LAWS]
	</div>
    <div class="col-md-16" id="chuyenmuc">
		{MODULE_CONTENT}
	</div>
</div>
<div class="row" id="bottom">
	<div class="col-md-6 col-sm-12"  id="left_bottom">
        [STATISTICS]
    </div>
    <div class="col-md-12 col-sm-24" id="main_bottom">
        [BOTTOM_ADS]
    </div>
	<div class="col-md-6 col-sm-12" id="right_bottom">
		[VOTING]
	</div>
</div>
<div class="row" id="slide">
	[SLIDE]
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->