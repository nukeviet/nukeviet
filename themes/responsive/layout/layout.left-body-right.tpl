<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="col-sm-3 col-md-2">
	[LEFT]
</div>
<div class="col-xs-12 col-sm-6 col-md-7">
	[HEADER]
	[TOP]
	{MODULE_CONTENT}
	[BOTTOM]
	[FOOTER]
</div>
<div class="col-sm-3 col-md-3">
	[RIGHT]
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->