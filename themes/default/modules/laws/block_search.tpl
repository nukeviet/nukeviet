<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<table class="lawitem" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td>
				<form class="form-inline formsearch" action="{FORM_ACTION}" method="get" onsubmit="return nv_check_search_laws(this);">
					<input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
					<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
					<input type="hidden" name="{NV_OP_VARIABLE}" value="search"/>
					<table id="ltablesearch" class="inner-search" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td style="width:150px">{LANG.s_key}</td>
								<td style="width:400px"><input class="form-control" id="ls_key" style="width:300px" type="text" name="q" value="{Q}"/></td>
								<td><input class="btn btn-primary" type="submit" value="{LANG.search}"/></td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.s_pubtime}</td>
								<td style="width:400px">
									<input class="form-control" id="ls_from" style="width:110px" type="text" name="sfrom" value="{FROM}" readonly="readonly"/>									
									 {LANG.to} 
									<input class="form-control" id="ls_to" style="width:110px" type="text" name="sto" value="{TO}" readonly="readonly"/>									
								</td>
								<td><input id="lclearform" type="button" value="{LANG.clear}"/></td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.area}</td>
								<td style="width:400px">
									<select class="form-control" id="ls_area" name="area">
										<!-- BEGIN: area -->
										<option value="{KEY}"{SELECTED}>{TITLE}</option>
										<!-- END: area -->
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.cat}</td>
								<td style="width:400px">
									<select class="form-control" id="ls_cat" name="cat">
										<!-- BEGIN: cat -->
										<option value="{KEY}"{SELECTED}>{TITLE}</option>
										<!-- END: cat -->
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.s_status}</td>
								<td style="width:400px">
									<select class="form-control" id="ls_status" name="status">
										<!-- BEGIN: status -->
										<option value="{status.id}"{status.selected}>{status.title}</option>
										<!-- END: status -->
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.s_signer}</td>
								<td style="width:400px">
									<select class="form-control" id="ls_signer" name="signer">
										<!-- BEGIN: signer -->
										<option value="{KEY}"{SELECTED}>{TITLE}</option>
										<!-- END: signer -->
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>					
						<tbody>
							<tr>
								<td style="width:150px">{LANG.subject}</td>
								<td style="width:400px">
									<select class="form-control" id="ls_subject" name="subject">
										<!-- BEGIN: subject -->
										<option value="{KEY}"{SELECTED}>{TITLE}</option>
										<!-- END: subject -->
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>					
					</table>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#lclearform').click(function(){
		$('#ltablesearch input[type=text]').val('');
		$('#ltablesearch select').val('');
	});
    $("#ls_from,#ls_to").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_siteroot + "images/calendar.gif",
		buttonImageOnly : true
	});
});
function nv_check_search_laws(data)
{
	if( ( $('#ls_key').val() == '' ) && ( $('#ls_cat').val() == 0 ) && ( $('#ls_area').val() == 0 ) && ( $('#ls_subject').val() == 0 ) && ( $('#ls_signer').val() == 0 ) && ( $('#ls_status').val() == 0 ) && ( $('#ls_from').val() == '' ) && ( $('#ls_to').val() == '' ) )
	{
		alert( '{LANG.search_alert}' );
		return false;
	}
	return true;
}
</script>
<!-- END: main -->