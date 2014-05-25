<!-- BEGIN: main -->
{FILE "header_only.tpl"}
{FILE "header_extended.tpl"}
<div class="row">
	<div class="col-md-12">
		[HEADER]
	</div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-7 col-sm-push-3 col-md-push-2 ">
        [TOP]
        {MODULE_CONTENT}
        [BOTTOM]
    </div>
    <div class="col-sm-3 col-md-3 col-sm-push-3 col-md-push-2 ">
        [RIGHT]
    </div>
	<div class="col-sm-3 col-md-2 col-sm-pull-9 col-md-pull-10">
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