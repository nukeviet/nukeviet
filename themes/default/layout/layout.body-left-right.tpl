<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	<div class="col-md-12">
		[HEADER]
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		[TOP]
		{MODULE_CONTENT}
		[BOTTOM]
	</div>
	<div class="col-sm-3 col-sm-push-3">
        [RIGHT]
    </div>
	<div class="col-sm-3 col-sm-pull-3">
		[LEFT]
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