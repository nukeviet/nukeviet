<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary"/></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><strong>{LANG.statistics_timezone}</strong></td>
					<td>
					<select name="statistics_timezone" class="form-control w200">
						<!-- BEGIN: timezone -->
						<option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
						<!-- END: timezone -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.online_upd}</strong></td>
					<td><input type="checkbox" value="1" name="online_upd" {DATA.online_upd} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.statistic}</strong></td>
					<td><input type="checkbox" value="1" name="statistic" {DATA.statistic} /></td>
				</tr>
				<tr>
					<td><strong>{LANG.googleAnalyticsID}</strong></td>
					<td><input type="text" class="form-control w400" name="googleAnalyticsID" value="{DATA.googleAnalyticsID}" maxlength="20" /></td>
				</tr>
				<tr>
					<td><strong>{LANG.googleAnalyticsSetDomainName_title}</strong></td>
					<td>
					<select name="googleAnalyticsSetDomainName" class="form-control w200">
						<!-- BEGIN: googleAnalyticsSetDomainName -->
						<option value="{GOOGLEANALYTICSSETDOMAINNAME_VALUE}" {GOOGLEANALYTICSSETDOMAINNAME_SELECTED}>{GOOGLEANALYTICSSETDOMAINNAME_TITLE}</option>
						<!-- END: googleAnalyticsSetDomainName -->
					</select></td>
				</tr>
				<tr>
					<td><strong>{LANG.googleAnalyticsMethod}</strong></td>
					<td>
					<select name="googleAnalyticsMethod" class="form-control w200">
						<!-- BEGIN: googleAnalyticsMethod -->
						<option value="{GOOGLEANALYTICSMETHOD_VALUE}" {GOOGLEANALYTICSMETHOD_SELECTED}>{GOOGLEANALYTICSMETHOD_TITLE}</option>
						<!-- END: googleAnalyticsMethod -->
					</select></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->