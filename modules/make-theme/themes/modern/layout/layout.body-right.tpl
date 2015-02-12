<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="main">
	<div class="col-a1">
		[HEADER]
		<div class="clear"></div>
		{MODULE_CONTENT}
		<div class="clear"></div>
		[BOTTOM]
	</div>
	<div class="col-a2 last">
		[RIGHT]
	</div>
	<div class="clear"></div>
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->