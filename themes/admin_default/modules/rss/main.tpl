<!-- BEGIN: main -->
<!-- BEGIN: add -->
<div style="margin-top:8px;position:absolute;right:10px;">
	<a class="button1" href="{EDIT_URL}" title="{GLANG.edit}"><span><span>{GLANG.edit}</span></span></a>
</div>
<div style="margin-bottom:20px;">{DATA}</div>
<!-- END: add -->
<!-- BEGIN: edit -->
<form action="{FORM_ACTION}" method="post">
	<input name="save" type="hidden" value="1" />
	<div style="margin-top:8px;margin-bottom:8px;">
		{DATA}
	</div>
	<br />
	<div class="center"><input name="submit1" type="submit" value="{LANG.save}" /></div>
</form>
<!-- END: edit -->
<!-- END: main -->