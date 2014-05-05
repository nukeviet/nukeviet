<!-- BEGIN: upload_blocked -->
<div class="quote">
	<blockquote><span>{CONTENTS.upload_blocked}</span></blockquote>
</div>
<!-- END: upload_blocked -->
<!-- BEGIN: main -->
<div class="quote">
	<blockquote {CLASS}><span>{CONTENTS.info}</span></blockquote>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form id="frm" method="post" enctype="multipart/form-data" action="{CONTENTS.action}">
	<input type="hidden" value="1" name="save" id="save" />
	<table class="tab1">
		<col class="w250" />
		<col class="w50" />
		<col />
		<tbody>
			<tr>
				<td>{CONTENTS.title.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td><input name="{CONTENTS.title.1}" class="w300 required" type="text" value="{CONTENTS.title.2}" maxlength="{CONTENTS.title.3}" /></td>
			</tr>
			<tr>
				<td>{CONTENTS.plan.0}:</td>
				<td><sup class="required">&lowast;</sup></td>
				<td>
				<select name="{CONTENTS.plan.1}">
					<!-- BEGIN: plan -->
					<option value="{PLAN.key}"{PLAN.selected}>{PLAN.title}</option>
					<!-- END: plan -->
				</select></td>
			</tr>
			<tr>
				<td>{CONTENTS.client.0}:</td>
				<td>&nbsp;</td>
				<td>
				<select name="{CONTENTS.client.1}">
					<option value="">&nbsp;</option>
					<!-- BEGIN: client -->
					<option value="{CLIENT.key}"{CLIENT.selected}>{CLIENT.title}</option>
					<!-- END: client -->
				</select></td>
			</tr>
			<tr>
				<td>{CONTENTS.file_name.0}:</td>
				<td>&nbsp;</td>
				<td><a href="{CONTENTS.file_name.1}" {CONTENTS.file_name.2}><img alt="{CONTENTS.file_name.4}" src="{CONTENTS.file_name.3}" width="16" height="16" style="cursor: pointer" /></a>
				<!-- BEGIN: imageforswf1 -->
				<a href="{CONTENTS.file_name.5}" {CONTENTS.file_name.2}><img alt="{CONTENTS.file_name.4}" src="{CONTENTS.file_name.6}" width="16" height="16" style="cursor: pointer; margin-left: 20px;" /></a>
				<!-- END: imageforswf1 -->
				</td>
			</tr>
			<tr>
				<td>{CONTENTS.upload.0}:</td>
				<td>&nbsp;</td>
				<td><input name="{CONTENTS.upload.1}" type="file" /></td>
			</tr>
			<!-- BEGIN: imageforswf2 -->
			<tr>
				<td>{CONTENTS.upload.2}:</td>
				<td>&nbsp;</td>
				<td><input name="{CONTENTS.upload.3}" type="file" /></td>
			</tr>
			<!-- END: imageforswf2 -->
			<tr>
				<td>{CONTENTS.file_alt.0}:</td>
				<td>&nbsp;</td>
				<td><input class="w300" name="{CONTENTS.file_alt.1}" type="text" value="{CONTENTS.file_alt.2}" maxlength="{CONTENTS.file_alt.3}" /></td>
			</tr>
			<tr>
				<td>{CONTENTS.click_url.0}:</td>
				<td>&nbsp;</td>
				<td><input class="w300 url" name="{CONTENTS.click_url.1}" type="text" value="{CONTENTS.click_url.2}" maxlength="{CONTENTS.click_url.3}" /></td>
			</tr>
			<tr>
				<td>{CONTENTS.target.0}:</td>
				<td>&nbsp;</td>
				<td>
				<select name="{CONTENTS.target.1}">
					<!-- BEGIN: target -->
					<option value="{TARGET.key}"{TARGET.selected}>{TARGET.title}</option>
					<!-- END: target -->
				</select></td>
			</tr>
			<tr>
				<td>{CONTENTS.publ_date.0}:</td>
				<td>&nbsp;</td>
				<td><input name="{CONTENTS.publ_date.1}" class="w100 datepicker" type="text" value="{CONTENTS.publ_date.2}" maxlength="{CONTENTS.publ_date.3}" readonly="readonly" /></td>
			</tr>
			<tr>
				<td>{CONTENTS.exp_date.0}:</td>
				<td>&nbsp;</td>
				<td><input name="{CONTENTS.exp_date.1}" class="w100 datepicker" type="text" value="{CONTENTS.exp_date.2}" maxlength="{CONTENTS.exp_date.3}" readonly="readonly" /></td>
			</tr>
		</tbody>
	</table>
	<div class="center">
		<input type="submit" value="{CONTENTS.submit}" />
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
		$(".datepicker").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
	});
</script>
<!-- END: main -->