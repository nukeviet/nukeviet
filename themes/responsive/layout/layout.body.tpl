<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	<div class="col-md-12">
		[HEADER]
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		[TOP]
		{MODULE_CONTENT}
		[BOTTOM]
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