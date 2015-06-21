<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	[HEADER]
</div>
<div class="row">
	<div class="col-sm-12 col-md-14 col-sm-push-0 col-md-push-0">
		[TOP]
		{MODULE_CONTENT}
		[BOTTOM]
	</div>
	<div class="col-sm-6 col-md-6 col-sm-push-6 col-md-push-4">
        [RIGHT]
    </div>
	<div class="col-sm-6 col-md-4 col-sm-pull-6 col-md-pull-6">
		[LEFT]
	</div>
</div>
<div class="row">
	[FOOTER]
</div>
{FILE "footer_extended.tpl"}
{FILE "footer_only.tpl"}
<!-- END: main -->