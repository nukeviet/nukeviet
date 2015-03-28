<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}js/select2/select2.min.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.global_config}
			</caption>
			<col class="w500" />
			<tbody>
				<tr>
					<td><strong>{LANG.closed_site}</strong></td>
					<td>
					<select name="closed_site" class="form-control w200">
						<!-- BEGIN: closed_site_mode -->
						<option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
						<!-- END: closed_site_mode -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.ssl_https}</strong></td>
					<td><input id="ssl_https" type="checkbox" value="1" name="ssl_https" {CHECKED_SSL_HTTPS} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.site_email}</strong></td>
					<td><input type="text" name="site_email" value="{DATA.site_email}" class="form-control" style="width: 450px"/></td>
				</tr>
				<!-- BEGIN: error_send_email -->
				<tr>
					<td><strong>{LANG.error_send_email}</strong></td>
					<td><input type="text" name="error_send_email" value="{DATA.error_send_email}" class="form-control" style="width: 450px"/></td>
				</tr>
				<!-- END: error_send_email -->
				<tr>
					<td><strong>{LANG.themeadmin}</strong></td>
					<td>
					<select name="admin_theme" class="form-control w200" >
						<!-- BEGIN: admin_theme -->
						<option value="{THEME_NAME}"{THEME_SELECTED}>{THEME_NAME}</option>
						<!-- END: admin_theme -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.date_pattern}</strong></td>
					<td><input type="text" name="date_pattern" value="{DATA.date_pattern}" class="w150 form-control"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.time_pattern}</strong></td>
					<td><input type="text" name="time_pattern" value="{DATA.time_pattern}" class="w150 form-control"/></td>
				</tr>
				<!-- BEGIN: system -->
				<tr>
					<td><strong>{LANG.lang_multi}</strong></td>
					<td><input type="checkbox" value="1" name="lang_multi" {CHECKED_LANG_MULTI} /></td>
				</tr>
				<!-- BEGIN: lang_multi -->
				<tr>
					<td><strong>{LANG.site_lang}</strong></td>
					<td>
					<select name="site_lang" class="form-control w200">
						<!-- BEGIN: site_lang_option -->
						<option value="{LANGOP}" {SELECTED}>{LANGVALUE} </option>
						<!-- END: site_lang_option -->
					</select></td>
				</tr>
				<!-- BEGIN: lang_geo -->
				<tr>
					<td><strong>{LANG.lang_geo}</strong></td>
					<td><input type="checkbox" value="1" name="lang_geo" {CHECKED_LANG_GEO} /> ( <a href="{CONFIG_LANG_GEO}">{LANG.lang_geo_config}</a> ) </td>
				</tr>
				<!-- END: lang_geo -->
				<!-- END: lang_multi -->
				<!-- BEGIN: rewrite_optional -->
				<tr>
					<td><strong>{LANG.rewrite_optional}</strong></td>
					<td><input type="checkbox" value="1" name="rewrite_optional" {CHECKED2} onchange="show_rewrite_op();"/></td>
				</tr>
				<tr id="tr_rewrite_op_mod">
					<td><strong>{LANG.rewrite_op_mod}</strong></td>
					<td>
					<select name="rewrite_op_mod" class="form-control w200">
						<option value="">&nbsp;</option>
						<!-- BEGIN: rewrite_op_mod -->
						<option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
						<!-- END: rewrite_op_mod -->
					</select></td>
				</tr>
				<!-- END: rewrite_optional -->
				<tr>
					<td><strong>{LANG.site_timezone}</strong></td>
					<td>
					<select name="site_timezone" id="site_timezone" class="form-control w200">
						<option value="">{LANG.timezoneAuto}</option>
						<!-- BEGIN: opsite_timezone -->
						<option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
						<!-- END: opsite_timezone -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.gzip_method}</strong></td>
					<td><input type="checkbox" value="1" name="gzip_method" {CHECKED_GZIP_METHOD} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.optActive}</strong></td>
					<td>
					<select name="optActive" class="form-control w200">
						<!-- BEGIN: optActive -->
						<option value="{OPTACTIVE_OP}" {OPTACTIVE_SELECTED}>{OPTACTIVE_TEXT}</option>
						<!-- END: optActive -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.my_domains}</strong></td>
					<td><input type="text" name="my_domains" value="{MY_DOMAINS}" class="form-control" style="width: 450px"/></td>
				</tr>
				<tr>
					<td><strong>{LANG.cdn_url}</strong></td>
					<td><input type="text" name="cdn_url" value="{DATA.cdn_url}" class="form-control pull-left" style="width: 220px; margin-right: 10px"/><input type="button" value="{LANG.cdn_download}"  id="cdn_download" class="btn btn-info"/></td>
				</tr>
				<!-- END: system -->
				<tr>
					<td><strong>{LANG.searchEngineUniqueID}</strong></td>
					<td><input type="text" name="searchEngineUniqueID" value="{DATA.searchEngineUniqueID}" class="form-control" style="width: 450px" maxlength="50" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<caption>
				{LANG.notification_config}
			</caption>
			<col class="w500" />
			<tbody>
				<tr>
					<td><strong>{LANG.notification_active}</strong></td>
					<td><input type="checkbox" name="notification_active" value="1" {CHECKED_NOTIFI_ACTIVE} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.notification_autodel}</strong><span class="help-block">{LANG.notification_autodel_note}</span></td>
					<td><input type="text" name="notification_autodel" value="{DATA.notification_autodel}" class="form-control w100 pull-left" />&nbsp;<span class="text-middle">{LANG.notification_day}</span></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$("#site_timezone").select2();
	});

	$('#cdn_download').click(function() {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cdn&cdndl={CDNDL}';
	});

	$('#ssl_https').change(function() {
		if ($(this).is(":checked")) {
			var returnVal = confirm('{LANG.note_ssl}');
			$(this).attr("checked", returnVal);
		}
	});

	function show_rewrite_op() {
		if ($("input[name=rewrite_optional]").is(":checked")) {
			$('#tr_rewrite_op_mod').show();
		} else {
			$('#tr_rewrite_op_mod').hide();
		}
	}
	show_rewrite_op();
</script>
<!-- END: main -->