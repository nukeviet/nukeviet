<!-- BEGIN: main -->
<!-- BEGIN: add -->
<div style="margin-top:8px;position:absolute;right:10px;">
	<a class="btn btn-default" href="{EDIT_URL}" title="{GLANG.edit}">{GLANG.edit}</a>
</div>
<div style="margin-bottom:20px;">
	{DATA}
</div>
<!-- END: add -->
<!-- BEGIN: edit -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<div style="margin-top:8px;margin-bottom:8px;">
		{DATA}
	</div>
	<br />
	<div class="text-center"><input name="submit1" type="submit" value="{LANG.save}" class="btn btn-primary" />
	</div>
</form>
<!-- END: edit -->
<!-- END: main -->