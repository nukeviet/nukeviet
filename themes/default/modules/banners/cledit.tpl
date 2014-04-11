<!-- BEGIN: cledit -->
<form role="form" action="#" method="post" class="form-horizontal" onsubmit="return false;">
	<!-- BEGIN: lt -->
	<div class="form-group">
		<label for="{LT_ID}" class="col-sm-3 control-label">{LT_NAME}:</label>
		<div class="col-sm-9">
			<input class="form-control" name="{LT_ID}" id="{LT_ID}" value="{LT_VALUE}" type="text" maxlength="{LT_MAXLENGTH}" />
		</div>
	</div>	
	<!-- END: lt -->
	<!-- BEGIN: npass -->
	<div class="form-group">
		<label for="{NPASS_ID}" class="col-sm-3 control-label">{NPASS_NAME}:</label>
		<div class="col-sm-9">
			<input class="form-control" name="{NPASS_ID}" id="{NPASS_ID}" value="" type="password" maxlength="{NPASS_MAXLENGTH}" />
		</div>
	</div>	
	<!-- END: npass -->
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<input class="btn btn-primary" type="button" value="{EDIT_NAME}" name="{EDIT_ID}" id="{EDIT_ID}" onclick="{EDIT_ONCLICK}"/>
			<input class="btn btn-primary" type="button" value="{CANCEL_NAME}" name="cancel" onclick="{CANCEL_ONCLICK}"/>
		</div>
	</div>
</form>
<!-- END: cledit -->