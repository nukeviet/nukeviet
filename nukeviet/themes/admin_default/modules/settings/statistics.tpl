<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1" summary="">
		<tbody class="second">
			<tr>
				<td><strong>{LANG.statistics_timezone}</strong></td>
				<td>
				<select name="statistics_timezone">
					<!-- BEGIN: timezone -->
					<option value="{TIMEZONEOP}" {TIMEZONESELECTED}>{TIMEZONELANGVALUE} </option>
					<!-- END: timezone -->
				</select></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.online_upd}</strong></td>
				<td>
				<input type="checkbox" value="1" name="online_upd" {DATA.online_upd} />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.statistic}</strong></td>
				<td>
				<input type="checkbox" value="1" name="statistic" {DATA.statistic} />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.googleAnalyticsID}</strong></td>
				<td>
				<input type="text" name="googleAnalyticsID" value="{DATA.googleAnalyticsID}" style="width: 450px" maxlength="20" />
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.googleAnalyticsSetDomainName_title}</strong></td>
				<td>
				<select name="googleAnalyticsSetDomainName">
					<!-- BEGIN: googleAnalyticsSetDomainName -->
					<option value="{GOOGLEANALYTICSSETDOMAINNAME_VALUE}"{GOOGLEANALYTICSSETDOMAINNAME_SELECTED}>{GOOGLEANALYTICSSETDOMAINNAME_TITLE}</option>
					<!-- END: googleAnalyticsSetDomainName -->
				</select></td>
			</tr>
		</tbody>
	</table>
	<div style="width: 200px; margin: 10px auto; text-align: center;">
		<input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;"/>
	</div>
</form>
<!-- END: main -->