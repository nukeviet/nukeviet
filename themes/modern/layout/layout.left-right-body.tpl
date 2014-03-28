<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="main">
	<div class="col-b3">
		[LEFT]
	</div>
	<div class="col-b2">
		[RIGHT]
	</div>
	<div class="col-b1 last">
		[HEADER]
		<div class="clear"></div>
		{MODULE_CONTENT}
		<div class="clear"></div>
		[BOTTOM]
	</div>
	<div class="clear"></div>
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->