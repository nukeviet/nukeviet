<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<form class="form-horizontal formsearch" action="{FORM_ACTION}" method="get" onsubmit="return nv_check_search_laws(this);">
	<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="{NV_OP_VARIABLE}" value="search"/>
	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.s_key}</label>
		<div class="col-sm-9">
			<input class="form-control" id="ls_key" type="text" name="q" value="{Q}"/>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.s_pubtime}</label>
		<div class="col-sm-9 form-inline">
			<input class="form-control" id="ls_from" style="width:110px" type="text" name="sfrom" value="{FROM}" readonly="readonly"/>
			{LANG.to}
			<input class="form-control" id="ls_to" style="width:110px" type="text" name="sto" value="{TO}" readonly="readonly"/>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.area}</label>
		<div class="col-sm-9">
			<select class="form-control" style="width: 200px" id="ls_area" name="area">
				<!-- BEGIN: area -->
				<option value="{KEY}"{SELECTED}>{TITLE}</option>
				<!-- END: area -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.cat}</label>
		<div class="col-sm-9">
			<select class="form-control" style="width: 200px" id="ls_cat" name="cat">
				<!-- BEGIN: cat -->
				<option value="{KEY}"{SELECTED}>{TITLE}</option>
				<!-- END: cat -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.s_status}</label>
		<div class="col-sm-9">
			<select class="form-control" style="width: 200px" id="ls_status" name="status">
				<!-- BEGIN: status -->
				<option value="{status.id}"{status.selected}>{status.title}</option>
				<!-- END: status -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.s_signer}</label>
		<div class="col-sm-9">
			<select class="form-control" style="width: 200px" id="ls_signer" name="signer">
				<!-- BEGIN: signer -->
				<option value="{KEY}"{SELECTED}>{TITLE}</option>
				<!-- END: signer -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">{LANG.subject}</label>
		<div class="col-sm-9">
			<select class="form-control" style="width: 200px" id="ls_subject" name="subject">
				<!-- BEGIN: subject -->
				<option value="{KEY}"{SELECTED}>{TITLE}</option>
				<!-- END: subject -->
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label">&nbsp;</label>
		<div class="col-sm-9">
			<input class="btn btn-primary" type="submit" value="{LANG.search}"/>
			<input class="btn btn-danger" id="lclearform" type="button" value="{LANG.clear}"/>
		</div>
	</div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#lclearform').click(function() {
			$('#ltablesearch input[type=text]').val('');
			$('#ltablesearch select').val('');
		});
		$("#ls_from,#ls_to").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			yearRange: "2000:2025",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "assets/images/calendar.gif",
			buttonImageOnly : true
		});
	});
	function nv_check_search_laws(data) {
		if (($('#ls_key').val() == '' ) && ($('#ls_cat').val() == 0 ) && ($('#ls_area').val() == 0 ) && ($('#ls_subject').val() == 0 ) && ($('#ls_signer').val() == 0 ) && ($('#ls_status').val() == 0 ) && ($('#ls_from').val() == '' ) && ($('#ls_to').val() == '' )) {
			alert('{LANG.search_alert}');
			return false;
		}
		return true;
	}
</script>
<!-- END: main -->