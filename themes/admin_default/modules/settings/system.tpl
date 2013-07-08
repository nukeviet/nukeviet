<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote" style="width:98%">
	<blockquote class="error"> {ERROR} </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="" method="post">
	<table class="tab1">
		<tbody class="second">
			<tr>
				<td><strong>{LANG.closed_site}</strong></td>
				<td><select name="closed_site">
					<!-- BEGIN: closed_site_mode -->
					<option value="{MODE_VALUE}"{MODE_SELECTED}>{MODE_NAME}</option>
					<!-- END: closed_site_mode -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.site_email}</strong></td>
				<td><input type="text" name="site_email" value="{DATA.site_email}" style="width: 450px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.error_send_email}</strong></td>
				<td><input type="text" name="error_send_email" value="{DATA.error_send_email}" style="width: 450px"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.themeadmin}</strong></td>
				<td><select name="admin_theme">
					<!-- BEGIN: admin_theme -->
					<option value="{THEME_NAME}"{THEME_SELECTED}>{THEME_NAME}</option>
					<!-- END: admin_theme -->
				</select></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.date_pattern}</strong></td>
				<td><input type="text" name="date_pattern" value="{DATA.date_pattern}" style="width: 150px"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.time_pattern}</strong></td>
				<td><input type="text" name="time_pattern" value="{DATA.time_pattern}" style="width: 150px"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.searchEngineUniqueID}</strong></td>
				<td><input type="text" name="searchEngineUniqueID" value="{DATA.searchEngineUniqueID}" style="width: 450px" maxlength="50" /></td>
			</tr>
		</tbody>
		<!-- BEGIN: system -->
		<tbody>
			<tr>
				<td><strong>{LANG.lang_multi}</strong></td>
				<td><input type="checkbox" value="1" name="lang_multi" {CHECKED_LANG_MULTI} /></td>
			</tr>
		</tbody>
		<!-- BEGIN: lang_multi -->
		<tbody class="second">
			<tr>
				<td><strong>{LANG.site_lang}</strong></td>
				<td><select name="site_lang">
					<!-- BEGIN: site_lang_option -->
					<option value="{LANGOP}" {SELECTED}>{LANGVALUE} </option>
					<!-- END: site_lang_option -->
				</select></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.lang_geo}</strong></td>
				<td><input type="checkbox" value="1" name="lang_geo" {CHECKED_LANG_GEO} /> ( <a href="{CONFIG_LANG_GEO}">{LANG.lang_geo_config}</a> ) </td>
			</tr>
		</tbody>
		<!-- END: lang_multi -->
		<tbody class="second">
			<tr>
				<td><strong>{LANG.rewrite}</strong></td>
				<td><input type="checkbox" value="1" name="is_url_rewrite" {CHECKED1} /></td>
			</tr>
		</tbody>
		<!-- BEGIN: rewrite_optional -->
		<tbody>
			<tr>
				<td><strong>{LANG.rewrite_optional}</strong></td>
				<td><input type="checkbox" value="1" name="rewrite_optional" {CHECKED2} /></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.rewrite_op_mod}</strong></td>
				<td><input type="checkbox" value="1" name="rewrite_op_mod" {CHECKED3} /></td>
			</tr>
		</tbody>
		<!-- END: rewrite_optional -->
		<tbody>
			<tr>
				<td><strong>{LANG.site_timezone}</strong></td>
				<td><select name="site_timezone"><option value="">{LANG.timezoneAuto}</option>
					<!-- BEGIN: opsite_timezone -->
					<option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
					<!-- END: opsite_timezone -->
				</select></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.gzip_method}</strong></td>
				<td><input type="checkbox" value="1" name="gzip_method" {CHECKED_GZIP_METHOD} /></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.optActive}</strong></td>
				<td><select name="optActive">
					<!-- BEGIN: optActive -->
					<option value="{OPTACTIVE_OP}"{OPTACTIVE_SELECTED}>{OPTACTIVE_TEXT}</option>
					<!-- END: optActive -->
				</select></td>
			</tr>
		</tbody>
		<tbody  class="second">
			<tr>
				<td><strong>{LANG.my_domains}</strong></td>
				<td><input type="text" name="my_domains" value="{MY_DOMAINS}" style="width: 450px"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.cdn_url}</strong></td>
				<td><input type="text" name="cdn_url" value="{DATA.cdn_url}" style="width: 230px"/><input type="button" value="{LANG.cdn_download}"  id="cdn_download"/></td>
			</tr>
		</tbody>
		<!-- END: system -->
	</table>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
		<input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
	</div>
</form>
<script type="text/javascript">
	$('#cdn_download').click(function() {
		window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cdn&cdndl={CDNDL}';
	});
</script>
<!-- END: main -->