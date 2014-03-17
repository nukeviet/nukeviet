<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	<div class="col-md-12">
		[HEADER]
	</div>
</div>
<div class="row">
	<div class="col-sm-8 col-md-9">
		[TOP]
		{MODULE_CONTENT}
		[BOTTOM]
	</div>
	<div class="col-sm-4 col-md-3">
		[RIGHT]
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		[FOOTER]
	</div>
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->